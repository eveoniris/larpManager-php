<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class ClasseControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\ClasseController::indexAction')
			->bind("classe")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\ClasseController::addAction')
			->bind("classe.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\ClasseController::updateAction')
			->assert('index', '\d+')
			->bind("classe.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\ClasseController::detailAction')
			->assert('index', '\d+')
			->bind("classe.detail")
			->method('GET');
		
		$controllers->match('/{index}/export','LarpManager\Controllers\ClasseController::detailExportAction')
			->assert('index', '\d+')
			->bind("classe.detail.export")
			->method('GET');
		
		$controllers->match('/export','LarpManager\Controllers\ClasseController::exportAction')
			->bind("classe.export")
			->method('GET');
					
		return $controllers;
	}
}