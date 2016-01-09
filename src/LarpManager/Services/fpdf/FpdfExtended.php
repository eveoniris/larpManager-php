<?php
//JOS - 22/11/2015 - Export de la fiche de personnage en PDF - Phase 1

namespace LarpManager\Services\fpdf;

require('FPDF.php');

class FpdfExtended extends FPDF
{
	/*******************************************************************************
	 * FPDF Layers support                                                          *
	 *                                                                              *
	 * Version: 1.0                                                                 *
	 * Author:  Olivier PLATHEY                                                     *
	 * Reference : http://www.fpdf.org/en/script/script97.php                       *
	 *******************************************************************************/
	
	var $layers = array();
	var $current_layer = -1;
	var $open_layer_pane = false;
	
	function AddLayer($name, $visible=true)
	{
	    $this->layers[] = array('name'=>$name, 'visible'=>$visible);
	    return count($this->layers)-1;
	}
	
	function BeginLayer($id)
	{
	    $this->EndLayer();
	    $this->_out('/OC /OC'.$id.' BDC');
	    $this->current_layer = $id;
	}
	
	function EndLayer()
	{
	    if($this->current_layer>=0)
	    {
	        $this->_out('EMC');
	        $this->current_layer = -1;
	    }
	}
	
	function OpenLayerPane()
	{
	    $this->open_layer_pane = true;
	}
	
	function _endpage()
	{
	    $this->EndLayer();
	    parent::_endpage();
	}
	
	function _enddoc()
	{
	    if($this->PDFVersion<'1.5')
	        $this->PDFVersion='1.5';
	    parent::_enddoc();
	}
	
	function _putlayers()
	{
	    foreach($this->layers as $id=>$layer)
	    {
	        $this->_newobj();
	        $this->layers[$id]['n'] = $this->n;
	        $this->_out('<</Type /OCG /Name '.$this->_textstring($this->_UTF8toUTF16($layer['name'])).'>>');
	        $this->_out('endobj');
	    }
	}
	
	function _putresources()
	{
	    $this->_putlayers();
	    parent::_putresources();
	}
	
	function _putresourcedict()
	{
	    parent::_putresourcedict();
	    $this->_out('/Properties <<');
	    foreach($this->layers as $id=>$layer)
	        $this->_out('/OC'.$id.' '.$layer['n'].' 0 R');
	    $this->_out('>>');
	}
	
	function _putcatalog()
	{
	    parent::_putcatalog();
	    $l = '';
	    $l_off = '';
	    foreach($this->layers as $layer)
	    {
	        $l .= $layer['n'].' 0 R ';
	        if(!$layer['visible'])
	            $l_off .= $layer['n'].' 0 R ';
	    }
	    $this->_out("/OCProperties <</OCGs [$l] /D <</OFF [$l_off] /Order [$l]>>>>");
	    if($this->open_layer_pane)
	        $this->_out('/PageMode /UseOC');
	}
	
	/*******************************************************************************
	 * FPDF Alpha Channel support                                                   *
	 *                                                                              *
	 * Version: 1.0                                                                 *
	 * Author:  Valentin Schmidt                                                    *
	 * Reference : http://www.fpdf.org/fr/script/script83.php                       *
	 *******************************************************************************/
	
	var $tmpFiles = array();
	
