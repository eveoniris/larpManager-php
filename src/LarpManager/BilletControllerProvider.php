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
 * LarpManager\BackgroundControllerProvider
 *
 * @author kevin
 *
 * role de base : ROLE_ADMIN
 */
class BilletControllerProvider implements ControllerProviderInterface
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
		 * Liste tous les billets
		 */
		$controllers->match('/list','LarpManager\Controllers\BilletController::listAction')
		->bind("billet.list")
		->method('GET');
			
		/**
		 * Ajoute un billet
		 */
		$controllers->match('/add','LarpManager\Controllers\BilletController::addAction')
			->bind("billet.add")
			->method('GET|POST');
			
		/**
		 * Ajoute un billet
		 */
		$controllers->match('/{billet}/update','LarpManager\Controllers\BilletController::updateAction')
			->bind("billet.update")
			->convert('billet', 'converter.billet:convert')
			->method('GET|POST');

		/**
		 * Détail d'un billet
		 */
		$controllers->match('/{billet}/detail','LarpManager\Controllers\BilletController::detailAction')
			->bind("billet.detail")
			->convert('billet', 'converter.billet:convert')
			->method('GET');
			
		/**
		 * Suppression d'un background
		 */
		$controllers->match('/{billet}/delete','LarpManager\Controllers\BilletController::deleteAction')
			->bind("billet.delete")
			->convert('billet', 'converter.billet:convert')
			->method('GET|POST');
			
		/**
		 * Liste des utilisateurs ayant ce billet
		 */
		$controllers->match('/{billet}/user','LarpManager\Controllers\BilletController::userAction')
			->bind("billet.user")
			->convert('billet', 'converter.billet:convert')
			->method('GET');
			
		return $controllers;
	}
}