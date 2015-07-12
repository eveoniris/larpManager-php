<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class StockTagControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\StockTagController::indexAction')
			->bind("stock_tag_index")
			->method('GET');
		
		$controllers->match('/{index}','LarpManager\Controllers\StockTagController::detailAction')
			->assert('index', '\d+')
			->bind("stock_tag_detail")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\StockTagController::addAction')
			->bind("stock_tag_add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\StockTagController::updateAction')
			->assert('index', '\d+')
			->bind("stock_tag_update")
			->method('GET|POST');
			
		$controllers->match('/{index}/delete','LarpManager\Controllers\StockTagController::deleteAction')
			->assert('index', '\d+')
			->bind("stock_tag_delete")
			->method('GET|POST');
		
		return $controllers;
	}
}
