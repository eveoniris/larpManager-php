<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class GuildeControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];

		$controllers->get('/','LarpManager\Controllers\GuildeController::indexAction')->bind('guilde_list');
		$controllers->get('/add','LarpManager\Controllers\GuildeController::addAction')->bind('guilde_add');
		$controllers->post('/add','LarpManager\Controllers\GuildeController::addAction');
		$controllers->post('/{index}/modify','LarpManager\Controllers\GuildeController::modifyAction');
		$controllers->get('/{index}/modify','LarpManager\Controllers\GuildeController::modifyAction')->bind("guilde_modify");
		$controllers->get('/{index}/remove','LarpManager\Controllers\GuildeController::removeAction')->bind('guilde_remove');
		$controllers->post('/{index}/remove','LarpManager\Controllers\GuildeController::removeAction');
		$controllers->get('/{index}','LarpManager\Controllers\GuildeController::detailAction');

		return $controllers;
	}
}