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
					
		return $controllers;
	}
}
