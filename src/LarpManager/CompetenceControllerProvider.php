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
		
		$controllers->match('/{index}/export','LarpManager\Controllers\CompetenceController::detailExportAction')
			->assert('index', '\d+')
			->bind("competence.detail.export")
			->method('GET');
		
		$controllers->match('/export','LarpManager\Controllers\CompetenceController::exportAction')
			->bind("competence.export")
			->method('GET');
		
		$controllers->match('/{index}/niveau/add','LarpManager\Controllers\CompetenceController::niveauAddAction')
			->assert('index', '\d+')
			->bind("competence.niveau.add")
			->method('GET|POST');
		
		$controllers->match('/niveau/{index}/update','LarpManager\Controllers\CompetenceController::niveauUpdateAction')
			->assert('index', '\d+')
			->bind("competence.niveau.update")
			->method('GET|POST');
			
		return $controllers;
	}
}