<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\GnControllerProvider
 * 
 * @author kevin
 */
class GnControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les gns
	 * Routes :
	 * 	- gn
	 * 	- gn.add
	 *  - gn.update
	 *  - gn.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		/**
		 * Voir la liste des gns
		 */
		$controllers->match('/','LarpManager\Controllers\GnController::indexAction')
			->bind("gn")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException();
				}
			});

		/**
		 * Ajouter un gn
		 */
		$controllers->match('/add','LarpManager\Controllers\GnController::addAction')
			->bind("gn.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException();
				}
			});

		/**
		 * Modifier un gn
		 */
		$controllers->match('/{index}/update','LarpManager\Controllers\GnController::updateAction')
			->assert('index', '\d+')
			->bind("gn.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException();
				}
			});

		/**
		 * voir le détail d'un gn
		 */
		$controllers->match('/{index}','LarpManager\Controllers\GnController::detailAction')
			->assert('index', '\d+')
			->bind("gn.detail")
			->method('GET');

		return $controllers;
	}
}