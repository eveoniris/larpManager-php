<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class NiveauControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\NiveauController::indexAction')
			->bind("niveau")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\NiveauController::addAction')
			->bind("niveau.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\NiveauController::updateAction')
			->assert('index', '\d+')
			->bind("niveau.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\NiveauController::detailAction')
			->assert('index', '\d+')
			->bind("niveau.detail")
			->method('GET');
		
		$controllers->match('/{index}/export','LarpManager\Controllers\NiveauController::detailExportAction')
			->assert('index', '\d+')
			->bind("niveau.detail.export")
			->method('GET');
		
		$controllers->match('/export','LarpManager\Controllers\NiveauController::exportAction')
			->bind("niveau.export")
			->method('GET');
					
		return $controllers;
	}
}