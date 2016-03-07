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
	/**
	 * Page d'accueil de l'interface d'administration
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		return $app['twig']->render('admin/index.twig');
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