<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class GroupeControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\GroupeController::indexAction')
			->bind("groupe")
			->method('GET');
		
		$controllers->match('/{index}','LarpManager\Controllers\GroupeController::detailAction')
			->assert('index', '\d+')
			->bind("groupe.detail")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\GroupeController::addAction')
			->bind("groupe.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\GroupeController::updateAction')
			->assert('index', '\d+')
			->bind("groupe.update")
			->method('GET|POST');
		
		$controllers->match('/{index}/export','LarpManager\Controllers\GroupeController::detailExportAction')
			->assert('index', '\d+')
			->bind("groupe.detail.export")
			->method('GET');
		
		$controllers->match('/export','LarpManager\Controllers\GroupeController::exportAction')
			->bind("groupe.export")
			->method('GET|POST');

		return $controllers;
	}
}
