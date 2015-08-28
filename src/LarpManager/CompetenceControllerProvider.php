<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\CompetenceControllerProvider
 * 
 * @author kevin
 *
 */
class CompetenceControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les competences
	 * Routes :
	 * 	- competence
	 * 	- competence.add
	 *  - competence.update
	 *  - competence.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\CompetenceController::indexAction')
			->bind("competence")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\CompetenceController::addAction')
			->bind("competence.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\CompetenceController::updateAction')
			->assert('index', '\d+')
			->bind("competence.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\CompetenceController::detailAction')
			->assert('index', '\d+')
			->bind("competence.detail")
			->method('GET');
			
		return $controllers;
	}
}