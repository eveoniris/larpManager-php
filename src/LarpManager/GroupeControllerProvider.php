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
	 *  - groupe.requestAlliance
	 *  - groupe.cancelRequestedAlliance
	 *  - groupe.acceptAlliance
	 *  - groupe.refuseAlliance
	 *  - groupe.breakAlliance
	 *  - groupe.declareWar
	 *  - groupe.requestPeace
	 *  - groupe.acceptPeace
	 *  - groupe.refusePeace
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 * @throws AccessDeniedException
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		/**
		 * Vérifie que l'utilisateur dispose du role SCENARISTE
		 */
		$mustBeScenariste = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur dispose du role ADMIN
		 * @var unknown $mustBeAdmin
		 */
		$mustBeAdmin = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur est membre du groupe
		 * @var unknown $mustBeMember
		 */
		$mustBeMember = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('GROUPE_MEMBER', $request->get('index'))) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur est responsable du groupe
		 * @var unknown $mustBeResponsable
		 */
		$mustBeResponsable = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('GROUPE_RESPONSABLE', $request->get('groupe'))) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Accueil groupe pour les joueurs
		 */
		$controllers->match('/','LarpManager\Controllers\GroupeController::accueilAction')
			->bind("groupe")
			->method('GET');
			
		/**
		 * Ajoute un nouveau personnage dans un groupe
		 */
		$controllers->match('/{index}/personnage/add','LarpManager\Controllers\GroupeController::personnageAddAction')
			->assert('index', '\d+')
			->bind("groupe.personnage.add")
			->method('GET|POST')
			->before($mustBeMember);
							
		/**
		 * Demander une alliance
		 */
		$controllers->match('/{groupe}/alliance/request','LarpManager\Controllers\GroupeController::requestAllianceAction')
			->assert('groupe', '\d+')
			->bind("groupe.requestAlliance")
			->method('GET|POST')
			->convert('groupe', 'converter.groupe:convert')
			->before($mustBeResponsable);

		/**
		 * Annuler une demande d'alliance
		 */
		$controllers->match('/{groupe}/alliance/{alliance}/cancel','LarpManager\Controllers\GroupeController::cancelRequestedAllianceAction')
			->assert('groupe', '\d+')
			->assert('alliance', '\d+')
			->bind("groupe.cancelRequestedAlliance")
			->method('GET|POST')
			->convert('groupe', 'converter.groupe:convert')
			->convert('alliance', 'converter.alliance:convert')
			->before($mustBeResponsable);
		/**
		 * Accepter une alliance
		 */
		$controllers->match('/{groupe}/alliance/{alliance}/accept','LarpManager\Controllers\GroupeController::acceptAllianceAction')
			->assert('groupe', '\d+')
			->assert('alliance', '\d+')
			->bind("groupe.acceptAlliance")
			->method('GET|POST')
			->convert('groupe', 'converter.groupe:convert')
			->convert('alliance', 'converter.alliance:convert')
			->before($mustBeResponsable);
		
		/**
		 * Refuser une alliance
		 */
		$controllers->match('/{groupe}/alliance/{alliance}/refuse','LarpManager\Controllers\GroupeController::refuseAllianceAction')
			->assert('groupe', '\d+')
			->assert('alliance', '\d+')
			->bind("groupe.refuseAlliance")
			->method('GET|POST')
			->convert('groupe', 'converter.groupe:convert')
			->convert('alliance', 'converter.alliance:convert')
			->before($mustBeResponsable);			

		/**
		 * Casser une alliance
		 */
		$controllers->match('/{groupe}/alliance/{alliance}/break','LarpManager\Controllers\GroupeController::breakAllianceAction')
			->assert('groupe', '\d+')
			->assert('alliance', '\d+')
			->bind("groupe.breakAlliance")
			->method('GET|POST')
			->convert('groupe', 'converter.groupe:convert')
			->convert('alliance', 'converter.alliance:convert')
			->before($mustBeResponsable);

		/**
		 * Déclarer la guerre
		 */
		$controllers->match('/{groupe}/enemy/declareWar','LarpManager\Controllers\GroupeController::declareWarAction')
			->assert('groupe', '\d+')
			->bind("groupe.declareWar")
			->method('GET|POST')
			->convert('groupe', 'converter.groupe:convert')
			->before($mustBeResponsable);			
			

		/**
		 * Demander la paix
		 */
		$controllers->match('/{groupe}/peace/{enemy}/requestPeace','LarpManager\Controllers\GroupeController::requestPeaceAction')
			->assert('groupe', '\d+')
			->assert('enemy', '\d+')
			->bind("groupe.requestPeace")
			->method('GET|POST')
			->convert('groupe', 'converter.groupe:convert')
			->convert('enemy', 'converter.enemy:convert')
			->before($mustBeResponsable);
		
		/**
		 * Accepter la paix
		 */
		$controllers->match('/{groupe}/peace/{enemy}/acceptPeace','LarpManager\Controllers\GroupeController::acceptPeaceAction')
			->assert('groupe', '\d+')
			->assert('enemy', '\d+')
			->bind("groupe.acceptPeace")
			->method('GET|POST')
			->convert('groupe', 'converter.groupe:convert')
			->convert('enemy', 'converter.enemy:convert')
			->before($mustBeResponsable);			
		
		/**
		 * Refuser la paix
		 */
		$controllers->match('/{groupe}/peace/{enemy}/refusePeace','LarpManager\Controllers\GroupeController::refusePeaceAction')
			->assert('groupe', '\d+')
			->assert('enemy', '\d+')
			->bind("groupe.refusePeace")
			->method('GET|POST')
			->convert('groupe', 'converter.groupe:convert')
			->convert('enemy', 'converter.enemy:convert')
			->before($mustBeResponsable);
			
		/**
		 * Annuler une demande de paix
		 */
		$controllers->match('/{groupe}/peace/{enemy}/cancel','LarpManager\Controllers\GroupeController::cancelRequestedPeaceAction')
			->assert('groupe', '\d+')
			->assert('enemy', '\d+')
			->bind("groupe.cancelRequestedPeace")
			->method('GET|POST')
			->convert('groupe', 'converter.groupe:convert')
			->convert('enemy', 'converter.enemy:convert')
			->before($mustBeResponsable);			
			
		/**
		 * Liste des groupes
		 */
		$controllers->match('/admin/list','LarpManager\Controllers\GroupeController::adminListAction')
			->bind("groupe.admin.list")
			->method('GET|POST')
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
		 *  Ajout d'un groupe (Scénariste uniquement)
		 */
		$controllers->match('/add','LarpManager\Controllers\GroupeController::addAction')
			->bind("groupe.add")
			->method('GET|POST')
			->before($mustBeScenariste);

		/**
		 * Modification des places disponibles (Admin uniquement)
		 */
		$controllers->match('/place','LarpManager\Controllers\GroupeController::placeAction')
			->bind("groupe.place")
			->method('GET|POST')
			->before($mustBeAdmin);
			
		/**
		 *  Mise à jour d'un groupe (scénariste uniquement)
		 */
		$controllers->match('/{index}/update','LarpManager\Controllers\GroupeController::updateAction')
			->assert('index', '\d+')
			->bind("groupe.update")
			->method('GET|POST')
			->before($mustBeScenariste);

		/**
		 * Ajout d'un background (scénariste uniquement)
		 */
		$controllers->match('/{index}/background/add','LarpManager\Controllers\GroupeController::addBackgroundAction')
			->assert('index', '\d+')
			->bind("groupe.background.add")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 *  Modification d'un background (scénariste uniquement)
		 */
		$controllers->match('/{index}/background/update','LarpManager\Controllers\GroupeController::updateBackgroundAction')
			->assert('index', '\d+')
			->bind("groupe.background.update")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		return $controllers;
	}
}
