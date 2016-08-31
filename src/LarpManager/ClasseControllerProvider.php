<?php

/**
 * LarpManager - A Live Action Role Playing Manager
 * Copyright (C) 2016 Kevin Polez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
				
		$controllers->match('/{classe}/perso','LarpManager\Controllers\ClasseController::persoAction')
			->bind("classe.perso")
			->convert('classe', 'converter.classe:convert')
			->before($mustBeOrga)
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