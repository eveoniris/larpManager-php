<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class InstallControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];

		$controllers->get('/','LarpManager\Controllers\InstallController::indexAction')
					->bind('install');
		
		$controllers->post('/','LarpManager\Controllers\InstallController::indexAction');

		return $controllers;
	}
}