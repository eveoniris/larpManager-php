<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\StockProprietaireControllerProvider
 * 
 * @author kevin
 *
 */
class StockProprietaireControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les proprietaires d'objets
	 * Routes :
	 * 	- stock_proprietaire_index
	 * 	- stock_proprietaire_add
	 *  - stock_proprieraire_update
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\StockProprietaireController::indexAction')
			->bind("stock_proprietaire_index")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\StockProprietaireController::addAction')
			->bind("stock_proprietaire_add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\StockProprietaireController::updateAction')
			->assert('index', '\d+')
			->bind("stock_proprietaire_update")
			->method('GET|POST');
		
		return $controllers;
	}
}

