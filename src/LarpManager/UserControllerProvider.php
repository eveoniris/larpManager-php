<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class UserControllerProvider implements ControllerProviderInterface
{
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
		
		$controllers->get('/{id}', 'LarpManager\Controllers\UserController::viewAction')
			->bind('user.view')
			->assert('id', '\d+');
			
		$controllers->match('/{id}/edit', 'LarpManager\Controllers\UserController::editAction')
			->bind('user.edit')
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security']->isGranted('EDIT_USER_ID', $request->get('id'))) {
					throw new AccessDeniedException();
				}
			});
			
		$controllers->get('/list', 'LarpManager\Controllers\UserController::listAction')
			->bind('user.list');
		
		$controllers->match('/right', 'LarpManager\Controllers\UserController::rightAction')
			->bind('user.right')
			->method('GET|POST');

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
