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
 * LarpManager\RestaurationControllerProvider
 * 
 * @author kevin
 *
 * Role de base : ROLE_ADMIN
 */
class RestaurationControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes la gestion des restaurations alimentaires
	 * Routes :
	 * 	- restauration.list
	 * 	- restauration.print
	 * 	- restauration.download
	 *  - restauration.user 
	 * 	- restauration.add
	 *  - restauration.update
	 *  - restauration.detail
	 *  - restauration.delete
	 *  
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des lieus
		 */
		$controllers->match('/','LarpManager\Controllers\RestaurationController::listAction')
			->bind("restauration.list")
			->method('GET');
			
		/**
		 * Imprimer la liste des restaurations
		 */
		$controllers->match('/print','LarpManager\Controllers\RestaurationController::printAction')
			->bind("restauration.print")
			->method('GET');
				
		/**
		 * Télécharger la liste des restaurations
		 */
		$controllers->match('/download','LarpManager\Controllers\RestaurationController::downloadAction')
			->bind("restauration.download")
			->method('GET');
			
		/**
		 * Liste des utilisateurs d'un lieu de restauration
		 */
		$controllers->match('/{restauration}/users','LarpManager\Controllers\RestaurationController::usersAction')
			->bind("restauration.users")
			->assert('restauration', '\d+')
			->convert('restauration', 'converter.restauration:convert')
			->method('GET');
			
		/**
		 * Liste des restrictions alimentaires d'un lieu de restauration
		 */
		$controllers->match('/{restauration}/restrictions','LarpManager\Controllers\RestaurationController::restrictionsAction')
			->bind("restauration.restrictions")
			->assert('restauration', '\d+')
			->convert('restauration', 'converter.restauration:convert')
			->method('GET');
		
		/**
		 * Exporter la liste des utilisateurs d'un lieu de restauration
		 */
		$controllers->match('/{restauration}/users/export','LarpManager\Controllers\RestaurationController::usersExportAction')
			->bind("restauration.users.export")
			->assert('restauration', '\d+')
			->convert('restauration', 'converter.restauration:convert')
			->method('GET');
			
		/**
		 * Ajoute une restauration
		 */
		$controllers->match('/add','LarpManager\Controllers\RestaurationController::addAction')
			->bind("restauration.add")
			->method('GET|POST');

		/**
		 * Mise à jour d'une restauration
		 */
		$controllers->match('/{restauration}/update','LarpManager\Controllers\RestaurationController::updateAction')
			->assert('restauration', '\d+')
			->convert('restauration', 'converter.restauration:convert')
			->bind("restauration.update")
			->method('GET|POST');

		/**
		 * Detail d'une restauration
		 */
		$controllers->match('/{restauration}','LarpManager\Controllers\RestaurationController::detailAction')
			->assert('restauration', '\d+')
			->convert('restauration', 'converter.restauration:convert')
			->bind("restauration.detail")
			->method('GET');

		/**
		 * Supression d'une restauration
		 */
		$controllers->match('/{restauration}/delete','LarpManager\Controllers\RestaurationController::deleteAction')
			->assert('restauration', '\d+')
			->convert('restauration', 'converter.restauration:convert')
			->bind("restauration.delete")
			->method('GET|POST');
			
		return $controllers;
	}
}