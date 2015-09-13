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
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->get('/', 'LarpManager\Controllers\UserController::viewSelfAction')
		->bind('user')
		->before(function(Request $request) use ($app) {
			// Require login. This should never actually cause access to be denied,
			// but it causes a login form to be rendered if the viewer is not logged in.
			if (!$app['user']) {
				throw new AccessDeniedException();
			}
		});
		
		$controllers->match('/{id}', 'LarpManager\Controllers\UserController::viewAction')
			->bind('user.view')
			->assert('id', '\d+')
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('VIEW_USER_ID', $request->get('id'))) {
					throw new AccessDeniedException();
				}				
			});
			
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
			
		$controllers->match('/list', 'LarpManager\Controllers\UserController::listAction')
			->bind('user.list')
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
					throw new AccessDeniedException();
				}				
			});
		
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
