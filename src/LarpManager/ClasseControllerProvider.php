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
		$mustBeRegle = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_REGLE')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Affiche la liste des classes
		 */
		$controllers->match('/','LarpManager\Controllers\ClasseController::indexAction')
			->bind("classe")
			->method('GET');
		
		/**
		 * Ajoute une classe
		 */
		$controllers->match('/add','LarpManager\Controllers\ClasseController::addAction')
			->bind("classe.add")
			->method('GET|POST')
			->before($mustBeRegle);
				
		/**
		 * Met à jour une classe
		 */
		$controllers->match('/{classe}/update','LarpManager\Controllers\ClasseController::updateAction')
			->assert('index', '\d+')
			->bind("classe.update")
			->convert('classe', 'converter.classe:convert')
			->method('GET|POST')
			->before($mustBeRegle);
		
		/**
		 * Détail d'une classe
		 */
		$controllers->match('/{classe}','LarpManager\Controllers\ClasseController::detailAction')
			->assert('index', '\d+')
			->bind("classe.detail")
			->convert('classe', 'converter.classe:convert')
			->method('GET')
			->before($mustBeRegle);
					
		return $controllers;
	}
}