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
		 * Vérifie que l'utilisateur dispose du role REGLE
		 */
		$mustBeOrga = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur est le responsable du groupe
		 */
		$mustBeResponsable = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('GROUPE_SECONDAIRE_RESPONSABLE', $request->get('groupe'))) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur est membre du groupe
		 */
		$mustBeMembre = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('GROUPE_SECONDAIRE_MEMBER', $request->get('groupe'))) {
				throw new AccessDeniedException();
			}
		};
				
		/**
		 * Liste des groupes secondaires (pour les orgas)
		 */		
		$controllers->match('/admin/list','LarpManager\Controllers\GroupeSecondaireController::adminListAction')
			->bind("groupeSecondaire.admin.list")
			->method('GET')
			->before($mustBeOrga);
			
		/**
		 * Detail d'un groupe secondaire (pour les orgas)
		 */
		$controllers->match('/admin/{groupe}','LarpManager\Controllers\GroupeSecondaireController::adminDetailAction')
			->assert('groupe', '\d+')
			->bind("groupeSecondaire.admin.detail")
			->method('GET')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->before($mustBeOrga);
		

		/**
		 * Ajouter un groupe secondaire (pour les orgas)
		 */
		$controllers->match('/admin/add','LarpManager\Controllers\GroupeSecondaireController::adminAddAction')
			->bind("groupeSecondaire.admin.add")
			->method('GET|POST')
			->before($mustBeOrga);
						
		/**
		 * Modification d'un groupe secondaire (pour les orgas)
		 */
		$controllers->match('/admin/{groupe}/update','LarpManager\Controllers\GroupeSecondaireController::adminUpdateAction')
			->assert('groupe', '\d+')
			->bind("groupeSecondaire.admin.update")
			->method('GET|POST')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->before($mustBeOrga);

		/**
		 * Accepter une candidature (pour les orgas)
		 */
		$controllers->match('/{groupe}/reponse','LarpManager\Controllers\GroupeSecondaireController::adminReponseAction')
			->assert('groupe', '\d+')
			->bind("groupeSecondaire.admin.reponse")
			->method('GET|POST')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->before($mustBeOrga);
							
		/**
		 * Liste des groupes secondaires (pour les joueurs)
		 */
		$controllers->match('/list','LarpManager\Controllers\GroupeSecondaireController::listAction')
			->bind("groupeSecondaire.list")
			->method('GET');

		/**
		 * Detail d'un groupe secondaire
		 */
		$controllers->match('/{groupe}','LarpManager\Controllers\GroupeSecondaireController::detailAction')
			->assert('groupe', '\d+')
			->bind("groupeSecondaire.detail")
			->convert('groupe', 'converter.secondaryGroup:convert')
			->method('GET');
					
		/**
		 * Postuler à un groupe secondaire
		 */
		$controllers->match('/{groupe}/postuler','LarpManager\Controllers\GroupeSecondaireController::postulerAction')
			->assert('groupe', '\d+')
			->bind("groupeSecondaire.postuler")
			->convert('groupe', 'converter.secondaryGroup:convert')
			->method('GET|POST');
			
		/**
		 * Accepter une candidature (chef de groupe)
		 */
		$controllers->match('/{groupe}/reponse','LarpManager\Controllers\GroupeSecondaireController::reponseAction')
			->assert('groupe', '\d+')
			->bind("groupeSecondaire.reponse")
			->method('GET|POST')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->before($mustBeResponsable);
				
			
		/**
		 * Detail d'un groupe secondaire à destication du chef de groupe
		 */
		$controllers->match('/{groupe}/gestion','LarpManager\Controllers\GroupeSecondaireController::gestionAction')
			->assert('groupe', '\d+')
			->bind("groupeSecondaire.gestion")
			->method('GET')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->before($mustBeResponsable);

		/**
		 * Rejeter la demande d'un postulant
		 */
		$controllers->match('/{groupe}/gestion/postulant/{postulant}/reject','LarpManager\Controllers\GroupeSecondaireController::gestionRejectAction')
			->assert('groupe', '\d+')
			->assert('postulant', '\d+')
			->bind("groupeSecondaire.gestion.reject")
			->method('GET|POST')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->convert('postulant', 'converter.postulant:convert')
			->before($mustBeResponsable);
		
		/**
		 * Accepter la demande d'un postulant
		 */
		$controllers->match('/{groupe}/gestion/postulant/{postulant}/accept','LarpManager\Controllers\GroupeSecondaireController::gestionAcceptAction')
			->assert('groupe', '\d+')
			->assert('postulant', '\d+')
			->bind("groupeSecondaire.gestion.accept")
			->method('GET|POST')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->convert('postulant', 'converter.postulant:convert')
			->before($mustBeResponsable);
			
		/**
		 * Mettre en attente la demande d'un postulant
		 */
		$controllers->match('/{groupe}/gestion/postulant/{postulant}/wait','LarpManager\Controllers\GroupeSecondaireController::gestionWaitAction')
			->assert('groupe', '\d+')
			->assert('postulant', '\d+')
			->bind("groupeSecondaire.gestion.wait")
			->method('GET|POST')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->convert('postulant', 'converter.postulant:convert')
			->before($mustBeResponsable);
			
		/**
		 * Répondre à un postulant
		 */
		$controllers->match('/{groupe}/gestion/postulant/{postulant}/response','LarpManager\Controllers\GroupeSecondaireController::gestionResponseAction')
			->assert('groupe', '\d+')
			->assert('postulant', '\d+')
			->bind("groupeSecondaire.gestion.response")
			->method('GET|POST')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->convert('postulant', 'converter.postulant:convert')
			->before($mustBeResponsable);			

			
		/**
		 * Detail d'un groupe secondaire à destination des membres de ce groupe
		 */
		$controllers->match('/{groupe}/joueur','LarpManager\Controllers\GroupeSecondaireController::joueurAction')
			->assert('groupe', '\d+')
			->bind("groupeSecondaire.joueur")
			->method('GET')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->before($mustBeMembre);

			
		return $controllers;
	}
}