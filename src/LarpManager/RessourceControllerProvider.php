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
 * LarpManager\RessourceControllerProvider
 * 
 * @author kevin
 *
 */
class RessourceControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les ressources
	 * Routes :
	 * 	- ressource
	 * 	- ressource.add
	 *  - ressource.update
	 *  - ressource.detail
	 *  
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des ressources
		 */
		$controllers->match('/','LarpManager\Controllers\RessourceController::indexAction')
			->bind("ressource")
			->method('GET');
		
		/**
		 * Ajoute une ressource
		 */
		$controllers->match('/add','LarpManager\Controllers\RessourceController::addAction')
			->bind("ressource.add")
			->method('GET|POST');
		
		/**
		 * Mise à jour d'une ressource
		 */
		$controllers->match('/{index}/update','LarpManager\Controllers\RessourceController::updateAction')
			->assert('index', '\d+')
			->bind("ressource.update")
			->method('GET|POST');
		
		/**
		 * Detail d'une ressource
		 */
		$controllers->match('/{index}','LarpManager\Controllers\RessourceController::detailAction')
			->assert('index', '\d+')
			->bind("ressource.detail")
			->method('GET');
		
		/**
		 * Export d'une ressource au format CSV
		 */
		$controllers->match('/{index}/export','LarpManager\Controllers\RessourceController::detailExportAction')
			->assert('index', '\d+')
			->bind("ressource.detail.export")
			->method('GET');
		
		/**
		 * Export de la liste des ressources au format CSV
		 */
		$controllers->match('/export','LarpManager\Controllers\NiveauController::exportAction')
			->bind("ressource.export")
			->method('GET');
					
		return $controllers;
	}
}