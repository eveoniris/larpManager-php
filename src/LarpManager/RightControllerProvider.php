<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\RightControllerProvider
 * 
 * @author kevin
 *
 */
class RightControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les droits
	 * Routes :
	 * 	- right.admin.list
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des ressources (admin uniquement)
		 */
		$controllers->match('/','LarpManager\Controllers\RightController::listAction')
			->bind("right.admin.list")
			->method('GET');
		
		return $controllers;
	}
}