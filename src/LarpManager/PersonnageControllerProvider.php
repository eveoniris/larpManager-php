<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class PersonnageControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
				
		$controllers->match('/add','LarpManager\Controllers\PersonnageController::addAction')
			->bind("personnage.add")
			->method('GET|POST');
		
		$controllers->match('/classe','LarpManager\Controllers\PersonnageController::classeAction')
			->bind("personnage.classe")
			->method('GET');
		
		$controllers->match('/competence/list','LarpManager\Controllers\PersonnageController::competenceListAction')
			->bind("personnage.competence.list")
			->method('GET');
					
		return $controllers;
	}
}
