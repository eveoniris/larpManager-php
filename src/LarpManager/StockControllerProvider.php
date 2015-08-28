<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\StockControllerProvider
 * 
 * @author kevin
 *
 */
class StockControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour le stock
	 * Routes :
	 * 	- stock_homepage
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];

		$controllers->get('/','LarpManager\Controllers\StockController::indexAction')->bind("stock_homepage");
		
		return $controllers;
	}
}
