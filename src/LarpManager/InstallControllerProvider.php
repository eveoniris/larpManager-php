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

/**
 * LarpManager\InstallControllerProvider
 * 
 * @author kevin
 *
 */
class InstallControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour le processus d'installation
	 * Routes :
	 * 	- install_index
	 * 	- install_create_user
	 *  - install_done
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\InstallController::indexAction')
			->bind("install_index")
			->method('GET|POST');
		
		$controllers->match('/update','LarpManager\Controllers\InstallController::updateAction')
			->bind("update")
			->method('GET|POST');			
		
		$controllers->match('/user/create','LarpManager\Controllers\InstallController::createUserAction')
			->bind("install_create_user")
			->method('GET|POST');
		
		$controllers->match('/done','LarpManager\Controllers\InstallController::doneAction')
			->bind("install_done")
			->method('GET');
		
		return $controllers;
	}
}