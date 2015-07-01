<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class PaysControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];

		$controllers->get('/','LarpManager\Controllers\PaysController::indexAction')->bind("pays_list");
		$controllers->get('/add','LarpManager\Controllers\PaysController::addAction')->bind("pays_add");
		$controllers->post('/add','LarpManager\Controllers\PaysController::addAction');
		$controllers->post('/{index}/modify','LarpManager\Controllers\PaysController::modifyAction')->bind("pays_modify");
		$controllers->post('/{index}/modify','LarpManager\Controllers\PaysController::modifyAction');
		$controllers->get('/{index}/remove','LarpManager\Controllers\PaysController::removeAction')->bind("pays_remove");
		$controllers->post('/{index}/remove','LarpManager\Controllers\PaysController::removeAction');
		$controllers->get('/{index}','LarpManager\Controllers\PaysController::detailAction');

		return $controllers;
	}
}