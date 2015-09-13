<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\AgeControllerProvider
 * 
 * @author kevin
 */
class AgeControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les ages
	 * Routes :
	 * 	- age
	 * 	- age.add.view
	 * 	- age.add.post
	 *  - age.update.view
	 *  - age.update.post
	 *  - age.detail
	 *   
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		// liste des ages
		$controllers->match('/','LarpManager\Controllers\AgeController::indexAction')
			->bind("age")
			->method('GET');
		
		// formulaire d'ajout d'un age
		$controllers->match('/add','LarpManager\Controllers\AgeController::addViewAction')
			->bind("age.add.view")
			->method('GET');
		
		// traitement d'une requête d'ajout d'un age
		$controllers->match('/add','LarpManager\Controllers\AgeController::addPostAction')
			->bind("age.add.post")
			->method('POST');
		
		// formulaire de modification d'un age
		$controllers->match('/{index}/update','LarpManager\Controllers\AgeController::updateViewAction')
			->assert('index', '\d+')
			->bind("age.update.view")
			->method('GET');
		
		// traitement d'une requête de modification d'un age
		$controllers->match('/{index}/update','LarpManager\Controllers\AgeController::updatePostAction')
			->assert('index', '\d+')
			->bind("age.update.post")
			->method('POST');
		
		// Affichage du détail d'un age
		$controllers->match('/{index}','LarpManager\Controllers\AgeController::detailAction')
			->assert('index', '\d+')
			->bind("age.detail")
			->method('GET');
		
		return $controllers;
	}
}