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
 * LarpManager\UserControllerProvider
 * 
 * @author kevin
 *
 */
class UserControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les users
	 * Routes :
	 * 	- user
	 * 	- user.view
	 *  - user.add
	 *  - user.edit
	 *  - user.list
	 *  - user.right
	 *  - user.login
	 *  - user.logout
	 *  - user.register
	 *  - user.forgot-password
	 *  - user.login_check
	 *  - user.billetterie
	 *
	 * @param Application $app
	 * @return Controllers $controllers
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
		 * Vérifie que l'utilisateur est connecté
		 */
		$mustBeUser = function(Request $request) use ($app) {
			if (!$app['user']) {
				return $app->redirect($app['url_generator']->generate('user.login'));
			}
			if (!$app['security.authorization_checker']->isGranted('ROLE_USER')) {
				throw new AccessDeniedException();
			}
		};
		
		$controllers->get('/', 'LarpManager\Controllers\UserController::viewSelfAction')
			->bind('user')
			->before($mustBeUser);
		
		/**
		 * Affichage de la messagie de l'utilisateur 
		 */
		$controllers->get('/messagerie', 'LarpManager\Controllers\UserController::viewSelfMessagerieAction')
			->bind('user.messagerie')
			->before($mustBeUser);
			
		/**
		 * Billetterie
		 */
		$controllers->get('/billetterie', 'LarpManager\Controllers\UserController::billetterieAction')
			->bind('user.billetterie')
			->before($mustBeUser);
			
		/**
		 * Ajoute un billet à un utilisateur
		 */
		$controllers->get('/{user}/billet', 'LarpManager\Controllers\UserController::billetAction')
			->assert('user', '\d+')
			->convert('user', 'converter.user:convert')
			->method('GET|POST')
			->bind('user.billet')
			->before($mustBeAdmin);
			
		/** 
		 * Formulaire de choix d'un personnage secondaire 
		 */
		$controllers->match('/personnageSecondaire','LarpManager\Controllers\UserController::personnageSecondaireAction')
			->method('GET|POST')
			->bind('user.personnageSecondaire')
			->before($mustBeUser);
			
		/**
		 * Restrictions alimentaires
		 */
		$controllers->match('/restriction','LarpManager\Controllers\UserController::restrictionAction')
			->method('GET|POST')
			->bind('user.restriction')
			->before($mustBeUser);
			
		/**
		 * Ajoute les informations administratives
		 */
		$controllers->match('/etatCivil', 'LarpManager\Controllers\UserController::etatCivilAction')
			->bind('user.etatCivil')
			->method('GET|POST')
			->before($mustBeUser);
		
		/**
		 * Affiche les informations de la fédégn
		 */
		$controllers->match('/fedegn', 'LarpManager\Controllers\UserController::fedegnAction')
			->bind('user.fedegn')
			->method('GET')
			->before($mustBeUser);
			
		/**
		 * Vue d'un utilisateur
		 */
		$controllers->match('/{id}', 'LarpManager\Controllers\UserController::viewAction')
			->bind('user.view')
			->assert('id', '\d+')
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('VIEW_USER_ID', $request->get('id'))) {
					throw new AccessDeniedException();
				}				
			});

			
		/**
		 * Vue de la messagerie d'un utilisateur
		 */
		$controllers->match('/{id}/messagerie', 'LarpManager\Controllers\UserController::viewMessagerieAction')
			->bind('user.messagerie.view')
			->assert('id', '\d+')
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('VIEW_USER_ID', $request->get('id'))) {
					throw new AccessDeniedException();
				}
			});
						
		/**
		 * Archiver un message
		 */
		$controllers->match('/{id}/messagerie/{message}/archive', 'LarpManager\Controllers\UserController::messageArchiveAction')
			->bind('user.messagerie.message.archive')
			->assert('id', '\d+')
			->assert('message', '\d+')
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('VIEW_USER_ID', $request->get('id'))) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Répondre à un message
		 */
		$controllers->match('/{id}/messagerie/{message}/response', 'LarpManager\Controllers\UserController::messageResponseAction')
			->bind('user.messagerie.message.response')
			->assert('id', '\d+')
			->assert('message', '\d+')
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('VIEW_USER_ID', $request->get('id'))) {
					throw new AccessDeniedException();
				}
			});			
		
		/**
		 * nouveau message
		 */
		$controllers->match('/{id}/messagerie/new', 'LarpManager\Controllers\UserController::newMessageAction')
			->bind('user.messagerie.message.new')
			->assert('id', '\d+')
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('VIEW_USER_ID', $request->get('id'))) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * nouveau message
		 */
		$controllers->match('/{from_id}/messagerie/new/{to_id}', 'LarpManager\Controllers\UserController::newMessageViewAction')
			->bind('user.message.new')
			->assert('from_id', '\d+')
			->assert('to_id', '\d+')
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('VIEW_USER_ID', $request->get('from_id'))) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Ajout d'un utilisateur
		 */
		$controllers->match('/add', 'LarpManager\Controllers\UserController::addAction')
			->bind('user.add')
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
			
		$controllers->match('/{id}/edit', 'LarpManager\Controllers\UserController::editAction')
			->bind('user.edit')
			->assert('id', '\d+')
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('EDIT_USER_ID', $request->get('id'))) {
					throw new AccessDeniedException();
				}
			});
		
		/**
		 * Affiche la liste des utilisateurs (admin uniquement)
		 */
		$controllers->match('/admin/list', 'LarpManager\Controllers\UserController::adminListAction')
			->bind('user.admin.list')
			->method('GET|POST')
			->before($mustBeAdmin);
			
		/**
		 * Gestion des droits
		 */
		$controllers->match('/right', 'LarpManager\Controllers\UserController::rightAction')
			->bind('user.right')
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException();
				}
			});

		/**
		 * Connection à LarpManager
		 */
		$controllers->match('/login','LarpManager\Controllers\UserController::loginAction')
			->bind("user.login")
			->method('GET');
				
		/**
		 * Deconnection de LarpManager
		 */
		$controllers->match('/logout','LarpManager\Controllers\UserController::logoutAction')
			->bind("user.logout")
			->method('GET');
		
		/**
		 * Création d'un compte
		 */
		$controllers->match('/register','LarpManager\Controllers\UserController::registerAction')
			->bind("user.register")
			->method('GET|POST');
		
		/**
		 * Réinitialisation du mot de passe
		 */
		$controllers->match('/forgot-password', 'LarpManager\Controllers\UserController::forgotPasswordAction')
			->bind('user.forgot-password')
			->method('GET|POST');
		
		/**
		 * Confirmation de l'adresse email
		 */
		$controllers->match('/confirm-email/{token}', 'LarpManager\Controllers\UserController::confirmEmailAction')
			->bind('user.confirm-email')
			->method('GET');
		
		/**
		 * Renvoyer le mail de confirmation
		 */
		$controllers->match('/resend-confirmation', 'LarpManager\Controllers\UserController::resendConfirmationAction')
			->bind('user.resend-confirmation')
			->method('GET|POST');
		
		/**
		 * Réinitialisation du mot de passe
		 */
		$controllers->match('/reset-password/{token}', 'LarpManager\Controllers\UserController::resetPasswordAction')
			->bind('user.reset-password')
			->method('GET|POST');
			
		// login_check and logout are dummy routes so we can use the names.
		// The security provider should intercept these, so no controller is needed.
		$controllers->match('/login_check', function() {})
			->bind('user.login_check')
			->method('GET|POST');
		
		$controllers->get('/logout', function() {})
			->bind('user.logout');
					
		return $controllers;
	}
}
