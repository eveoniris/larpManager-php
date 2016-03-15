<?php
namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\AdminControllerProvider
 *
 * @author kevin
 */
class AdminControllerProvider implements ControllerProviderInterface
{

	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Page d'accueil de l'interface d'administration
		 */
		$controllers->match('/','LarpManager\Controllers\AdminController::indexAction')
			->bind("admin")
			->method('GET');
		
		/**
		 * Page d'accueil de l'interface d'administration
		 */
		$controllers->match('/rappels','LarpManager\Controllers\AdminController::rappelsAction')
			->bind("admin.rappels")
			->method('GET');			
		
		/**
		 * Voir les logs
		 */
		$controllers->match('/log','LarpManager\Controllers\AdminController::logAction')
			->bind("admin.log")
			->method('GET');			
		
		/**
		 * Exporter la base de données
		 */
		$controllers->match('/database/export','LarpManager\Controllers\AdminController::databaseExportAction')
			->bind("admin.database.export")
			->method('GET');

		/**
		 * Mettre à jour la base de données
		 */
		$controllers->match('/database/update','LarpManager\Controllers\AdminController::databaseUpdateAction')
			->bind("admin.database.update")
			->method('GET');			
		
		/**
		 * Vider le cache
		 */
		$controllers->match('/cache/empty','LarpManager\Controllers\AdminController::cacheEmptyAction')
			->bind("admin.cache.empty")
			->method('GET');
		
		/**
		 * Vider les logs
		 */
		$controllers->match('/cache/log','LarpManager\Controllers\AdminController::logEmptyAction')
			->bind("admin.log.empty")
			->method('GET');
			
		return $controllers;
	}
}