<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\PersonnageSecondaireControllerProvider
 * 
 * @author kevin
 *
 */
class PersonnageSecondaireControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\PersonnageSecondaireController::indexAction')
			->bind("personnageSecondaire")
			->method('GET');

		$controllers->match('/add','LarpManager\Controllers\PersonnageSecondaireController::addAction')
			->bind("personnageSecondaire.add")
			->method('GET|POST');
			
		$controllers->match('/{personnageSecondaire}/detail','LarpManager\Controllers\PersonnageSecondaireController::detailAction')
			->assert('personnageSecondaire', '\d+')
			->bind("personnageSecondaire.detail")
			->method('GET')
			->convert('personnageSecondaire', 'converter.personnageSecondaire:convert');
		
		$controllers->match('/{personnageSecondaire}/update','LarpManager\Controllers\PersonnageSecondaireController::updateAction')
			->assert('personnageSecondaire', '\d+')
			->bind("personnageSecondaire.update")
			->method('GET|POST')
			->convert('personnageSecondaire', 'converter.personnageSecondaire:convert');
			
		return $controllers;
	}
}