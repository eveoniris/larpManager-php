<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\AppelationControllerProvider
 * 
 * @author kevin
 */
class AnnonceControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les annonces
	 * 
	 * Routes :
	 * 	- annonce
	 * 	- annonce.add
	 *  - annonce.update
	 *  - annonce.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/list','LarpManager\Controllers\AnnonceController::listAction')
			->bind("annonce.list")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\AnnonceController::addAction')
			->bind("annonce.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\AnnonceController::updateAction')
			->assert('index', '\d+')
			->bind("annonce.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\AnnonceController::detailAction')
			->assert('index', '\d+')
			->bind("annonce.detail")
			->method('GET');
					
		return $controllers;
	}
}