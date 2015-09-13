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
	 * 	- joueur : liste des joueurs
	 * 	- joueur.add : Ajoute un joueur (saisie des informations joueurs par un utilisateur)
	 *  - joueur.update : Mise à jour des informations joueur par un utilisateur
	 *  - joueur.detail : Détail des informations joueur
	 *  - joueur.xp : modification des XP pour un joueur
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 * @throws AccessDeniedException
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		/**
		 * Liste des joueurs
		 * Accessible uniquement aux admin
		 */
		$controllers->match('/','LarpManager\Controllers\JoueurController::indexAction')
			->bind("joueur")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException();
				}
			});

		/**
		 * Saisie des informations joueurs
		 * Accessible uniquement aux utilisateurs ne l'ayant pas déjà fait
		 */
		$controllers->match('/add','LarpManager\Controllers\JoueurController::addAction')
			->bind("joueur.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('JOUEUR_NOT_REGISTERED')) {
					throw new AccessDeniedException();
				}
			});

		/**
		 * Mise à jour des informations joueur
		 * Accessible uniquement aux utilisateurs ayant déjà saisie leurs informations
		 */
		$controllers->match('/{index}/update','LarpManager\Controllers\JoueurController::updateAction')
			->assert('index', '\d+')
			->bind("joueur.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('JOUEUR_OWNER', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
		
		/**
		 * Détail des informations joueurs
		 * Accessible uniquement aux utilisateurs possédant ces informations
		 */
		$controllers->match('/{index}/detail','LarpManager\Controllers\JoueurController::detailAction')
			->assert('index', '\d+')
			->bind("joueur.detail")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('JOUEUR_OWNER', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
		
		/**
		 * Saisie de bonus d'xp
		 * Accessible uniquement aux admin
		 */
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