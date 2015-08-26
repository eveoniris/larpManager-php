<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PersonnageControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
				
		$controllers->match('/add','LarpManager\Controllers\PersonnageController::addAction')
			->bind("personnage.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/detail','LarpManager\Controllers\PersonnageController::detailAction')
			->assert('index', '\d+')
			->bind("personnage.detail")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('OWN_PERSONNAGE', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/{index}/competence/add','LarpManager\Controllers\PersonnageController::addCompetenceAction')
			->assert('index', '\d+')
			->bind("personnage.competence.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('OWN_PERSONNAGE', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
					
		return $controllers;
	}
}