	/*******************************************************************************
	 *                                                                              *
	 *                               Public methods                                 *
	 *                                                                              *
	 *******************************************************************************/
	function Image($file, $x=null, $y=null, $w=0, $h=0, $type='', $link='', $isMask=false, $maskImg=0)
	{
		//Put an image on the page
		if(!isset($this->images[$file]))
		{
			//First use of this image, get info
			if($type=='')
			{
				$pos=strrpos($file,'.');
				if(!$pos)
					$this->Error('Image file has no extension and no type was specified: '.$file);
					$type=substr($file,$pos+1);
			}
			$type=strtolower($type);
			if($type=='png'){
				$info=$this->_parsepng($file);
				if($info=='alpha')
					return $this->ImagePngWithAlpha($file,$x,$y,$w,$h,$link);
			}
			else
			{
				if($type=='jpeg')
					$type='jpg';
					$mtd='_parse'.$type;
					if(!method_exists($this,$mtd))
						$this->Error('Unsupported image type: '.$type);
						$info=$this->$mtd($file);
			}
			if($isMask){
				if(in_array($file,$this->tmpFiles))
					$info['cs']='DeviceGray'; //hack necessary as GD can't produce gray scale images
					if($info['cs']!='DeviceGray')
						$this->Error('Mask must be a gray scale image');
						if($this->PDFVersion<'1.4')
							$this->PDFVersion='1.4';
			}
			$info['i']=count($this->images)+1;
			if($maskImg>0)
				$info['masked'] = $maskImg;
				$this->images[$file]=$info;
		}
		else
			$info=$this->images[$file];
			//Automatic width and height calculation if needed
			if($w==0 && $h==0)
			{
				//Put image at 72 dpi
				$w=$info['w']/$this->k;
				$h=$info['h']/$this->k;
			}
			elseif($w==0)
			$w=$h*$info['w']/$info['h'];
			elseif($h==0)
			$h=$w*$info['h']/$info['w'];
			//Flowing mode
			if($y===null)
			{
				if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
				{
					//Automatic page break
					$x2=$this->x;
					$this->AddPage($this->CurOrientation,$this->CurPageFormat);
					$this->x=$x2;
				}
				$y=$this->y;
				$this->y+=$h;
			}
			if($x===null)
				$x=$this->x;
				if(!$isMask)
					$this->_out(sprintf('q %.2F 0 0 %.2F %.2F %.2F cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
					if($link)
						$this->Link($x,$y,$w,$h,$link);
						return $info['i'];
	}
	
	// needs GD 2.x extension
	// pixel-wise operation, not very fast
	function ImagePngWithAlpha($file,$x,$y,$w=0,$h=0,$link='')
	{
		$tmp_alpha = tempnam('.', 'mska');
		$this->tmpFiles[] = $tmp_alpha;
		$tmp_plain = tempnam('.', 'mskp');
		$this->tmpFiles[] = $tmp_plain;
	
		list($wpx, $hpx) = getimagesize($file);
		$img = imagecreatefrompng($file);
		$alpha_img = imagecreate( $wpx, $hpx );
	
		// generate gray scale pallete
		for($c=0;$c<256;$c++)
			ImageColorAllocate($alpha_img, $c, $c, $c);
	
			// extract alpha channel
			$xpx=0;
			while ($xpx<$wpx){
				$ypx = 0;
				while ($ypx<$hpx){
					$color_index = imagecolorat($img, $xpx, $ypx);
					$col = imagecolorsforindex($img, $color_index);
					imagesetpixel($alpha_img, $xpx, $ypx, $this->_gamma( (127-$col['alpha'])*255/127) );
					++$ypx;
				}
				++$xpx;
			}
	
			imagepng($alpha_img, $tmp_alpha);
			imagedestroy($alpha_img);
	
			// extract image without alpha channel
			$plain_img = imagecreatetruecolor ( $wpx, $hpx );
			imagecopy($plain_img, $img, 0, 0, 0, 0, $wpx, $hpx );
			imagepng($plain_img, $tmp_plain);
			imagedestroy($plain_img);
	
			//first embed mask image (w, h, x, will be ignored)
			$maskImg = $this->Image($tmp_alpha, 0,0,0,0, 'PNG', '', true);
	
			//embed image, masked with previously embedded mask
			$this->Image($tmp_plain,$x,$y,$w,$h,'PNG',$link, false, $maskImg);
	}
	
	function Close()
	{
		parent::Close();
		// clean up tmp files
		foreach($this->tmpFiles as $tmp)
			@unlink($tmp);
	}
	
	/*******************************************************************************
	 *                                                                              *
	 *                               Private methods                                *
	 *                                                                              *
	 *******************************************************************************/
	function _putimages()
	{
		$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
		reset($this->images);
		while(list($file,$info)=each($this->images))
		{
			$this->_newobj();
			$this->images[$file]['n']=$this->n;
			$this->_out('<</Type /XObject');
			$this->_out('/Subtype /Image');
			$this->_out('/Width '.$info['w']);
			$this->_out('/Height '.$info['h']);
	
			if(isset($info['masked']))
				$this->_out('/SMask '.($this->n-1).' 0 R');
	
				if($info['cs']=='Indexed')
					$this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
					else
					{
						$this->_out('/ColorSpace /'.$info['cs']);
						if($info['cs']=='DeviceCMYK')
							$this->_out('/Decode [1 0 1 0 1 0 1 0]');
					}
					$this->_out('/BitsPerComponent '.$info['bpc']);
					if(isset($info['f']))
						$this->_out('/Filter /'.$info['f']);
						if(isset($info['parms']))
							$this->_out($info['parms']);
							if(isset($info['trns']) && is_array($info['trns']))
							{
								$trns='';
								for($i=0;$i<count($info['trns']);$i++)
									$trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
									$this->_out('/Mask ['.$trns.']');
							}
							$this->_out('/Length '.strlen($info['data']).'>>');
							$this->_putstream($info['data']);
							unset($this->images[$file]['data']);
							$this->_out('endobj');
							//Palette
							if($info['cs']=='Indexed')
							{
								$this->_newobj();
								$pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
								$this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
								$this->_putstream($pal);
								$this->_out('endobj');
							}
		}
	}
	
	// GD seems to use a different gamma, this method is used to correct it again
	function _gamma($v){
		return pow ($v/255, 2.2) * 255;
	}
	
	// this method overriding the original version is only needed to make the Image method support PNGs with alpha channels.
	// if you only use the ImagePngWithAlpha method for such PNGs, you can remove it from this script.
	function _parsepng($file)
	{
		//Extract info from a PNG file
		$f=fopen($file,'rb');
		if(!$f)
			$this->Error('Can\'t open image file: '.$file);
			//Check signature
			if($this->_readstream($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
				$this->Error('Not a PNG file: '.$file);
				//Read header chunk
				$this->_readstream($f,4);
				if($this->_readstream($f,4)!='IHDR')
					$this->Error('Incorrect PNG file: '.$file);
					$w=$this->_readint($f);
					$h=$this->_readint($f);
					$bpc=ord($this->_readstream($f,1));
					if($bpc>8)
						$this->Error('16-bit depth not supported: '.$file);
						$ct=ord($this->_readstream($f,1));
						if($ct==0)
							$colspace='DeviceGray';
							elseif($ct==2)
							$colspace='DeviceRGB';
							elseif($ct==3)
							$colspace='Indexed';
							else {
								fclose($f);      // the only changes are
								return 'alpha';  // made in those 2 lines
							}
							if(ord($this->_readstream($f,1))!=0)
								$this->Error('Unknown compression method: '.$file);
								if(ord($this->_readstream($f,1))!=0)
									$this->Error('Unknown filter method: '.$file);
									if(ord($this->_readstream($f,1))!=0)
										$this->Error('Interlacing not supported: '.$file);
										$this->_readstream($f,4);
										$parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
										//Scan chunks looking for palette, transparency and image data
										$pal='';
										$trns='';
										$data='';
										do
										{
											$n=$this->_readint($f);
											$type=$this->_readstream($f,4);
											if($type=='PLTE')
											{
												//Read palette
												$pal=$this->_readstream($f,$n);
												$this->_readstream($f,4);
											}
											elseif($type=='tRNS')
											{
												//Read transparency info
												$t=$this->_readstream($f,$n);
												if($ct==0)
													$trns=array(ord(substr($t,1,1)));
													elseif($ct==2)
													$trns=array(ord(substr($t,1,1)), ord(substr($t,3,1)), ord(substr($t,5,1)));
													else
													{
														$pos=strpos($t,chr(0));
														if($pos!==false)
															$trns=array($pos);
													}
													$this->_readstream($f,4);
											}
											elseif($type=='IDAT')
											{
												//Read image data block
												$data.=$this->_readstream($f,$n);
												$this->_readstream($f,4);
											}
											elseif($type=='IEND')
											break;
											else
												$this->_readstream($f,$n+4);
										}
										while($n);
										if($colspace=='Indexed' && empty($pal))
											$this->Error('Missing palette in '.$file);
											fclose($f);
											return array('w'=>$w, 'h'=>$h, 'cs'=>$colspace, 'bpc'=>$bpc, 'f'=>'FlateDecode', 'parms'=>$parms, 'pal'=>$pal, 'trns'=>$trns, 'data'=>$data);
	}

	/*******************************************************************************
	 * FPDF Tables support                                                          *
	 *                                                                              *
	 * Version: 1.1                                                                 *
	 * Initial Author:  Olivier PLATHEY                                             *
	 * Author:  Romain FERET                                                        *
	 * Reference : http://fpdf.org/fr/tutorial/tuto5.htm                            *
	 *******************************************************************************/
	
	// Tableau simple
	function BasicTable($header = null, $data)
	{
		$fPdfCurrentX = $this->GetX();
		$fPdfCurrentY = $this->GetY();
		if($header != null)
		{
			// En-tête
			foreach($header as $col)
			$this->Cell(40,7,$col,1);
			$fPdfCurrentY += 7;
			$this->SetXY($fPdfCurrentX, $fPdfCurrentY);
		}
		// Données
		foreach($data as $row)
		{
			foreach($row as $col)
			{
				$this->Cell(40,6,$col,1);
			}
			$fPdfCurrentY += 6;
			$this->SetXY($fPdfCurrentX, $fPdfCurrentY);
		}
	}
	
	function FixtedWidthBasicTable($data, $tableWidth = 100, $defaultColumHeight = 6)
	{
		$fPdfCurrentX = $this->GetX();
		$fPdfCurrentY = $this->GetY();
		foreach($data as $row)
		{
			$cellCount = count($row);
			$rowWidth = floor($tableWidth/$cellCount);
			$rowWidthDelta = $tableWidth - $cellCount * $rowWidth;
			$cellCpt = 0;
			foreach($row as $col)
			{
				$cellCpt++;
				//Ajout du delta si la division des tailles du tableau ne tombe pas juste
				if($cellCpt < $cellCount)
				{
					$this->Cell($rowWidth,$defaultColumHeight,$col,1);
				}
				else
				{
					$this->Cell($rowWidth+$rowWidthDelta,$defaultColumHeight,$col,1);
				}
			}
			$fPdfCurrentY += $defaultColumHeight;
			$this->SetXY($fPdfCurrentX, $fPdfCurrentY);
		}
	}
	
	//JOS - 22/11/2015 - Export de la fiche de personnage en PDF - Phase 2 : Implémentation des feuilles de style pour tableau
	function FixtedWidthStyleSheetTable($pData, $pStyleSheet = null, $tableWidth = 100, $defaultColumHeight = 6)
	{
		$lHasStyleSheet = $pStyleSheet != null;
		$lDefaultCellStyle = null;
		$lCurentCellStyle = null;
		
		$fPdfCurrentX = $this->GetX();
		$fPdfCurrentY = $this->GetY();
		
		//Arguments check
		if(!is_array($pData) || $this->_is_assoc($pData))
		{
			throw new \InvalidArgumentException(__FUNCTION__.' function pData argument only accepts 2 dimension sequential array. Input type was: '.gettype($pData));
		}
		
		if($lHasStyleSheet)
		{
			if(!is_array($pStyleSheet) || !$this->_is_assoc($pStyleSheet))
			{
				throw new \InvalidArgumentException(__FUNCTION__.' function pStyleSheet argument only associative array. Input type was: '.gettype($pStyleSheet));
			}
			if(!array_key_exists('default', $pStyleSheet))
			{
				throw new \InvalidArgumentException(__FUNCTION__.' function pStyleSheet argument only associative array with one \'default\' key. \'default\' key no found in Array.');
			}
			if(!is_array($pStyleSheet['default']) || !$this->_is_assoc($pStyleSheet['default']))
			{
				throw new \InvalidArgumentException(__FUNCTION__.' function pStyleSheet[\'default\'] argument only associative array. Input type was: '.gettype($pStyleSheet['default']));
			}
			if(!array_key_exists('default', $pStyleSheet['default']))
			{
				throw new \InvalidArgumentException(__FUNCTION__.' function pStyleSheet[\'default\'] argument only associative array with one \'default\' key. \'default\' key no found in Array.');
			}
			
			$lDefaultCellStyle = $pStyleSheet['default']['default'];
			$lCurentCellStyle = $lDefaultCellStyle;
			
			
			if(!array_key_exists('even', $pStyleSheet)) //Gestion des styles pair
			{
				$pStyleSheet['even'] = $pStyleSheet['default'];
			}
			if(!array_key_exists('odd', $pStyleSheet)) //Gestion des styles impair
			{
				$pStyleSheet['odd'] = $pStyleSheet['default'];
			}
		}
		
		
		$lRowCount = count($pData);
		for($loCptRow = 0; $loCptRow < $lRowCount; $loCptRow++)
		{
			$loRow = $pData[$loCptRow];

			//Arguments check
			if(!is_array($loRow) || $this->_is_assoc($loRow))
			{
				throw new \InvalidArgumentException(__FUNCTION__.' function pData argument only accepts 2 dimension sequential array. Input type was: '.gettype($pData));
			}

			$loRowDefaultCellStyle = null;
			$loRowStyleSheet = null;				
			
			if($lHasStyleSheet)
			{
				if(array_key_exists(strval($loCptRow), $pStyleSheet))
				{
					$loRowStyleSheet = $pStyleSheet[strval($loCptRow)];
					
					if(!is_array($loRowStyleSheet) /*|| !$this->_is_assoc($loRowStyleSheet)*/) //Fix : même si les clefs sont de type string si leur valeurs sont numériques _is_assoc retourne true
					{
						throw new \InvalidArgumentException(__FUNCTION__.' function pStyleSheet[\''.strval($loCptRow).'\'] argument only accept associative array. Input type was: '.gettype($loRowStyleSheet));
					}
					
					//Si pour la ligne le style par défault est paramétré on le prend sinon on prend le style par défaut global
					if(array_key_exists('default', $loRowStyleSheet))
					{
						$loRowDefaultCellStyle = $loRowStyleSheet['default'];
					}
					else 
					{
						$loRowDefaultCellStyle = $lDefaultCellStyle;
					}
				}
				else 
				{
					$loRowStyleSheet = (($loCptRow % 2 == 0) ? $pStyleSheet['even'] : $pStyleSheet['odd']); //Gestion du défault par colonne ? à tester
					if(array_key_exists('default', $loRowStyleSheet))
					{
						$loRowDefaultCellStyle = $loRowStyleSheet['default'];
					}
					else
					{
						$loRowDefaultCellStyle = $lDefaultCellStyle;
					}
				}
			}
			
			$loCellCount = count($loRow);
			$loRowWidth = floor($tableWidth/$loCellCount);
			$loRowWidthDelta = $tableWidth - $loCellCount * $loRowWidth;
			
			for($loCptCell = 0; $loCptCell < $loCellCount; $loCptCell++)
			{
				$loCell = $loRow[$loCptCell];
				
				$loCellStyleSheet = $loRowDefaultCellStyle;
				
				if($lHasStyleSheet)
				{
					if(array_key_exists(strval($loCptCell), $loRowStyleSheet))
					{
						$loCellStyleSheet = $loRowStyleSheet[strval($loCptCell)];
					}
					
					foreach ($loCellStyleSheet as $loCellStyleSheetFunctionName => $loCellStyleSheetFunctionArgs)
					{
						call_user_func_array(
							array(
								$this,
								$loCellStyleSheetFunctionName
							),
							$loCellStyleSheetFunctionArgs
						);
					}
				}
				
				//Ajout du delta si la division des tailles du tableau ne tombe pas juste
				if($loCptCell < ($loCellCount - 1))
				{
					$this->Cell($loRowWidth,$defaultColumHeight,$loCell,1);
				}
				else
				{
					$this->Cell($loRowWidth+$loRowWidthDelta,$defaultColumHeight,$loCell,1);
				}
			}
			$fPdfCurrentY += $defaultColumHeight;
			$this->SetXY($fPdfCurrentX, $fPdfCurrentY);
		}
	}
	
	private function _is_assoc(array $array) 
	{
		return (bool)count(array_filter(array_keys($array), 'is_string'));
	}
	
}


// Handle special IE contype request
if(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']=='contype')
{
	header('Content-Type: application/pdf');
	exit;
}
