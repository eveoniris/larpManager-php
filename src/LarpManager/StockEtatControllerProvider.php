<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\StockEtatControllerProvider
 * 
 * @author kevin
 *
 */
class StockEtatControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les états du stock
	 * Routes :
	 * 	- stock_etat_index
	 * 	- stock_etat_add
	 *  - stock_etat_update
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\StockEtatController::indexAction')
			->bind("stock_etat_index")
			->method('GET');
				
		$controllers->match('/add','LarpManager\Controllers\StockEtatController::addAction')
			->bind("stock_etat_add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\StockEtatController::updateAction')
			->assert('index', '\d+')
			->bind("stock_etat_update")
			->method('GET|POST');
			
		return $controllers;
	}
}
		
		