<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\StockRangementControllerProvider
 * 
 * @author kevin
 *
 */
class StockRangementControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les rangements
	 * Routes :
	 * 	- stock_rangement_index
	 * 	- stock_rangement_add
	 *  - stock_rangement_update
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
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

