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
		
		$controllers->match('/','LarpManager\Controllers\GroupeSecondaireTypeController::indexAction')
			->bind("groupeSecondaire.type")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\GroupeSecondaireTypeController::addAction')
			->bind("groupeSecondaire.type.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\GroupeSecondaireTypeController::updateAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.type.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\GroupeSecondaireTypeController::detailAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.type.detail")
			->method('GET');
			
		return $controllers;
	}
}