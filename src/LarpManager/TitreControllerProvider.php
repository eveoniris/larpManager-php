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
 * LarpManager\TitreControllerProvider
 * 
 * @author kevin
 *
 */
class TitreControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les genres
	 * Routes :
	 * 	- titre.admin.list
	 * 	- titre.admin.add
	 *  - titre.admin.update
	 *  - titre.admin.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\TitreController::adminListAction')
			->bind("titre.admin.list")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\TitreController::adminAddAction')
			->bind("titre.admin.add")
			->method('GET|POST');
		
		$controllers->match('/{titre}/update','LarpManager\Controllers\TitreController::adminUpdateAction')
			->assert('titre', '\d+')
			->convert('titre', 'converter.titre:convert')
			->bind("titre.admin.update")
			->method('GET|POST');
		
		$controllers->match('/{titre}/delete','LarpManager\Controllers\TitreController::adminDeleteAction')
			->assert('titre', '\d+')
			->convert('titre', 'converter.titre:convert')
			->bind("titre.admin.delete")
			->method('GET|POST');
		
		$controllers->match('/{titre}','LarpManager\Controllers\TitreController::adminDetailAction')
			->assert('titre', '\d+')
			->convert('titre', 'converter.titre:convert')
			->bind("titre.admin.detail")
			->method('GET');
		
		return $controllers;
	}
}