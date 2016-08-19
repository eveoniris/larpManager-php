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
 * LarpManager\RestrictionControllerProvider
 * 
 * @author kevin
 *
 * Role de base : ROLE_ADMIN
 */
class RestrictionControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes la gestion des restrictions alimentaires
	 * Routes :
	 * 	- restriction.list
	 * 	- restriction.print
	 * 	- restriction.download 
	 * 	- restriction.add
	 *  - restriction.update
	 *  - restriction.detail
	 *  - restriction.delete
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
		$controllers->match('/','LarpManager\Controllers\RestrictionController::listAction')
			->bind("restriction.list")
			->method('GET');
			
		/**
		 * Imprimer la liste des restrictions
		 */
		$controllers->match('/print','LarpManager\Controllers\RestrictionController::printAction')
			->bind("restriction.print")
			->method('GET');
				
		/**
		 * Télécharger la liste des restrictions
		 */
		$controllers->match('/download','LarpManager\Controllers\RestrictionController::downloadAction')
			->bind("restriction.download")
			->method('GET');
		
		/**
		 * Ajoute une restriction
		 */
		$controllers->match('/add','LarpManager\Controllers\RestrictionController::addAction')
			->bind("restriction.add")
			->method('GET|POST');

		/**
		 * Mise à jour d'une restriction
		 */
		$controllers->match('/{restriction}/update','LarpManager\Controllers\ResrtictionController::updateAction')
			->assert('restriction', '\d+')
			->convert('restriction', 'converter.restriction:convert')
			->bind("restriction.update")
			->method('GET|POST');

		/**
		 * Detail d'une restriction
		 */
		$controllers->match('/{restriction}','LarpManager\Controllers\RestrictionController::detailAction')
			->assert('restriction', '\d+')
			->convert('restriction', 'converter.restriction:convert')
			->bind("restriction.detail")
			->method('GET');

		/**
		 * Supression d'une restriction
		 */
		$controllers->match('/{restriction}/delete','LarpManager\Controllers\RestrictionController::deleteAction')
			->assert('restriction', '\d+')
			->convert('restriction', 'converter.restriction:convert')
			->bind("restriction.delete")
			->method('GET|POST');
			
		return $controllers;
	}
}