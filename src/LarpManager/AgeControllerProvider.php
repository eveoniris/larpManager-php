<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class AgeControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\AgeController::indexAction')
			->bind("age")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\AgeController::addAction')
			->bind("age.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\AgeController::updateAction')
			->assert('index', '\d+')
			->bind("age.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\AgeController::detailAction')
			->assert('index', '\d+')
			->bind("age.detail")
			->method('GET');
		
		return $controllers;
	}
}