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
 * LarpManager\MonnaieControllerProvider
 *
 * @author kevin
 *
 * role de base : ROLE_SCENARISTE
 */
class MonnaieControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour le background
	 *
	 * @param Application $app
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
	
		/**
		 * Liste toutes les monnaies
		 */
		$controllers->match('/','LarpManager\Controllers\MonnaieController::listAction')
			->bind("monnaie")
			->method('GET');
			
		/**
		 * Ajoute une monnaie
		 */
		$controllers->match('/add','LarpManager\Controllers\MonnaieController::addAction')
			->bind("monnaie.add")
			->method('GET|POST');
			
		/**
		 * Met à jour une monnaie
		 */
		$controllers->match('/{monnaie}/update','LarpManager\Controllers\MonnaieController::updateAction')
			->bind("monnaie.update")
			->assert('monnaie', '\d+')
			->convert('monnaie', 'converter.monnaie:convert')
			->method('GET|POST');
	
		/**
		 * Détail d'une monnaie
		 */
		$controllers->match('/{monnaie}/detail','LarpManager\Controllers\MonnaieController::detailAction')
			->bind("monnaie.detail")
			->assert('monnaie', '\d+')
			->convert('monnaie', 'converter.monnaie:convert')
			->method('GET');
			
		/**
		 * Suppression d'une monnaie
		 */
		$controllers->match('/{monnaie}/delete','LarpManager\Controllers\MonnaieController::deleteAction')
			->bind("monnaie.delete")
			->assert('monnaie', '\d+')
			->convert('monnaie', 'converter.monnaie:convert')
			->method('GET|POST');
			
		return $controllers;
	}
}