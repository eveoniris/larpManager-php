<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class AppelationControllerProvider implements ControllerProviderInterface
{
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