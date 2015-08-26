<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class StockLocalisationControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\StockLocalisationController::indexAction')
			->bind("stock_localisation_index")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\StockLocalisationController::addAction')
			->bind("stock_localisation_add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\StockLocalisationController::updateAction')
			->assert('index', '\d+')
			->bind("stock_localisation_update")
			->method('GET|POST');
		
		return $controllers;
	}
}
