<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\GuildeControllerProvider
 * 
 * @author kevin
 *
 */
class GuildeControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les guildes
	 * Routes :
	 * 	- guilde_list
	 * 	- guilde_add
	 *  - guilde_modify
	 *  - guilde_remove
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
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