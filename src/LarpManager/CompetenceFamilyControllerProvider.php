<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\CompetenceFamilyControllerProvider
 * @author kevin
 *
 */
class CompetenceFamilyControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les famille de competence
	 * Routes :
	 * 	- competence.family
	 * 	- competence.family.add
	 *  - competence.family.update
	 *  - competence.family.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\CompetenceFamilyController::indexAction')
			->bind("competence.family")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\CompetenceFamilyController::addAction')
			->bind("competence.family.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\CompetenceFamilyController::updateAction')
			->assert('index', '\d+')
			->bind("competence.family.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\CompetenceFamilyController::detailAction')
			->assert('index', '\d+')
			->bind("competence.family.detail")
			->method('GET');
		

		return $controllers;
	}
}