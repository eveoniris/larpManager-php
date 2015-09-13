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
		 * Liste des groupes secondaires
		 */		
		$controllers->match('/','LarpManager\Controllers\GroupeSecondaireController::indexAction')
			->bind("groupeSecondaire")
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
		$controllers->match('/add','LarpManager\Controllers\GroupeSecondaireController::addAction')
			->bind("groupeSecondaire.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Modification d'un groupe secondaire
		 */
		$controllers->match('/{index}/update','LarpManager\Controllers\GroupeSecondaireController::updateAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
			
		return $controllers;
	}
}