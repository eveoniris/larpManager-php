<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class InstallControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];

		$controllers->get('/','LarpManager\Controllers\InstallController::indexAction')->bind('install');
		
		$controllers->post('/usercreate','LarpManager\Controllers\InstallController::createUserAction')->bind('create_user');
		$controllers->post('/installregister','LarpManager\Controllers\InstallController::registerInstallUserAction')->bind('install_register');
		$controllers->post('/larpupdate','LarpManager\Controllers\InstallController::createOrUpdateAction')->bind('create_or_update_larp');
		
		return $controllers;
	}
}