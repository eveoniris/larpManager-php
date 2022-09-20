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
 * LarpManager\ConstructionControllerProvider
 * 
 * @author kevin
 *
 */
class ConstructionControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les constructions
	 * Routes :
	 * 	- construction
	 * 	- construction.add
	 *  - construction.update
	 *  - constructino.detail
	 *  - constructino.delete
	 *  
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des construction
		 */
		$controllers->match('/','LarpManager\Controllers\ConstructionController::indexAction')
			->bind("construction")
			->method('GET');
		
		/**
		 * Ajoute une construction
		 */
		$controllers->match('/add','LarpManager\Controllers\ConstructionController::addAction')
			->bind("construction.add")
			->method('GET|POST');

		/**
		 * Mise à jour d'une construciton
		 */
		$controllers->match('/{construction}/update','LarpManager\Controllers\ConstructionController::updateAction')
			->assert('construction', '\d+')
			->convert('construction', 'converter.construction:convert')
			->bind("construction.update")
			->method('GET|POST');

		/**
		 * Detail d'une construction
		 */
		$controllers->match('/{construction}','LarpManager\Controllers\ConstructionController::detailAction')
			->assert('construction', '\d+')
			->convert('construction', 'converter.construction:convert')
			->bind("construction.detail")
			->method('GET');

		/**
		 * Supression d'une construction
		 */
		$controllers->match('/{construction}/delete','LarpManager\Controllers\ConstructionController::deleteAction')
			->assert('construction', '\d+')
			->convert('construction', 'converter.construction:convert')
			->bind("construction.delete")
			->method('GET|POST');

		$controllers->match('/{construction}/territoires','LarpManager\Controllers\ConstructionController::personnagesAction')
			->assert('construction', '\d+')
			->convert('construction', 'converter.construction:convert')
			->bind("construction.territoires")
			->method('GET');

		return $controllers;
	}
}