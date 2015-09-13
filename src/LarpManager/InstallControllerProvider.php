<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\InstallControllerProvider
 * 
 * @author kevin
 *
 */
class InstallControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour le processus d'installation
	 * Routes :
	 * 	- install_index
	 * 	- install_create_user
	 *  - install_done
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\InstallController::indexAction')
			->bind("install_index")
			->method('GET|POST');
		
		$controllers->match('/user/create','LarpManager\Controllers\InstallController::createUserAction')
			->bind("install_create_user")
			->method('GET|POST');
		
		$controllers->match('/done','LarpManager\Controllers\InstallController::doneAction')
			->bind("install_done")
			->method('GET');
		
		return $controllers;
	}
}