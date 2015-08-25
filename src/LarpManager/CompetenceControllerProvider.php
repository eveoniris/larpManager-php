<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class CompetenceControllerProvider implements ControllerProviderInterface
{
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