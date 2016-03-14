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
		return $app['twig']->render('admin/index.twig', array(
				'phpVersion' => $phpVersion,
				'zendVersion' => $zendVersion,
				'uploadMaxSize' => $uploadMaxSize,
				'extensions' => $extensions));
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
		return $app['twig']->render('admin/cacheEmpty.twig');
	}
}