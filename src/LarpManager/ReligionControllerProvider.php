<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\ReligionControllerProvider
 * 
 * @author kevin
 *
 */
class ReligionControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les religions
	 * Routes :
	 * 	- religion
	 * 	- religion.add
	 *  - religion.update
	 *  - religion.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\ReligionController::indexAction')
			->bind("religion")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\ReligionController::addAction')
			->bind("religion.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\ReligionController::updateAction')
			->assert('index', '\d+')
			->bind("religion.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\ReligionController::detailAction')
			->assert('index', '\d+')
			->bind("religion.detail")
			->method('GET');
					
		return $controllers;
	}
}