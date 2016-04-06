<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\ConstructionControllerProvider
 * 
 * @author kevin
 *
 */
class ConstructionControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les constructions
	 * Routes :
	 * 	- construction
	 * 	- construction.add
	 *  - construction.update
	 *  - constructino.detail
	 *  - constructino.delete
	 *  
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des construction
		 */
		$controllers->match('/','LarpManager\Controllers\ConstructionController::indexAction')
			->bind("construction")
			->method('GET');
		
		/**
		 * Ajoute une construction
		 */
		$controllers->match('/add','LarpManager\Controllers\ConstructionController::addAction')
			->bind("construction.add")
			->method('GET|POST');

		/**
		 * Mise à jour d'une construciton
		 */
		$controllers->match('/{construction}/update','LarpManager\Controllers\ConstructionController::updateAction')
			->assert('construction', '\d+')
			->convert('construction', 'converter.construction:convert')
			->bind("construction.update")
			->method('GET|POST');

		/**
		 * Detail d'une construction
		 */
		$controllers->match('/{construction}','LarpManager\Controllers\ConstructionController::detailAction')
			->assert('construction', '\d+')
			->convert('construction', 'converter.construction:convert')
			->bind("construction.detail")
			->method('GET');

		/**
		 * Supression d'une construction
		 */
		$controllers->match('/{construction}/delete','LarpManager\Controllers\ConstructionController::deleteAction')
			->assert('construction', '\d+')
			->convert('construction', 'converter.construction:convert')
			->bind("construction.delete")
			->method('GET|POST');

		return $controllers;
	}
}