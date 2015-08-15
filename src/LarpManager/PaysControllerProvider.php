<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class PaysControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\PaysController::indexAction')
			->bind("pays")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\PaysController::addAction')
			->bind("pays.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\PaysController::updateAction')
			->assert('index', '\d+')
			->bind("pays.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\PaysController::detailAction')
			->assert('index', '\d+')
			->bind("pays.detail")
			->method('GET');
		
		$controllers->match('/{index}/export','LarpManager\Controllers\PaysController::detailExportAction')
			->assert('index', '\d+')
			->bind("pays.detail.export")
			->method('GET');
			
		$controllers->match('/export','LarpManager\Controllers\PaysController::exportAction')
			->bind("pays.export")
			->method('GET');
			
		return $controllers;
	}
}