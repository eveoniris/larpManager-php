<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\LieuControllerProvider
 * 
 * @author kevin
 *
 */
class LieuControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les lieus
	 * Routes :
	 * 	- lieu
	 * 	- lieu.add
	 *  - lieu.update
	 *  - lieu.detail
	 *  - lieu.delete
	 *  
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des lieus
		 */
		$controllers->match('/','LarpManager\Controllers\LieuController::indexAction')
			->bind("lieu")
			->method('GET');
			
		/**
		 * Imprimer la liste des lieux
		 */
		$controllers->match('/print','LarpManager\Controllers\LieuController::printAction')
			->bind("lieu.print")
			->method('GET');
				
		/**
		 * Télécharger la liste des lieux
		 */
		$controllers->match('/download','LarpManager\Controllers\LieuController::downloadAction')
			->bind("lieu.download")
			->method('GET');
		
		/**
		 * Ajoute une construction
		 */
		$controllers->match('/add','LarpManager\Controllers\LieuController::addAction')
			->bind("lieu.add")
			->method('GET|POST');

		/**
		 * Mise à jour d'un lieu
		 */
		$controllers->match('/{lieu}/update','LarpManager\Controllers\LieuController::updateAction')
			->assert('lieu', '\d+')
			->convert('lieu', 'converter.lieu:convert')
			->bind("lieu.update")
			->method('GET|POST');

		/**
		 * Detail d'un lieu
		 */
		$controllers->match('/{lieu}','LarpManager\Controllers\LieuController::detailAction')
			->assert('lieu', '\d+')
			->convert('lieu', 'converter.lieu:convert')
			->bind("lieu.detail")
			->method('GET');

		/**
		 * Supression d'un lieu
		 */
		$controllers->match('/{lieu}/delete','LarpManager\Controllers\LieuController::deleteAction')
			->assert('lieu', '\d+')
			->convert('lieu', 'converter.lieu:convert')
			->bind("lieu.delete")
			->method('GET|POST');

		/**
		 * Gestion des documents lié au lieu
		 */
		$controllers->match('/{lieu}/documents','LarpManager\Controllers\LieuController::documentAction')
			->bind("lieu.documents")
			->convert('lieu', 'converter.lieu:convert')
			->method('GET|POST');
			
		return $controllers;
	}
}