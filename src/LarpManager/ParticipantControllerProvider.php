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
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 * @throws AccessDeniedException
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
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
		 * Page de contrôle d'un joueur
		 */
		$controllers->match('/{participant}', 'LarpManager\Controllers\ParticipantController::indexAction')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind('participant.index')
			->method('GET')
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

		return $controllers;
	}
}