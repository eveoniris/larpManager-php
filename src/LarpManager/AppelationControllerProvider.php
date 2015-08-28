<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\AppelationControllerProvider
 * 
 * @author kevin
 */
class AppelationControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les appelations
	 * Routes :
	 * 	- appelation
	 * 	- appelation.add
	 *  - appelation.update
	 *  - appelation.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\AppelationController::indexAction')
			->bind("appelation")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\AppelationController::addAction')
			->bind("appelation.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\AppelationController::updateAction')
			->assert('index', '\d+')
			->bind("appelation.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\AppelationController::detailAction')
			->assert('index', '\d+')
			->bind("appelation.detail")
			->method('GET');
					
		return $controllers;
	}
}