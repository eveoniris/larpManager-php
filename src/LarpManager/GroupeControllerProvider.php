<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\GroupeControllerProvider
 * 
 * @author kevin
 *
 */
class GroupeControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les groupes
	 * Routes :
	 * 	- groupe
	 * 	- groupe.add
	 *  - groupe.update
	 *  - groupe.detail
	 *  - groupe.gestion
	 *  - groupe.joueur
	 *  - groupe.place
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 * @throws AccessDeniedException
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		/**
		 * Vérifie que l'utilisateur dispose du role ORGA
		 */
		$mustBeScenariste = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Liste des groupes
		 */
		$controllers->match('/admin/list','LarpManager\Controllers\GroupeController::adminListAction')
			->bind("groupe.admin.list")
			->method('GET')
			->before($mustBeScenariste);
		
		/**
		 * Retirer un participant du groupe
		 */
		$controllers->match('/admin/{groupe}/participant/{participant}/remove','LarpManager\Controllers\GroupeController::adminParticipantRemoveAction')
			->bind("groupe.admin.participant.remove")
			->method('GET|POST')
			->before($mustBeScenariste);
		
		/**
		 * Rechercher un groupe
		 */
		$controllers->match('/search','LarpManager\Controllers\GroupeController::searchAction')
			->bind("groupe.search")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Detail d'un groupe
		 */
		$controllers->match('/{index}','LarpManager\Controllers\GroupeController::detailAction')
			->assert('index', '\d+')
			->bind("groupe.detail")
			->method('GET')
			->before($mustBeScenariste);
			
		/**
		 * Ajoute un nouveau personnage dans un groupe
		 */
		$controllers->match('/{index}/personnage/add','LarpManager\Controllers\GroupeController::personnageAddAction')
			->assert('index', '\d+')
			->bind("groupe.personnage.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('GROUPE_MEMBER', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
			
		
		
		$controllers->match('/{index}/gestion','LarpManager\Controllers\GroupeController::gestionAction')
			->assert('index', '\d+')
			->bind("groupe.gestion")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('GROUPE_RESPONSABLE', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/{index}/joueur','LarpManager\Controllers\GroupeController::joueurAction')
			->assert('index', '\d+')
			->bind("groupe.joueur")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('GROUPE_MEMBER', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
					
		// Ajout d'un groupe (Scénariste uniquement)
		$controllers->match('/add','LarpManager\Controllers\GroupeController::addAction')
			->bind("groupe.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});

		// Modification des places disponibles (Admin uniquement)
		$controllers->match('/place','LarpManager\Controllers\GroupeController::placeAction')
			->bind("groupe.place")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException();
				}
			});
			
		// Mise à jour d'un groupe (scénariste uniquement)
		$controllers->match('/{index}/update','LarpManager\Controllers\GroupeController::updateAction')
			->assert('index', '\d+')
			->bind("groupe.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
		
		return $controllers;
	}
}
