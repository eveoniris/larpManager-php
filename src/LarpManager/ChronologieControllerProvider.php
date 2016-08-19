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

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\ChronologieControllerProvider
 * 
 * @author kevin
 *
 */
class ChronologieControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les territoires
	 * Routes :
	 * 	- chrononologie
	 * 	- chrononologie.admin.add
	 * 	- chrononologie.admin.update
	 * 	- chrononologie.admin.remove
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];

		/**
		 * Vérifie que l'utilisateur dispose du role ORGA
		 */
		$mustBeOrga = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA')) {
				throw new AccessDeniedException();
			}
		};
		
		$controllers->match('/','LarpManager\Controllers\ChronologieController::indexAction')
					->bind("chronologie")
					->method('GET');
		
		$controllers->match('/admin/add','LarpManager\Controllers\ChronologieController::addAction')
					->bind("chronologie.admin.add")
					->method('GET|POST')
					->before($mustBeOrga);
		
		$controllers->match('/admin/{index}/update','LarpManager\Controllers\ChronologieController::updateAction')
					->bind("chronologie.admin.update")
					->method('GET|POST')
					->before($mustBeOrga);
		
		$controllers->match('/admin/{index}/remove','LarpManager\Controllers\ChronologieController::removeAction')
					->bind("chronologie.admin.remove")
					->method('GET|POST')
					->before($mustBeOrga);
		
		return $controllers;
	}
}