<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\StockTagControllerProvider
 * 
 * @author kevin
 *
 */
class StockTagControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les tags 
	 * Routes :
	 * 	- stock_tag_index
	 * 	- stock_tag_add
	 *  - stock_tag_update
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\StockTagController::indexAction')
			->bind("stock_tag_index")
			->method('GET');
				
		$controllers->match('/add','LarpManager\Controllers\StockTagController::addAction')
			->bind("stock_tag_add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\StockTagController::updateAction')
			->assert('index', '\d+')
			->bind("stock_tag_update")
			->method('GET|POST');
					
		return $controllers;
	}
}
