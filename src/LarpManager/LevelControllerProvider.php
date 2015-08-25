<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class LevelControllerProvider implements ControllerProviderInterface
{
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