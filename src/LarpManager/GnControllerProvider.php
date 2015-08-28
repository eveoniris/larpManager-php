<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\GnControllerProvider
 * 
 * @author kevin
 */
class GnControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les gns
	 * Routes :
	 * 	- gn
	 * 	- gn.add
	 *  - gn.update
	 *  - gn.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\GnController::indexAction')
			->bind("gn")
			->method('GET');

		$controllers->match('/add','LarpManager\Controllers\GnController::addAction')
			->bind("gn.add")
			->method('GET|POST');

		$controllers->match('/{index}/update','LarpManager\Controllers\GnController::updateAction')
			->assert('index', '\d+')
			->bind("gn.update")
			->method('GET|POST');

		$controllers->match('/{index}','LarpManager\Controllers\GnController::detailAction')
			->assert('index', '\d+')
			->bind("gn.detail")
			->method('GET');

		return $controllers;
	}
}