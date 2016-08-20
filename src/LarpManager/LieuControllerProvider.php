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
 * LarpManager\LieuControllerProvider
 * 
 * @author kevin
 *
 */
class LieuControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les lieus
	 * Routes :
	 * 	- lieu
	 * 	- lieu.add
	 *  - lieu.update
	 *  - lieu.detail
	 *  - lieu.delete
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
		$controllers->match('/','LarpManager\Controllers\LieuController::indexAction')
			->bind("lieu")
			->method('GET');
			
		/**
		 * Imprimer la liste des lieux
		 */
		$controllers->match('/print','LarpManager\Controllers\LieuController::printAction')
			->bind("lieu.print")
			->method('GET');
				
		/**
		 * Télécharger la liste des lieux
		 */
		$controllers->match('/download','LarpManager\Controllers\LieuController::downloadAction')
			->bind("lieu.download")
			->method('GET');
		
		/**
		 * Ajoute une construction
		 */
		$controllers->match('/add','LarpManager\Controllers\LieuController::addAction')
			->bind("lieu.add")
			->method('GET|POST');

		/**
		 * Mise à jour d'un lieu
		 */
		$controllers->match('/{lieu}/update','LarpManager\Controllers\LieuController::updateAction')
			->assert('lieu', '\d+')
			->convert('lieu', 'converter.lieu:convert')
			->bind("lieu.update")
			->method('GET|POST');

		/**
		 * Detail d'un lieu
		 */
		$controllers->match('/{lieu}','LarpManager\Controllers\LieuController::detailAction')
			->assert('lieu', '\d+')
			->convert('lieu', 'converter.lieu:convert')
			->bind("lieu.detail")
			->method('GET');

		/**
		 * Supression d'un lieu
		 */
		$controllers->match('/{lieu}/delete','LarpManager\Controllers\LieuController::deleteAction')
			->assert('lieu', '\d+')
			->convert('lieu', 'converter.lieu:convert')
			->bind("lieu.delete")
			->method('GET|POST');

		/**
		 * Gestion des documents lié au lieu
		 */
		$controllers->match('/{lieu}/documents','LarpManager\Controllers\LieuController::documentAction')
			->bind("lieu.documents")
			->convert('lieu', 'converter.lieu:convert')
			->method('GET|POST');
			
		return $controllers;
	}
}