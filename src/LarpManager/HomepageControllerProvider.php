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

		$controllers->get('/','LarpManager\Controllers\HomepageController::indexAction')
					->bind('homepage');
		
		return $controllers;
	}
}