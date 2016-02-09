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
		 * Ajouter un groupe secondaire (pour les orgas)
		 */
		$controllers->match('/admin/add','LarpManager\Controllers\GroupeSecondaireController::adminAddAction')
			->bind("groupeSecondaire.admin.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA')) {
					throw new AccessDeniedException();
				}
			});
						
		/**
		 * Modification d'un groupe secondaire (pour les orgas)
		 */
		$controllers->match('/admin/{index}/update','LarpManager\Controllers\GroupeSecondaireController::adminUpdateAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.admin.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA')) {
					throw new AccessDeniedException();
				}
			});

		/**
		 * Accepter une candidature (pour les orgas)
		 */
		$controllers->match('/{index}/reponse','LarpManager\Controllers\GroupeSecondaireController::adminReponseAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.admin.reponse")
			->method('GET|POST')
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
		 * Accepter une candidature (chef de groupe)
		 */
		$controllers->match('/{index}/reponse','LarpManager\Controllers\GroupeSecondaireController::reponseAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.reponse")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('GROUPE_SECONDAIRE_RESPONSABLE', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
				
			
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
		 * Rejeter la demande d'un postulant
		 */
		$controllers->match('/{groupe}/gestion/postulant/{postulant}/reject','LarpManager\Controllers\GroupeSecondaireController::gestionRejectAction')
			->assert('groupe', '\d+')
			->assert('postulant', '\d+')
			->bind("groupeSecondaire.gestion.reject")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('GROUPE_SECONDAIRE_RESPONSABLE', $request->get('groupe'))) {
					throw new AccessDeniedException();
				}
			});
		
		/**
		 * Accepter la demande d'un postulant
		 */
		$controllers->match('/{groupe}/gestion/postulant/{postulant}/accept','LarpManager\Controllers\GroupeSecondaireController::gestionAcceptAction')
			->assert('groupe', '\d+')
			->assert('postulant', '\d+')
			->bind("groupeSecondaire.gestion.accept")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('GROUPE_SECONDAIRE_RESPONSABLE', $request->get('groupe'))) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Mettre en attente la demande d'un postulant
		 */
		$controllers->match('/{groupe}/gestion/postulant/{postulant}/wait','LarpManager\Controllers\GroupeSecondaireController::gestionWaitAction')
			->assert('groupe', '\d+')
			->assert('postulant', '\d+')
			->bind("groupeSecondaire.gestion.wait")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('GROUPE_SECONDAIRE_RESPONSABLE', $request->get('groupe'))) {
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

			
		return $controllers;
	}
}