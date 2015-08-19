<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class LangueControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\LangueController::indexAction')
			->bind("langue")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\LangueController::addAction')
			->bind("langue.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\LangueController::updateAction')
			->assert('index', '\d+')
			->bind("langue.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\LangueController::detailAction')
			->assert('index', '\d+')
			->bind("langue.detail")
			->method('GET');
					
		return $controllers;
	}
}