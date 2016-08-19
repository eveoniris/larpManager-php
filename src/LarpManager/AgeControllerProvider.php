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
 * LarpManager\AgeControllerProvider
 * 
 * @author kevin
 */
class AgeControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les ages
	 * Routes :
	 * 	- age
	 * 	- age.add.view
	 * 	- age.add.post
	 *  - age.update.view
	 *  - age.update.post
	 *  - age.detail
	 *   
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		// liste des ages
		$controllers->match('/','LarpManager\Controllers\AgeController::indexAction')
			->bind("age")
			->method('GET');
		
		// formulaire d'ajout d'un age
		$controllers->match('/add','LarpManager\Controllers\AgeController::addViewAction')
			->bind("age.add.view")
			->method('GET');
		
		// traitement d'une requête d'ajout d'un age
		$controllers->match('/add','LarpManager\Controllers\AgeController::addPostAction')
			->bind("age.add.post")
			->method('POST');
			
		// liste des perso par age
		$controllers->match('/{age}/perso','LarpManager\Controllers\AgeController::persoAction')
			->bind("age.perso")
			->convert('age', 'converter.age:convert')
			->method('GET');
		
		// formulaire de modification d'un age
		$controllers->match('/{index}/update','LarpManager\Controllers\AgeController::updateViewAction')
			->assert('index', '\d+')
			->bind("age.update.view")
			->method('GET');
		
		// traitement d'une requête de modification d'un age
		$controllers->match('/{index}/update','LarpManager\Controllers\AgeController::updatePostAction')
			->assert('index', '\d+')
			->bind("age.update.post")
			->method('POST');
		
		// Affichage du détail d'un age
		$controllers->match('/{index}','LarpManager\Controllers\AgeController::detailAction')
			->assert('index', '\d+')
			->bind("age.detail")
			->method('GET');
		
		return $controllers;
	}
}