<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\TerritoireControllerProvider
 * 
 * @author kevin
 *
 */
class TerritoireControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les territoires
	 * Routes :
	 * 	- territoire
	 * 	- territoire.add
	 *  - territoire.update
	 *  - territoire.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\TerritoireController::indexAction')
			->bind("territoire")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/add','LarpManager\Controllers\TerritoireController::addAction')
			->bind("territoire.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/{index}/update','LarpManager\Controllers\TerritoireController::updateAction')
			->assert('index', '\d+')
			->bind("territoire.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/{index}','LarpManager\Controllers\TerritoireController::detailAction')
			->assert('index', '\d+')
			->bind("territoire.detail")
			->method('GET');
			
					
		return $controllers;
	}
}