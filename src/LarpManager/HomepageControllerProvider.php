<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class HomepageControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\HomepageController::indexAction')
					->method('GET')
					->bind('homepage');
		
		$controllers->match('/inscription','LarpManager\Controllers\HomepageController::inscriptionAction')
					->method('POST')
					->bind('homepage.inscription');
		
		return $controllers;
	}
}