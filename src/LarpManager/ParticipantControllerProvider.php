<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\ParticipantControllerProvider
 *  
 * @author kevin
 */
class ParticipantControllerProvider implements ControllerProviderInterface
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
		$controllers->match('/admin','LarpManager\Controllers\ParticipantController::indexAction')
			->bind("admin.joueur")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException();
				}
			});
			

		/**
		 * Détail des informations joueurs (pour les orgas)
		 * Accessible uniquement aux utilisateurs possédant ces informations
		 */
		$controllers->match('/admin/{index}','LarpManager\Controllers\ParticipantController::adminDetailAction')
			->assert('index', '\d+')
			->bind("admin.joueur.detail")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Rechercher un joueur
		 */
		$controllers->match('/admin/search','LarpManager\Controllers\ParticipantController::adminSearchAction')
			->bind("admin.joueur.search")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if ( !$app['security.authorization_checker']->isGranted('ROLE_ORGA') ) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Mise à jour des informations joueur
		 */
		$controllers->match('/admin/{index}/update','LarpManager\Controllers\ParticipantController::adminUpdateAction')
			->assert('index', '\d+')
			->bind("admin.joueur.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA') ) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Mise à jour des XP alloué à un joueur
		 */
		$controllers->match('/admin/{index}/xp','LarpManager\Controllers\ParticipantController::adminXpAction')
			->assert('index', '\d+')
			->bind("admin.joueur.xp")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA') ) {
					throw new AccessDeniedException();
				}
			});

		/**
		 * Saisie des informations joueurs
		 * Accessible uniquement aux utilisateurs ne l'ayant pas déjà fait
		 */
		$controllers->match('/add','LarpManager\Controllers\ParticipantController::addAction')
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
		$controllers->match('/{index}/update','LarpManager\Controllers\ParticipantController::updateAction')
			->assert('index', '\d+')
			->bind("joueur.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('JOUEUR_OWNER', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
		
		/**
		 * Détail des informations joueurs (pour le joueur)
		 * Accessible uniquement aux utilisateurs possédant ces informations
		 */
		$controllers->match('/{index}/detail','LarpManager\Controllers\ParticipantController::detailAction')
			->assert('index', '\d+')
			->bind("joueur.detail")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('JOUEUR_OWNER', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});

		return $controllers;
	}
}