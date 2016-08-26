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
 * Role de base : ROLE_ADMIN
 * 
 * @author kevin
 */
class AnnonceControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les annonces
	 * 
	 * Routes :
	 * 	- annonce
	 * 	- annonce.add
	 *  - annonce.update
	 *  - annonce.detail
	 *  - annonce.delete
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des annnonces
		 */
		$controllers->match('/list','LarpManager\Controllers\AnnonceController::listAction')
			->bind("annonce.list")
			->method('GET');
		
		/**
		 * Ajouter une annonce
		 */
		$controllers->match('/add','LarpManager\Controllers\AnnonceController::addAction')
			->bind("annonce.add")
			->method('GET|POST');
		
		/**
		 * Mise à jour d'une annonce
		 */
		$controllers->match('/{annonce}/update','LarpManager\Controllers\AnnonceController::updateAction')
			->assert('annonce', '\d+')
			->convert('annonce', 'converter.annonce:convert')
			->bind("annonce.update")
			->method('GET|POST');
		
		/**
		 * Détail d'une annonce
		 */
		$controllers->match('/{annonce}','LarpManager\Controllers\AnnonceController::detailAction')
			->assert('annonce', '\d+')
			->convert('annonce', 'converter.annonce:convert')
			->bind("annonce.detail")
			->method('GET');
					
		/**
		 * Suppression d'une annonce
		 */
		$controllers->match('/{annonce}/delete','LarpManager\Controllers\AnnonceController::deleteAction')
			->assert('annonce', '\d+')
			->convert('annonce', 'converter.annonce:convert')
			->bind("annonce.delete")
			->method('GET|POST');
			
		return $controllers;
	}
}