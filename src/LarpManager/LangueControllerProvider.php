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
 * LarpManager\LangueControllerProvider
 * 
 * @author kevin
 *
 */
class LangueControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les langues
	 * Routes :
	 * 	- langue
	 * 	- langue.add
	 *  - langue.update
	 *  - langue.detail
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
		$mustBeScenariste = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
				throw new AccessDeniedException();
			}
		};

		$controllers->match('/','LarpManager\Controllers\LangueController::indexAction')
			->bind("langue")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\LangueController::addAction')
			->bind("langue.add")
			->method('GET|POST')
			->before($mustBeScenariste);
		
		$controllers->match('/{index}/update','LarpManager\Controllers\LangueController::updateAction')
			->assert('index', '\d+')
			->bind("langue.update")
			->method('GET|POST')
			->before($mustBeScenariste);
		
		$controllers->match('/{index}','LarpManager\Controllers\LangueController::detailAction')
			->assert('index', '\d+')
			->bind("langue.detail")
			->method('GET');
		
		$controllers->match('/group/add','LarpManager\Controllers\LangueController::addGroupAction')
			->bind("langue.addGroup")
			->method('GET|POST')
			->before($mustBeScenariste);
		
		$controllers->match('/group/{index}/update','LarpManager\Controllers\LangueController::updateGroupAction')
			->assert('index', '\d+')
			->bind("langue.updateGroup")
			->method('GET|POST')
			->before($mustBeScenariste);
		
		$controllers->match('/group/{index}','LarpManager\Controllers\LangueController::detailGroupAction')
			->assert('index', '\d+')
			->bind("langue.detailGroup")
			->method('GET');

		$controllers->match('/admin/{langue}/documents','LarpManager\Controllers\LangueController::adminDocumentAction')
			->bind("langue.admin.document")
			->assert('langue', '\d+')
			->convert('langue', 'converter.langue:convert')
			->method('GET|POST')
			->before($mustBeScenariste);

		return $controllers;
	}
}