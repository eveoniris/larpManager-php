<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\ReligionControllerProvider
 * 
 * @author kevin
 *
 */
class ReligionControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les religions
	 * Routes :
	 * 	- religion
	 *  - religion.list
	 * 	- religion.add
	 *  - religion.update
	 *  - religion.detail
	 *  - religion.level
	 *  - religion.level.add
	 *  - religion.level.update
	 *  - religion.level.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Vérifie que l'utilisateur dispose du role SCENARISTE
		 */
		$mustBeOrga = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
				throw new AccessDeniedException();
			}
		};
		
		$controllers->match('/','LarpManager\Controllers\ReligionController::indexAction')
			->bind("religion")
			->method('GET')
			->before($mustBeOrga);
		
		$controllers->match('/list','LarpManager\Controllers\ReligionController::listAction')
			->bind("religion.list")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\ReligionController::addAction')
			->bind("religion.add")
			->method('GET|POST')
			->before($mustBeOrga);
		
		$controllers->match('/{index}/update','LarpManager\Controllers\ReligionController::updateAction')
			->assert('index', '\d+')
			->bind("religion.update")
			->method('GET|POST')
			->before($mustBeOrga);
		
		$controllers->match('/{religion}/update/blason','LarpManager\Controllers\ReligionController::updateBlasonAction')
			->assert('religion', '\d+')
			->bind("religion.update.blason")
			->convert('religion', 'converter.religion:convert')
			->method('GET|POST')
			->before($mustBeOrga);			
		
		$controllers->match('/{index}','LarpManager\Controllers\ReligionController::detailAction')
			->assert('index', '\d+')
			->bind("religion.detail")
			->method('GET')
			->before($mustBeOrga);
		
		$controllers->match('/level','LarpManager\Controllers\ReligionController::levelIndexAction')
			->bind("religion.level")
			->method('GET')
			->before($mustBeOrga);
		
		$controllers->match('/level/add','LarpManager\Controllers\ReligionController::levelAddAction')
			->bind("religion.level.add")
			->method('GET|POST')
			->before($mustBeOrga);

		$controllers->match('/level/{index}/update','LarpManager\Controllers\ReligionController::levelUpdateAction')
			->assert('index', '\d+')
			->bind("religion.level.update")
			->method('GET|POST')
			->before($mustBeOrga);
		
		$controllers->match('/level/{index}','LarpManager\Controllers\ReligionController::levelDetailAction')
			->assert('index', '\d+')
			->bind("religion.level.detail")
			->method('GET')
			->before($mustBeOrga);
			
		return $controllers;
	}
}