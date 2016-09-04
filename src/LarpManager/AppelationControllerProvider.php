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
 * LarpManager\AppelationControllerProvider
 * 
 * @author kevin
 */
class AppelationControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les appelations
	 * Routes :
	 * 	- appelation
	 * 	- appelation.add
	 *  - appelation.update
	 *  - appelation.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des appelations
		 */
		$controllers->match('/','LarpManager\Controllers\AppelationController::indexAction')
			->bind("appelation")
			->method('GET');
		
		/**
		 * Ajouter une appelation
		 */
		$controllers->match('/add','LarpManager\Controllers\AppelationController::addAction')
			->bind("appelation.add")
			->method('GET|POST');
		
		/**
		 * Mise à jour d'une appelation
		 */
		$controllers->match('/{appelation}/update','LarpManager\Controllers\AppelationController::updateAction')
			->assert('appelation', '\d+')
			->convert('appelation', 'converter.appelation:convert')
			->bind("appelation.update")
			->method('GET|POST');
		
		/**
		 * Détail d'une appelation
		 */
		$controllers->match('/{appelation}','LarpManager\Controllers\AppelationController::detailAction')
			->assert('appelation', '\d+')
			->convert('appelation', 'converter.appelation:convert')
			->bind("appelation.detail")
			->method('GET');
					
		return $controllers;
	}
}