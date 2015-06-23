<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class GnControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];

		$controllers->get('/','LarpManager\Controllers\GnController::indexAction')->bind('gn_list');
		$controllers->get('/add','LarpManager\Controllers\GnController::addAction')->bind('gn_add');
		$controllers->get('/{index}/remove','LarpManager\Controllers\GnController::removeAction')->bind('gn_remove');
		$controllers->get('/{index}','LarpManager\Controllers\GnController::detailAction');
		
		return $controllers;
	}
}