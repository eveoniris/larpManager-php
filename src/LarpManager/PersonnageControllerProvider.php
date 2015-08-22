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
		
		// service pour obtenir des informations sur une classe
		$controllers->match('/classe','LarpManager\Controllers\PersonnageController::classeAction')
			->bind("personnage.classe")
			->method('GET');
		
		// service pour obtenir des informations sur une competence
		$controllers->match('/competence','LarpManager\Controllers\PersonnageController::competenceAction')
			->bind("personnage.competence")
			->method('GET');
		
		$controllers->match('/competence/list','LarpManager\Controllers\PersonnageController::competenceListAction')
			->bind("personnage.competence.list")
			->method('GET');
					
		return $controllers;
	}
}
