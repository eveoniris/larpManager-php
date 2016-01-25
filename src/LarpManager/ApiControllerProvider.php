<?php

namespace LarpManager;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\ApiControllerProvider
 * 
 * @author kevin
 *
 */
class ApiControllerProvider implements ControllerProviderInterface
{
	/**
	 * Routes :
	 * 	- api.territoire.event.list
	 * 	- api.territoire.event.add
	 * 	- api.territoire.event.update
	 * 	- api.territoire.event.remove
	 * 	- api.territoire.event.detail
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
		
		// Récupére tous les territoires
		$controllers->match('/territoire','LarpManager\Controllers\TerritoireController::apiListAction')
			->bind("api.territoire.list")
			->method('GET')
			->before($mustBeOrga);
		
		// Ajoute un territoire
		$controllers->match('/territoire','LarpManager\Controllers\TerritoireController::apiAddAction')
			->bind("api.territoire.add")
			->method('POST')
			->before($mustBeOrga);
			
		// Modifie un territoire
		$controllers->match('/territoire/{territoire}','LarpManager\Controllers\TerritoireController::apiUpdateAction')
			->bind("api.territoire.update")
			->assert('territoire', '\d+')
			->convert('territoire', 'converter.territoire:convert')
			->method('POST')
			->before($mustBeOrga);			
		
		// Modifie un événement
		$controllers->match('/chronologies/{event}','LarpManager\Controllers\ChronologieController::apiUpdateAction')
			->bind("api.event.update")
			->assert('event', '\d+')
			->convert('event', 'converter.event:convert')
			->method('POST')
			->before($mustBeOrga);
			
		// Supprime un événement
		$controllers->match('/chronologies/{event}','LarpManager\Controllers\ChronologieController::apiDeleteAction')
			->bind("api.event.delete")
			->assert('event', '\d+')
			->convert('event', 'converter.event:convert')
			->method('DELETE')
			->before($mustBeOrga);			
			
		// Ajoute un événement
		$controllers->match('/chronologies','LarpManager\Controllers\ChronologieController::apiAddAction')
			->bind("api.event.add")
			->method('POST')
			->before($mustBeOrga);			
			
			
			
			
			
		// Récupére tous les événements d'un territoire
		$controllers->match('/territoire/{territoire}/chronologies','LarpManager\Controllers\TerritoireController::apiEventListAction')
			->bind("api.territoire.event.list")
			->assert('territoire', '\d+')
			->convert('territoire', 'converter.territoire:convert')
			->method('GET')
			->before($mustBeOrga);
			

			
			
			
			
		// Ajoute un nouvel événement
			$controllers->match('/territoire/{territoire}/event','LarpManager\Controllers\TerritoireController::eventAddAction')
			->bind("api.territoire.event.add")
			->assert('territoire', '\d+')
			->convert('territoire', 'converter.territoire:convert')
			->method('POST')
			->before($mustBeOrga);
		
		// Récupére un événement en particulier
		$controllers->match('/territoire/{territoire}/event/{event}','LarpManager\Controllers\TerritoireController::eventDetailAction')
			->bind("api.territoire.event.detail")
			->assert('territoire', '\d+')
			->assert('event', '\d+')
			->convert('territoire', 'converter.territoire:convert')
			->convert('event', 'converter.event:convert')
			->method('GET')
			->before($mustBeOrga);
		
		// Supprime un événement
		$controllers->match('/territoire/{territoire}/event/{event}','LarpManager\Controllers\TerritoireController::eventDeleteAction')
			->bind("api.territoire.event.delete")
			->assert('territoire', '\d+')
			->assert('event', '\d+')
			->convert('territoire', 'converter.territoire:convert')
			->convert('event', 'converter.event:convert')
			->method('DELETE')
			->before($mustBeOrga);
		

		
		return $controllers;
		
		
	}
}