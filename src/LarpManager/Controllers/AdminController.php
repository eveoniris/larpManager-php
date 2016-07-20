<?php

namespace LarpManager\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


/**
 * LarpManager\Controllers\AdminController
 *
 * @author kevin
 *
 */
class AdminController
{
	// Returns a file size limit in bytes based on the PHP upload_max_filesize
	// and post_max_size
	private function file_upload_max_size() {
		static $max_size = -1;
	
		if ($max_size < 0) {
			// Start with post_max_size.
			$max_size = $this->parse_size(ini_get('post_max_size'));
	
			// If upload_max_size is less, then reduce. Except if upload_max_size is
			// zero, which indicates no limit.
			$upload_max = $this->parse_size(ini_get('upload_max_filesize'));
			if ($upload_max > 0 && $upload_max < $max_size) {
				$max_size = $upload_max;
			}
		}
		return $max_size;
	}
	
	/**
	 * Met en forme une taille
	 * 
	 * @param unknown $size
	 */
	private function parse_size($size) {
		$unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
		$size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
		if ($unit) {
			// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
			return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
		}
		else {
			return round($size);
		}
	}
	
	/**
	 * Simplifie une taille en bytes et fourni le symbole adequat
	 * @param unknown $bytes
	 */
	private function getSymbolByQuantity($bytes) {
		$symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
		$exp =  $bytes ? floor(log($bytes) / log(1024)) : 0;
		return sprintf('%.2f '.$symbols[$exp], ($bytes/pow(1024, floor($exp))));
	}
	
	/**
	 * Calcul la taille d'un dossier
	 * @param unknown $path
	 */
	private function foldersize($path) {
		$total_size = 0;
		$files = scandir($path);
		$cleanPath = rtrim($path, '/'). '/';
	
		foreach($files as $t) {
			if ($t<>"." && $t<>"..") {
				$currentFile = $cleanPath . $t;
				if (is_dir($currentFile)) {
					$size = $this->foldersize($currentFile);
					$total_size += $size;
				}
				else {
					$size = filesize($currentFile);
					$total_size += $size;
				}
			}
		}
	
		return $total_size;
	}
	
	/**
	 * Page d'accueil de l'interface d'administration
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$extensions = get_loaded_extensions();
		$phpVersion = phpversion();
		$zendVersion = zend_version();
		$uploadMaxSize = $this->file_upload_max_size();
		
		// taille du cache
		$cacheTotalSpace = $this->foldersize(__DIR__.'/../../../cache');
		if ( $cacheTotalSpace )
		{
			$cacheTotalSpace = $this->getSymbolByQuantity($cacheTotalSpace);
		}
		
		// taille du log
		$logTotalSpace = $this->getSymbolByQuantity($this->foldersize(__DIR__.'/../../../logs'));
		
		// taille des documents
		$docTotalSpace = $this->getSymbolByQuantity($this->foldersize(__DIR__.'/../../../private'));
		
		return $app['twig']->render('admin/index.twig', array(
				'phpVersion' => $phpVersion,
				'zendVersion' => $zendVersion,
				'uploadMaxSize' => $uploadMaxSize,
				'extensions' => $extensions,
				'cacheTotalSpace' => $cacheTotalSpace,
				'logTotalSpace' => $logTotalSpace,
				'docTotalSpace' => $docTotalSpace,
		));
	}
	
	/**
	 * Consulter les logs de larpManager
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function logAction(Request $request, Application $app)
	{
		if ( $app['config']['env']['env'] == 'prod' )
		{
			$filename = __DIR__.'/../../../logs/production.log';
		}
		else
		{
			$filename = __DIR__.'/../../../logs/development.log';	
		}
		
		$logfile = new \SplFileObject($filename);
		
		$lines = array();
		$linesFatal = array();
		
		$handle = fopen($filename,"r");
		if ( $logfile )
		{
			$lineCount = 0;
			while ( ! $logfile->eof() )
			{
				$linetmp = $logfile->current();
				if ( preg_match("/CRITICAL/", $linetmp) === 1)
				{
					$linesFatal[] = $linetmp;			
				}
				$lineCount++;
				$logfile->next();
			}
			
			$start = $lineCount - 20;
			if ( $start < 0 ) $start = 0;
			
			
			$logfile->seek($start); 
			while ( ! $logfile->eof() )
			{
				$lines[] = $logfile->current();
				$logfile->next();
			}
		}
		else
		{
			var_dump("impossible d'ouvrir $filename");
		}

		$lines = array_reverse($lines);
		$linesFatal = array_reverse($linesFatal);
		
		return $app['twig']->render('admin/log.twig', array(
				'lines' => $lines,
				'linesFatal' => $linesFatal
		));
	}
	
	/**
	 * Exporter la base de données
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function databaseExportAction(Request $request, Application $app)
	{
		return $app['twig']->render('admin/databaseExport.twig');
	}
	
	/**
	 * Mettre à jour la base de données
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function databaseUpdateAction(Request $request, Application $app)
	{
		return $app['twig']->render('admin/databaseUpdate.twig');
	}
	
	/**
	 * Vider le cache
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function cacheEmptyAction(Request $request, Application $app)
	{
		$app['twig']->clearTemplateCache();
		$app['twig']->clearCacheFiles();
			
		$app['session']->getFlashBag()->add('success', 'Le cache a été vidé.');
		return $app->redirect($app['url_generator']->generate('admin'),301);
	}
	
	/**
	 * Vider les logs
	 */
	public function logEmptyAction(Request $request, Application $app)
	{
		$filename = __DIR__.'/../../../logs/production.log';

		$myTextFileHandler = @fopen("$filename","r+");
		@ftruncate($myTextFileHandler, 0);
		@fclose($myTextFileHandle);
		
		$filename = __DIR__.'/../../../logs/development.log';
		
		$myTextFileHandler = @fopen("$filename","r+");
		@ftruncate($myTextFileHandler, 0);
		@fclose($myTextFileHandle);
		
		$app['session']->getFlashBag()->add('success', 'Les logs ont été vidés.');
		return $app->redirect($app['url_generator']->generate('admin'),301);
	}
	
	/**
	 * Fourni les listes des utilisateurs n'ayants pas remplis certaines conditions
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function rappelsAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\User');
		
		$usersWithoutEtatCivil = $repo->findWithoutEtatCivil();
		$usersWithoutTrombine = $repo->findWithoutTrombine();
		$usersWithoutGroup = $repo->findWithoutGroup();
		$usersWithoutPersonnage = $repo->findWithoutPersonnage();
		$usersWithoutSecondaryPersonnage = $repo->findWithoutSecondaryPersonnage();
		
		return $app['twig']->render('admin/rappels.twig', array(
				'usersWithoutEtatCivil' => $usersWithoutEtatCivil,
				'usersWithoutTrombine' =>  $usersWithoutTrombine,
				'usersWithoutGroup' =>  $usersWithoutGroup,
				'usersWithoutPersonnage' =>  $usersWithoutPersonnage,
				'usersWithoutSecondaryPersonnage' =>  $usersWithoutSecondaryPersonnage,
		));
	}
}