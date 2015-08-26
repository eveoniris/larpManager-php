<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class StockRangementControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\StockRangementController::indexAction')
			->bind("stock_rangement_index")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\StockRangementController::addAction')
			->bind("stock_rangement_add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\StockRangementController::updateAction')
			->assert('index', '\d+')
			->bind("stock_rangement_update")
			->method('GET|POST');
		
		return $controllers;
	}
}

