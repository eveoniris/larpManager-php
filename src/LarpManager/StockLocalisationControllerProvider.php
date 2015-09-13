<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\StockLocalisationControllerProvider
 * 
 * @author kevin
 *
 */
class StockLocalisationControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les localisations du stock
	 * Routes :
	 * 	- stock_localisation_index
	 * 	- stock_localisation_add
	 *  - stock_localisation_update
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
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
