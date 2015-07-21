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
		
		$controllers->match('/','LarpManager\Controllers\InstallController::indexAction')
			->bind("install_index")
			->method('GET|POST');
		
		$controllers->match('/user/create','LarpManager\Controllers\InstallController::createUserAction')
			->bind("install_create_user")
			->method('GET|POST');
		
		$controllers->match('/done','LarpManager\Controllers\InstallController::doneAction')
			->bind("install_done")
			->method('GET');
		
		//$controllers->post('/installregister','LarpManager\Controllers\InstallController::registerInstallUserAction')->bind('install_register');
		//$controllers->post('/larpupdate','LarpManager\Controllers\InstallController::createOrUpdateAction')->bind('create_or_update_larp');
		
		return $controllers;
	}
}