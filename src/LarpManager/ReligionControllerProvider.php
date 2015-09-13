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
	 *  - religion.level
	 *  - religion.level.add
	 *  - religion.level.update
	 *  - religion.level.detail
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
		
		$controllers->match('/level','LarpManager\Controllers\ReligionController::levelIndexAction')
			->bind("religion.level")
			->method('GET');
		
		$controllers->match('/level/add','LarpManager\Controllers\ReligionController::levelAddAction')
			->bind("religion.level.add")
			->method('GET|POST');

		$controllers->match('/level/{index}/update','LarpManager\Controllers\ReligionController::levelUpdateAction')
			->assert('index', '\d+')
			->bind("religion.level.update")
			->method('GET|POST');
		
		$controllers->match('/level/{index}','LarpManager\Controllers\ReligionController::levelDetailAction')
			->assert('index', '\d+')
			->bind("religion.level.detail")
			->method('GET');
			
		return $controllers;
	}
}