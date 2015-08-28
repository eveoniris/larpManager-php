<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\HomepageControllerProvider
 * 
 * @author kevin
 *
 */
class HomepageControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise des routes non lié à un objet en particulier 
	 * Routes :
	 * 	- homepage
	 * 	- world
	 *  - inscription
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\HomepageController::indexAction')
					->method('GET')
					->bind('homepage');
		
		$controllers->match('/world','LarpManager\Controllers\HomepageController::worldAction')
					->method('GET')
					->bind('world');
		
		$controllers->match('/inscription','LarpManager\Controllers\HomepageController::inscriptionAction')
					->method('POST')
					->bind('homepage.inscription');
		
		return $controllers;
	}
}