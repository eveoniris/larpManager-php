<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\JoueurControllerProvider
 *  
 * @author kevin
 */
class JoueurControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les joueurs
	 * Routes :
	 * 	- joueur
	 * 	- joueur.add
	 *  - joueur.update
	 *  - joueur.detail
	 *  - joueur.xp
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 * @throws AccessDeniedException
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\JoueurController::indexAction')
			->bind("joueur")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException();
				}
			});

		$controllers->match('/add','LarpManager\Controllers\JoueurController::addAction')
			->bind("joueur.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException();
				}
			});

		$controllers->match('/{index}/update','LarpManager\Controllers\JoueurController::updateAction')
			->assert('index', '\d+')
			->bind("joueur.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('JOUEUR_OWNER', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/{index}/detail','LarpManager\Controllers\JoueurController::detailAction')
			->assert('index', '\d+')
			->bind("joueur.detail")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('JOUEUR_OWNER', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/xp','LarpManager\Controllers\JoueurController::xpAction')
			->bind("joueur.xp")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException();
				}
			});

		return $controllers;
	}
}