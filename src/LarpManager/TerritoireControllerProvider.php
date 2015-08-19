<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class TerritoireControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\TerritoireController::indexAction')
			->bind("territoire")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\TerritoireController::addAction')
			->bind("territoire.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\TerritoireController::updateAction')
			->assert('index', '\d+')
			->bind("territoire.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\TerritoireController::detailAction')
			->assert('index', '\d+')
			->bind("territoire.detail")
			->method('GET');
					
		return $controllers;
	}
}