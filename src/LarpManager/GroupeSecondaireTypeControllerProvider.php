<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\GroupeSecondaireTypeControllerProvider
 * 
 * @author kevin
 *
 */
class GroupeSecondaireTypeControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\GroupeSecondaireTypeController::adminListAction')
			->bind("groupeSecondaire.admin.type.list")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\GroupeSecondaireTypeController::adminAddAction')
			->bind("groupeSecondaire.admin.type.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\GroupeSecondaireTypeController::adminUpdateAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.admin.type.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\GroupeSecondaireTypeController::adminDetailAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.admin.type.detail")
			->method('GET');
			
		return $controllers;
	}
}