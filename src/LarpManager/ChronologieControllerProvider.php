<?php

namespace LarpManager;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\ChronologieControllerProvider
 * 
 * @author kevin
 *
 */
class ChronologieControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les territoires
	 * Routes :
	 * 	- chrononologie
	 * 	- chrononologie.admin.add
	 * 	- chrononologie.admin.update
	 * 	- chrononologie.admin.remove
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];

		/**
		 * Vérifie que l'utilisateur dispose du role ORGA
		 */
		$mustBeOrga = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA')) {
				throw new AccessDeniedException();
			}
		};
		
		$controllers->match('/','LarpManager\Controllers\ChronologieController::indexAction')
					->bind("chronologie")
					->method('GET');
		
		$controllers->match('/admin/add','LarpManager\Controllers\ChronologieController::addAction')
					->bind("chronologie.admin.add")
					->method('GET|POST')
					->before($mustBeOrga);
		
		$controllers->match('/admin/{index}/update','LarpManager\Controllers\ChronologieController::updateAction')
					->bind("chronologie.admin.update")
					->method('GET|POST')
					->before($mustBeOrga);
		
		$controllers->match('/admin/{index}/remove','LarpManager\Controllers\ChronologieController::removeAction')
					->bind("chronologie.admin.remove")
					->method('GET|POST')
					->before($mustBeOrga);
		
		return $controllers;
	}
}