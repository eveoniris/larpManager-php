<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class StockObjetControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\StockObjetController::indexAction')
			->bind("stock_objet_index")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\StockObjetController::detailAction')
			->assert('index', '\d+')
			->bind("stock_objet_detail")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\StockObjetController::addAction')
			->bind("stock_objet_add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\StockObjetController::updateAction')
			->assert('index', '\d+')
			->bind("stock_objet_update")
			->method('GET|POST');
			
		$controllers->match('/{index}/delete','LarpManager\Controllers\StockObjetController::deleteAction')
			->assert('index', '\d+')
			->bind("stock_objet_delete")
			->method('GET|POST');
		
		return $controllers;
	}
}

