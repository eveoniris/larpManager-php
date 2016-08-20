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
 * LarpManager\GenreControllerProvider
 * 
 * @author kevin
 *
 */
class GenreControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les genres
	 * Routes :
	 * 	- genre
	 * 	- genre.add
	 *  - genre.update
	 *  - genre.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\GenreController::indexAction')
			->bind("genre")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\GenreController::addAction')
			->bind("genre.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\GenreController::updateAction')
			->assert('index', '\d+')
			->bind("genre.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\GenreController::detailAction')
			->assert('index', '\d+')
			->bind("genre.detail")
			->method('GET');
		
		return $controllers;
	}
}