<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class RessourceControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\RessourceController::indexAction')
			->bind("ressource")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\RessourceController::addAction')
			->bind("ressource.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\RessourceController::updateAction')
			->assert('index', '\d+')
			->bind("ressource.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\RessourceController::detailAction')
			->assert('index', '\d+')
			->bind("ressource.detail")
			->method('GET');
		
		$controllers->match('/{index}/export','LarpManager\Controllers\RessourceController::detailExportAction')
			->assert('index', '\d+')
			->bind("ressource.detail.export")
			->method('GET');
		
		$controllers->match('/export','LarpManager\Controllers\NiveauController::exportAction')
			->bind("ressource.export")
			->method('GET');
					
		return $controllers;
	}
}