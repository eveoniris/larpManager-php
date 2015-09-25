<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\ClasseControllerProvider
 * @author kevin
 *
 */
class ClasseControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les classes
	 * Routes :
	 * 	- classe
	 * 	- classe.add
	 *  - classe.update
	 *  - classe.detail
	 *  - classe.detail.export
	 *  - classe.export
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Vérifie que l'utilisateur dispose du role REGLE
		 */
		$mustBeOrga = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_REGLE')) {
				throw new AccessDeniedException();
			}
		};
		
		$controllers->match('/','LarpManager\Controllers\ClasseController::indexAction')
			->bind("classe")
			->method('GET')
			->before($mustBeOrga);
		
		$controllers->match('/list','LarpManager\Controllers\ClasseController::listAction')
			->bind("classe.list")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\ClasseController::addAction')
			->bind("classe.add")
			->method('GET|POST')
			->before($mustBeOrga);
				
		$controllers->match('/{index}/update','LarpManager\Controllers\ClasseController::updateAction')
			->assert('index', '\d+')
			->bind("classe.update")
			->method('GET|POST')
			->before($mustBeOrga);
		
		$controllers->match('/{index}','LarpManager\Controllers\ClasseController::detailAction')
			->assert('index', '\d+')
			->bind("classe.detail")
			->method('GET')
			->before($mustBeOrga);
		
		$controllers->match('/{index}/export','LarpManager\Controllers\ClasseController::detailExportAction')
			->assert('index', '\d+')
			->bind("classe.detail.export")
			->method('GET')
			->before($mustBeOrga);
		
		$controllers->match('/export','LarpManager\Controllers\ClasseController::exportAction')
			->bind("classe.export")
			->method('GET')
			->before($mustBeOrga);
					
		return $controllers;
	}
}