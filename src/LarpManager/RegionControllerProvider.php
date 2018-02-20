<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class RegionControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\RegionController::indexAction')
			->bind("region")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\RegionController::addAction')
			->bind("region.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\RegionController::updateAction')
			->assert('index', '\d+')
			->bind("region.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\RegionController::detailAction')
			->assert('index', '\d+')
			->bind("region.detail")
			->method('GET');
		
		$controllers->match('/{index}/export','LarpManager\Controllers\RegionController::detailExportAction')
			->assert('index', '\d+')
			->bind("region.detail.export")
			->method('GET');
			
		$controllers->match('/export','LarpManager\Controllers\PaysController::exportAction')
			->bind("region.export")
			->method('GET');
			
		return $controllers;
	}
}