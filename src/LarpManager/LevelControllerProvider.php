<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
/**
 * LarpManager\LevelControllerProvider
 * 
 * @author kevin
 *
 */
class LevelControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les levels
	 * Routes :
	 * 	- level
	 * 	- level.add
	 *  - level.update
	 *  - level.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\LevelController::indexAction')
			->bind("level")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\LevelController::addAction')
			->bind("level.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\LevelController::updateAction')
			->assert('index', '\d+')
			->bind("level.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\LevelController::detailAction')
			->assert('index', '\d+')
			->bind("level.detail")
			->method('GET');
					
		return $controllers;
	}
}