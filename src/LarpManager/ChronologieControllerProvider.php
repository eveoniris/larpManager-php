<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class ChronologieControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];

		$controllers->get('/','LarpManager\Controllers\ChronologieController::indexAction')->bind("chrono_list");
		$controllers->get('/add','LarpManager\Controllers\ChronologieController::addAction')->bind("chrono_add");
		$controllers->post('/add','LarpManager\Controllers\ChronologieController::addAction');
		$controllers->post('/{index}/modify','LarpManager\Controllers\ChronologieController::modifyAction');
		$controllers->get('/{index}/modify','LarpManager\Controllers\ChronologieController::modifyAction')->bind("chrono_modify");
		$controllers->get('/{index}/remove','LarpManager\Controllers\ChronologieController::removeAction')->bind("chrono_remove");
		$controllers->post('/{index}/remove','LarpManager\Controllers\ChronologieController::removeAction');
		$controllers->get('/{index}','LarpManager\Controllers\ChronologieController::detailAction');

		return $controllers;
	}
}