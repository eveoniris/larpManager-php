<?php

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
	 *  - user.informations.add
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
		
		$controllers->get('/', 'LarpManager\Controllers\UserController::viewSelfAction')
			->bind('user')
			->before(function(Request $request) use ($app) {
			// Require login. This should never actually cause access to be denied,
			// but it causes a login form to be rendered if the viewer is not logged in.
			if (!$app['user']) {
				throw new AccessDeniedException();
			}
		});
			
		$controllers->get('/etatCivil', 'LarpManager\Controllers\UserController::viewSelfEtatCivilAction')
			->bind('etatCivil')
			->before(function(Request $request) use ($app) {
				// Require login. This should never actually cause access to be denied,
				// but it causes a login form to be rendered if the viewer is not logged in.
				if (!$app['user']) {
					throw new AccessDeniedException();
				}
		});
		
		/**
		 * Affichage de la messagie de l'utilisateur 
		 */
		$controllers->get('/messagerie', 'LarpManager\Controllers\UserController::viewSelfMessagerieAction')
			->bind('user.messagerie')
			->before(function(Request $request) use ($app) {
				if ( !$app['user']) {
					throw new AccessDeniedException();
				}
		});
			
		/**
		 * Billetterie
		 */
		$controllers->get('/billetterie', 'LarpManager\Controllers\UserController::viewSelfBilletterieAction')
			->bind('user.billetterie')
			->before($mustBeAdmin)
			->before(function(Request $request) use ($app) {
				if ( !$app['user']) {
					throw new AccessDeniedException();
				}
			});				
		
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
		 * Vue de l'état-civil d'un utilisateur
		 */
		$controllers->match('/{id}/etatCivil', 'LarpManager\Controllers\UserController::viewEtatCivilAction')
			->bind('user.etatCivil.view')
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
		 * Vue de la billetterie
		 */
		$controllers->match('/{id}/billetterie', 'LarpManager\Controllers\UserController::viewBilletterieAction')
			->bind('user.billetterie.view')
			->assert('id', '\d+')
			->method('GET')
			->before($mustBeAdmin)
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
		 * Affiche l'état civil d'un utilisateur
		 */
		$controllers->match('/admin/{user}/etatCivil', 'LarpManager\Controllers\UserController::adminEtatCivilAction')
			->assert('id', '\d+')
			->bind('user.admin.etatCivil')
			->method('GET')
			->convert('user', 'converter.user:convert')
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

		$controllers->match('/login','LarpManager\Controllers\UserController::loginAction')
			->bind("user.login")
			->method('GET');
				
		$controllers->match('/logout','LarpManager\Controllers\UserController::logoutAction')
			->bind("user.logout")
			->method('GET');
		
		$controllers->match('/register','LarpManager\Controllers\UserController::registerAction')
			->bind("user.register")
			->method('GET|POST');
		
		$controllers->match('/forgot-password', 'LarpManager\Controllers\UserController::forgotPasswordAction')
			->bind('user.forgot-password')
			->method('GET|POST');
		
		$controllers->match('/{id}/information/add', 'LarpManager\Controllers\UserController::addInformationAction')
			->assert('id', '\d+')
			->bind('user.information.add')
			->method('GET|POST');
		
		$controllers->match('/{id}/information/update', 'LarpManager\Controllers\UserController::updateInformationAction')
			->assert('id', '\d+')
			->bind('user.information.update')
			->method('GET|POST');
		
		$controllers->match('/confirm-email/{token}', 'LarpManager\Controllers\UserController::confirmEmailAction')
			->bind('user.confirm-email')
			->method('GET');
		
		$controllers->match('/resend-confirmation', 'LarpManager\Controllers\UserController::resendConfirmationAction')
			->bind('user.resend-confirmation')
			->method('GET|POST');
		
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
