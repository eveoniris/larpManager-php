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
class GroupeSecondaireControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des groupes secondaires (pour les orgas)
		 */		
		$controllers->match('/admin/list','LarpManager\Controllers\GroupeSecondaireController::adminListAction')
			->bind("groupeSecondaire.admin.list")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA')) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Detail d'un groupe secondaire (pour les orgas)
		 */
		$controllers->match('/admin/{index}','LarpManager\Controllers\GroupeSecondaireController::adminDetailAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.admin.detail")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA')) {
					throw new AccessDeniedException();
				}
			});
		
		/**
		 * Liste des groupes secondaires (pour les joueurs)
		 */
		$controllers->match('/list','LarpManager\Controllers\GroupeSecondaireController::listAction')
			->bind("groupeSecondaire.list")
			->method('GET');

		/**
		 * Detail d'un groupe secondaire
		 */
		$controllers->match('/{index}','LarpManager\Controllers\GroupeSecondaireController::detailAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.detail")
			->method('GET');
					
		/**
		 * Postuler à un groupe secondaire
		 */
		$controllers->match('/{index}/postuler','LarpManager\Controllers\GroupeSecondaireController::postulerAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.postuler")
			->method('GET|POST');

		/**
		 * Detail d'un groupe secondaire à destication du chef de groupe
		 */
		$controllers->match('/{index}/gestion','LarpManager\Controllers\GroupeSecondaireController::gestionAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.gestion")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('GROUPE_SECONDAIRE_RESPONSABLE', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Detail d'un groupe secondaire à destication des membres de ce groupe
		 */
		$controllers->match('/{index}/joueur','LarpManager\Controllers\GroupeSecondaireController::joueurAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.joueur")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('GROUPE_SECONDAIRE_MEMBER', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
						
		/**
		 * Ajouter un groupe secondaire
		 */
		$controllers->match('/admin/add','LarpManager\Controllers\GroupeSecondaireController::adminAddAction')
			->bind("groupeSecondaire.admin.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Modification d'un groupe secondaire
		 */
		$controllers->match('/admin/{index}/update','LarpManager\Controllers\GroupeSecondaireController::adminUpdateAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.admin.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
			
		return $controllers;
	}
}