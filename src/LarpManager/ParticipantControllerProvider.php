<?php

/**
 * LarpManager - A Live Action Role Playing Manager
 * Copyright (C) 2016 Kevin Polez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
	 * 	- participant : liste des joueurs
	 * 	- participant.add : Ajoute un joueur (saisie des informations joueurs par un utilisateur)
	 *  - participant.update : Mise à jour des informations joueur par un utilisateur
	 *  - participant.detail : Détail des informations joueur
	 *  - participant.xp : modification des XP pour un joueur
	 *  - participant.billet
	 *  - participant.restauration
	 *  - participant.background
	 *  - participant.groupe
	 *  - participant.remove
	 *  - participant.groupeSecondaire.list
	 *  - participant.groupeSecondaire.detail
	 *  - participant.personnage
	 *  - participant.personnage.remove
	 *  - participant.personnage.trombine
	 *  - participant.personnage.surnom.edit
	 *  - participant.personnage.politique
	 *  - participant.priere.detail
	 *  - participant.priere.document
	 *  - participant.potion.detail
	 *  - participant.potion.document
	 *  - participant.magie
	 *  - participant.competence.list
	 *  - participant.competence.detail
	 *  - participant.competence.document
	 *  - participant.groupe.join
	 *  - participant.groupe.list
	 *  - participant.groupe.detail
	 *  - participant.regle.list
	 *  - participant.regle.detail
	 *  - participant.regle.document
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
		 * Vérifie que l'utilisateur dispose du role ADMIN
		 */
		$mustBeAdmin = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur dispose du role SCENARISTE
		 */
		$mustBeScenariste = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur participe à ce gn
		 */
		$mustOwnParticipant = function(Request $request) use ($app) {
			if (!$app['user']) {
				return $app->redirect($app['url_generator']->generate('user.login'));
			}
			if (!$app['security.authorization_checker']->isGranted('OWN_PARTICIPANT', $request->get('participant'))) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur est le responsable du groupe secondaire
		 */
		$mustBeResponsable = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('GROUPE_SECONDAIRE_RESPONSABLE', $request->get('groupeSecondaire'))) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur est responsable du groupe
		 */
		$mustBeGroupeResponsable = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('GROUPE_RESPONSABLE', $request->get('groupe'))) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Crée une participation pour un utilisateur
		 */
		$controllers->get('/new/{user}', 'LarpManager\Controllers\ParticipantController::newAction')
			->assert('user', '\d+')
			->convert('user', 'converter.user:convert')
			->method('GET|POST')
			->bind('participant.new')
			->before($mustBeAdmin);
		
		/**
		 * Ajoute un billet à un utilisateur
		 */
		$controllers->get('/{participant}/billet', 'LarpManager\Controllers\ParticipantController::billetAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->method('GET|POST')
			->bind('participant.billet')
			->before($mustBeAdmin);
		
		/**
		 * Ajoute une restauration à un utilisateur
		 */
		$controllers->get('/{participant}/restauration', 'LarpManager\Controllers\ParticipantController::restaurationAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->method('GET|POST')
			->bind('participant.restauration')
			->before($mustBeAdmin);


		/**
		 * Retire le participant
		 */
		$controllers->get('/{participant}/remove', 'LarpManager\Controllers\ParticipantController::removeAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->method('GET|POST')
			->bind('participant.remove')
			->before($mustBeAdmin);
			
		/**
		 * Affecte le participant à un groupe
		 */
		$controllers->get('/{participant}/groupe', 'LarpManager\Controllers\ParticipantController::groupeAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->method('GET|POST')
			->bind('participant.groupe')
			->before($mustBeAdmin);
			
		/**
		 * Page de contrôle d'un joueur
		 */
		$controllers->match('/{participant}', 'LarpManager\Controllers\ParticipantController::indexAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind('participant.index')
			->method('GET')
			->before($mustOwnParticipant);

		/** Liste des règles */
		$controllers->match('/{participant}/regle','LarpManager\Controllers\ParticipantController::regleListAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->method('GET')
			->bind('participant.regle.list')
			->before($mustOwnParticipant);
				
		/** Récupére un fichier de règle */
		$controllers->match('/{participant}/regle/{rule}','LarpManager\Controllers\ParticipantController::regleDetailAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->assert('rule', '\d+')
			->convert('rule', 'converter.rule:convert')
			->method('GET')
			->bind('participant.regle.detail')
			->before($mustOwnParticipant);
				
		/** Récupére un fichier de règle */
		$controllers->match('/{participant}/regle/{rule}/document','LarpManager\Controllers\ParticipantController::regleDocumentAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->assert('rule', '\d+')
			->convert('rule', 'converter.rule:convert')
			->method('GET')
			->bind('participant.regle.document')
			->before($mustOwnParticipant);			
			
		/**
		 * Rejoindre un groupe
		 */
		$controllers->match('/{participant}/groupe/join','LarpManager\Controllers\ParticipantController::groupeJoinAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.groupe.join")
			->method('GET|POST')
			->before($mustOwnParticipant);
						
		/**
		 * Affecte un personnage secondaire à un participant
		 */
		$controllers->match('/{participant}/personnageSecondaire','LarpManager\Controllers\ParticipantController::personnageSecondaireAction')
			->assert('participant', '\d+')
			->method('GET|POST')
			->bind('participant.personnageSecondaire')
			->convert('participant', 'converter.participant:convert')
			->before($mustOwnParticipant);
		
		/**
		 * Retire un personnage à un participant
		 */
		$controllers->match('/{participant}/personnage/{personnage}/remove','LarpManager\Controllers\ParticipantController::personnageRemoveAction')
			->assert('participant', '\d+')
			->assert('personnage', '\d+')
			->convert('participant', 'converter.participant:convert')
			->convert('personnage', 'converter.personnage:convert')
			->bind("participant.personnage.remove")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Modifie la photo lié à un personnage
		 */
		$controllers->match('/{participant}/personnage/{personnage}/trombine','LarpManager\Controllers\ParticipantController::personnageTrombineAction')
			->assert('participant', '\d+')
			->assert('personnage', '\d+')
			->convert('participant', 'converter.participant:convert')
			->convert('personnage', 'converter.personnage:convert')
			->bind("participant.personnage.trombine")
			->method('GET|POST')
			->before($mustOwnParticipant);
			
		/**
		 * Création d'un personnage
		 */
		$controllers->match('/{participant}/personnage/new','LarpManager\Controllers\ParticipantController::personnageNewAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.personnage.new")
			->method('GET|POST')
			->before($mustOwnParticipant);
			
		/**
		 * Reprendre un personnage
		 */
		$controllers->match('/{participant}/personnage/old','LarpManager\Controllers\ParticipantController::personnageOldAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.personnage.old")
			->method('GET|POST')
			->before($mustOwnParticipant);		
			
		/**
		 * Affiche les background d'un utilisateur
		 */
		$controllers->match('/{participant}/background','LarpManager\Controllers\ParticipantController::backgroundAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.background")
			->method('GET')
			->before($mustOwnParticipant);
			
		/**
		 * Affiche les liens entres les fiefs pour un personnage politique initié
		 */
		$controllers->match('/{participant}/personnage/politique','LarpManager\Controllers\ParticipantController::politiqueAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.personnage.politique")
			->method('GET')
			->before($mustOwnParticipant);
			

		/**
		 * Liste de tous les groupes secondaires public
		 */
		$controllers->match('/{participant}/groupeSecondaire/list','LarpManager\Controllers\ParticipantController::groupeSecondaireListAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.groupeSecondaire.list")
			->method('GET')
			->before($mustOwnParticipant);
				
			
		/**
		 * Postuler à un groupe secondaire
		 */
		$controllers->match('/{participant}/groupeSecondaire/{groupeSecondaire}/postuler','LarpManager\Controllers\ParticipantController::groupeSecondairePostulerAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->assert('groupeSecondaire', '\d+')
			->convert('groupeSecondaire', 'converter.secondaryGroup:convert')
			->bind("participant.groupeSecondaire.postuler")
			->method('GET|POST')
			->before($mustOwnParticipant);
		
		/**
		 * Detail d'un groupe secondaire à destination des membres de ce groupe
		 */
		$controllers->match('/{participant}/groupeSecondaire/{groupeSecondaire}/detail','LarpManager\Controllers\ParticipantController::groupeSecondaireDetailAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->assert('groupeSecondaire', '\d+')
			->convert('groupeSecondaire', 'converter.secondaryGroup:convert')
			->bind("participant.groupeSecondaire.detail")
			->method('GET')
			->before($mustOwnParticipant);
			
		/**
		 * Rejeter la demande d'un postulant
		 */
		$controllers->match('/{participant}/groupeSecondaire/{groupeSecondaire}/postulant/{postulant}/reject','LarpManager\Controllers\ParticipantController::groupeSecondaireRejectAction')
			->assert('participant', '\d+')
			->assert('groupeSecondaire', '\d+')
			->assert('postulant', '\d+')
			->bind("participant.groupeSecondaire.reject")
			->method('GET|POST')
			->convert('participant', 'converter.participant:convert')
			->convert('groupeSecondaire', 'converter.secondaryGroup:convert')
			->convert('postulant', 'converter.postulant:convert')
			->before($mustBeResponsable);
		
		/**
		 * Accepter la demande d'un postulant
		 */
		$controllers->match('/{participant}/groupeSecondaire/{groupeSecondaire}/postulant/{postulant}/accept','LarpManager\Controllers\ParticipantController::groupeSecondaireAcceptAction')
			->assert('participant', '\d+')
			->assert('groupeSecondaire', '\d+')
			->assert('postulant', '\d+')
			->bind("participant.groupeSecondaire.accept")
			->method('GET|POST')
			->convert('participant', 'converter.participant:convert')
			->convert('groupeSecondaire', 'converter.secondaryGroup:convert')
			->convert('postulant', 'converter.postulant:convert')
			->before($mustBeResponsable);
			
		/**
		 * Mettre en attente la demande d'un postulant
		 */
		$controllers->match('/{participant}/groupeSecondaire/{groupeSecondaire}/postulant/{postulant}/wait','LarpManager\Controllers\ParticipantController::groupeSecondaireWaitAction')
			->assert('participant', '\d+')
			->assert('groupeSecondaire', '\d+')
			->assert('postulant', '\d+')
			->bind("participant.groupeSecondaire.wait")
			->method('GET|POST')
			->convert('participant', 'converter.participant:convert')
			->convert('groupeSecondaire', 'converter.secondaryGroup:convert')
			->convert('postulant', 'converter.postulant:convert')
			->before($mustBeResponsable);
			
		/**
		 * Répondre à un postulant
		 */
		$controllers->match('/{participant}/groupeSecondaire/{groupeSecondaire}/postulant/{postulant}/response','LarpManager\Controllers\ParticipantController::groupeSecondaireResponseAction')
			->assert('participant', '\d+')
			->assert('groupeSecondaire', '\d+')
			->assert('postulant', '\d+')
			->bind("participant.groupeSecondaire.response")
			->method('GET|POST')
			->convert('participant', 'converter.participant:convert')
			->convert('groupeSecondaire', 'converter.secondaryGroup:convert')
			->convert('postulant', 'converter.postulant:convert')
			->before($mustBeResponsable);

			
			
			
		/**
		 * Liste des religions
		 */
		$controllers->match('/{participant}/religion/list','LarpManager\Controllers\ParticipantController::religionListAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.religion.list")
			->method('GET')
			->before($mustOwnParticipant);
		
		/**
		 * Choix d'une religion
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{participant}/religion/add','LarpManager\Controllers\ParticipantController::religionAddAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.religion.add")
			->method('GET|POST')
			->before($mustOwnParticipant);
		
		/**
		 * Choix d'une origine
		 * Accessible uniquements'il n'a pas déjà choisi d'origine
		 */
		$controllers->match('/{participant}/origine','LarpManager\Controllers\ParticipantController::origineAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.origine")
			->method('GET|POST')
			->before($mustOwnParticipant);
	
			
		/**
		 * Ajout d'une compétence au personnage
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{participant}/competence/add','LarpManager\Controllers\ParticipantController::competenceAddAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.competence.add")
			->method('GET|POST')
			->before($mustOwnParticipant);
		
		/**
		 * Détail d'une prière
		 */
		$controllers->match('/{participant}/priere/{priere}/detail', 'LarpManager\Controllers\ParticipantController::priereDetailAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->assert('priere', '\d+')
			->convert('priere', 'converter.priere:convert')
			->bind("participant.priere.detail")
			->method('GET')
			->before($mustOwnParticipant);
			
		/**
		 * Obtenir le document lié à une prière
		 */
		$controllers->match('/{participant}/priere/{priere}/document', 'LarpManager\Controllers\ParticipantController::priereDocumentAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->assert('priere', '\d+')
			->convert('priere', 'converter.priere:convert')
			->bind("participant.priere.document")
			->method('GET')
			->before($mustOwnParticipant);
			
		/**
		 * Détail d'une potion
		 */
		$controllers->match('/{participant}/potion/{potion}/detail', 'LarpManager\Controllers\ParticipantController::potionDetailAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->assert('potion', '\d+')
			->convert('potion', 'converter.potion:convert')
			->bind("participant.potion.detail")
			->method('GET')
			->before($mustOwnParticipant);

		/**
		 * Obtenir le document lié à une potion
		 */
		$controllers->match('/{participant}/potion/{potion}/document', 'LarpManager\Controllers\ParticipantController::potionDocumentAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->assert('potion', '\d+')
			->convert('potion', 'converter.potion:convert')
			->bind("participant.potion.document")
			->method('GET')
			->before($mustOwnParticipant);
		
		/**
		 * Détail d'un sort
		 */
		$controllers->match('/{participant}/sort/{sort}/detail', 'LarpManager\Controllers\ParticipantController::sortDetailAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->assert('sort', '\d+')
			->convert('sort', 'converter.sort:convert')
			->bind("participant.sort.detail")
			->method('GET')
			->before($mustOwnParticipant);
			
		/**
		 * Obtenir le document lié à un sort
		 */
		$controllers->match('/{participant}/sort/{sort}/document', 'LarpManager\Controllers\ParticipantController::sortDocumentAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->assert('sort', '\d+')
			->convert('sort', 'converter.sort:convert')
			->bind("participant.sort.document")
			->method('GET')
			->before($mustOwnParticipant);
			
		/**
		 * Page de présentation de la magie, domaine et sortilèges
		 */
		$controllers->match('/{participant}/magie','LarpManager\Controllers\ParticipantController::magieAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind('participant.magie')
			->method('GET')
			->before($mustOwnParticipant);
		
		/**
		 * Liste des classes pour les joueurs
		 */
		$controllers->match('/{participant}/classe/list','LarpManager\Controllers\ParticipantController::classeListAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.classe.list")
			->method('GET')
			->before($mustOwnParticipant);
			
		/**
		 * Liste des compétences pour les joueurs
		 */
		$controllers->match('/{participant}/competence/list','LarpManager\Controllers\ParticipantController::competenceListAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.competence.list")
			->method('GET')
			->before($mustOwnParticipant);
			
		/**
		 * Détail d'une compétence
		 */
		$controllers->match('/{participant}/competence/{competence}/detail','LarpManager\Controllers\ParticipantController::competenceDetailAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->assert('competence', '\d+')
			->convert('competence', 'converter.competence:convert')
			->bind("participant.competence.detail")
			->method('GET')
			->before($mustOwnParticipant);
			
		/**
		 * Obtenir le document lié à une compétence
		 */
		$controllers->match('/{participant}/competence/{competence}/document','LarpManager\Controllers\ParticipantController::competenceDocumentAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->assert('competence', '\d+')
			->convert('competence', 'converter.competence:convert')
			->bind("participant.competence.document")
			->method('GET')
			->before($mustOwnParticipant);
			
		/**
		 * Détail du personnage
		 */
		$controllers->match('/{participant}/personnage','LarpManager\Controllers\ParticipantController::personnageAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("participant.personnage")
			->method('GET')
			->before($mustOwnParticipant);
		
		/**
		 * Formulaire de choix d'une nouvelle potion
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{participant}/personnage/potion/{niveau}','LarpManager\Controllers\ParticipantController::potionAction')
			->assert('niveau', '\d+')
			->assert('participant', '\d+')
			->bind("participant.personnage.potion")
			->method('GET|POST')
			->convert('participant', 'converter.participant:convert')
			->before($mustOwnParticipant);

		/**
		 * Formulaire d'ajout des langues gagnés grace à litterature initie
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{participant}/personnage/langueCommune','LarpManager\Controllers\ParticipantController::langueCommuneAction')
			->assert('participant', '\d+')
			->bind("participant.personnage.langueCommune")
			->method('GET|POST')
			->convert('participant', 'converter.participant:convert')
			->before($mustOwnParticipant);
				
			
		/**
		 * Formulaire d'ajout des langues gagnés grace à litterature initie
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{participant}/personnage/langueCourante','LarpManager\Controllers\ParticipantController::langueCouranteAction')
			->assert('participant', '\d+')
			->bind("participant.personnage.langueCourante")
			->method('GET|POST')
			->convert('participant', 'converter.participant:convert')
			->before($mustOwnParticipant);
			
		/**
		 * Formulaire d'ajout des langues gagnés grace à litterature initie
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{participant}/personnage/langueAncienne','LarpManager\Controllers\ParticipantController::langueAncienneAction')
			->assert('participant', '\d+')
			->bind("participant.personnage.langueAncienne")
			->method('GET|POST')
			->convert('participant', 'converter.participant:convert')
			->before($mustOwnParticipant);
		

		/**
		 * Formulaire de choix du domaine de magie
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{participant}/personnage/domaine','LarpManager\Controllers\ParticipantController::domaineMagieAction')
			->assert('personnage', '\d+')
			->bind("participant.personnage.domaine")
			->method('GET|POST')
			->convert('participant', 'converter.participant:convert')
			->before($mustOwnParticipant);
				
		/**
		 * Formulaire de choix d'un nouveau sort
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{participant}/personnage/sort/{niveau}','LarpManager\Controllers\ParticipantController::sortAction')
			->assert('participant', '\d+')
			->assert('niveau', '\d+')
			->bind("participant.personnage.sort")
			->method('GET|POST')
			->convert('participant', 'converter.participant:convert')
			->before($mustOwnParticipant);
			
		/**
		 * Modification de quelques informations
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{participant}/personnage/edit','LarpManager\Controllers\ParticipantController::personnageEditAction')
			->assert('participant', '\d+')
			->bind("participant.personnage.edit")
			->method('GET|POST')
			->convert('participant', 'converter.participant:convert')
			->before($mustOwnParticipant);
			
		/**
		 * Liste des joueurs
		 * Accessible uniquement aux admin
		 */
		$controllers->match('/admin','LarpManager\Controllers\ParticipantController::listAction')
			->bind("participant")
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
			
			
			/**
			 * Demander une alliance
			 */
			$controllers->match('/{participant}/groupe/{groupe}/alliance/request','LarpManager\Controllers\ParticipantController::requestAllianceAction')
				->assert('participant', '\d+')
				->assert('groupe', '\d+')
				->bind("groupe.requestAlliance")
				->method('GET|POST')
				->convert('groupe', 'converter.groupe:convert')
				->convert('participant', 'converter.participant:convert')
				->before($mustBeGroupeResponsable);
				
			/**
			 * Annuler une demande d'alliance
			 */
			$controllers->match('/{participant}/groupe/{groupe}/alliance/{alliance}/cancel','LarpManager\Controllers\ParticipantController::cancelRequestedAllianceAction')
				->assert('participant', '\d+')
				->assert('groupe', '\d+')
				->assert('alliance', '\d+')
				->bind("groupe.cancelRequestedAlliance")
				->method('GET|POST')
				->convert('participant', 'converter.participant:convert')
				->convert('groupe', 'converter.groupe:convert')
				->convert('alliance', 'converter.alliance:convert')
				->before($mustBeGroupeResponsable);
				
			/**
			 * Accepter une alliance
			 */
			$controllers->match('/{participant}/groupe/{groupe}/alliance/{alliance}/accept','LarpManager\Controllers\ParticipantController::acceptAllianceAction')
				->assert('participant', '\d+')
				->assert('groupe', '\d+')
				->assert('alliance', '\d+')
				->bind("groupe.acceptAlliance")
				->method('GET|POST')
				->convert('participant', 'converter.participant:convert')
				->convert('groupe', 'converter.groupe:convert')
				->convert('alliance', 'converter.alliance:convert')
				->before($mustBeGroupeResponsable);
				
			/**
			 * Refuser une alliance
			 */
			$controllers->match('/{participant}/groupe/{groupe}/alliance/{alliance}/refuse','LarpManager\Controllers\ParticipantController::refuseAllianceAction')
				->assert('participant', '\d+')
				->assert('groupe', '\d+')
				->assert('alliance', '\d+')
				->bind("groupe.refuseAlliance")
				->method('GET|POST')
				->convert('participant', 'converter.participant:convert')
				->convert('groupe', 'converter.groupe:convert')
				->convert('alliance', 'converter.alliance:convert')
				->before($mustBeGroupeResponsable);
				
			/**
			 * Casser une alliance
			 */
			$controllers->match('/{participant}/groupe/{groupe}/alliance/{alliance}/break','LarpManager\Controllers\ParticipantController::breakAllianceAction')
				->assert('participant', '\d+')
				->assert('groupe', '\d+')
				->assert('alliance', '\d+')
				->bind("groupe.breakAlliance")
				->method('GET|POST')
				->convert('participant', 'converter.participant:convert')
				->convert('groupe', 'converter.groupe:convert')
				->convert('alliance', 'converter.alliance:convert')
				->before($mustBeGroupeResponsable);
				
			/**
			 * Déclarer la guerre
			 */
			$controllers->match('/{participant}/groupe/{groupe}/enemy/declareWar','LarpManager\Controllers\ParticipantController::declareWarAction')
				->assert('participant', '\d+')
				->assert('groupe', '\d+')
				->bind("groupe.declareWar")
				->method('GET|POST')
				->convert('participant', 'converter.participant:convert')
				->convert('groupe', 'converter.groupe:convert')
				->before($mustBeGroupeResponsable);
					
				
			/**
			 * Demander la paix
			 */
			$controllers->match('/{participant}/groupe/{groupe}/peace/{enemy}/requestPeace','LarpManager\Controllers\ParticipantController::requestPeaceAction')
				->assert('participant', '\d+')
				->assert('groupe', '\d+')
				->assert('enemy', '\d+')
				->bind("groupe.requestPeace")
				->method('GET|POST')
				->convert('participant', 'converter.participant:convert')
				->convert('groupe', 'converter.groupe:convert')
				->convert('enemy', 'converter.enemy:convert')
				->before($mustBeGroupeResponsable);
				
			/**
			 * Accepter la paix
			 */
			$controllers->match('/{participant}/groupe/{groupe}/peace/{enemy}/acceptPeace','LarpManager\Controllers\ParticipantController::acceptPeaceAction')
				->assert('participant', '\d+')
				->assert('groupe', '\d+')
				->assert('enemy', '\d+')
				->bind("groupe.acceptPeace")
				->method('GET|POST')
				->convert('participant', 'converter.participant:convert')
				->convert('groupe', 'converter.groupe:convert')
				->convert('enemy', 'converter.enemy:convert')
				->before($mustBeGroupeResponsable);
				
			/**
			 * Refuser la paix
			 */
			$controllers->match('/{participant}/groupe/{groupe}/peace/{enemy}/refusePeace','LarpManager\Controllers\ParticipantController::refusePeaceAction')
				->assert('participant', '\d+')
				->assert('groupe', '\d+')
				->assert('enemy', '\d+')
				->bind("groupe.refusePeace")
				->method('GET|POST')
				->convert('participant', 'converter.participant:convert')
				->convert('groupe', 'converter.groupe:convert')
				->convert('enemy', 'converter.enemy:convert')
				->before($mustBeGroupeResponsable);
					
			/**
			 * Annuler une demande de paix
			 */
			$controllers->match('/{participant}/groupe/{groupe}/peace/{enemy}/cancel','LarpManager\Controllers\ParticipantController::cancelRequestedPeaceAction')
				->assert('participant', '\d+')
				->assert('groupe', '\d+')
				->assert('enemy', '\d+')
				->bind("groupe.cancelRequestedPeace")
				->method('GET|POST')
				->convert('participant', 'converter.participant:convert')
				->convert('groupe', 'converter.groupe:convert')
				->convert('enemy', 'converter.enemy:convert')
				->before($mustBeGroupeResponsable);
					

		return $controllers;
	}
}