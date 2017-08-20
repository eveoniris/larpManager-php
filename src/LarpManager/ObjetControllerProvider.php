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
 * LarpManager\ObjetControllerProvider
 * 
 * @author kevin
 *
 */
class ObjetControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour l'éconnomie
	 * Routes :
	 * 	- objet
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des objets de jeu
		 */
		$controllers->match('/','LarpManager\Controllers\ObjetController::indexAction')
			->bind("items")
			->method('GET');
			
		/**
		 * Imprimer les étiquettes des objets de jeu
		 */
		$controllers->match('/print','LarpManager\Controllers\ObjetController::printAllAction')
			->bind("items.print")
			->method('GET');
			
		/**
		 * Imprimer les étiquettes des objets de jeu
		 */
		$controllers->match('/printPhoto','LarpManager\Controllers\ObjetController::printPhotoAction')
			->bind("items.print.photo")
			->method('GET');
			
		/**
		 * Imprimer le fichier CSV avec tous les objets de jeu
		 */
		$controllers->match('/print.csv','LarpManager\Controllers\ObjetController::printCsvAction')
			->bind("items.print.csv")
			->method('GET');
			
		/**
		 * Imprimer les étiquettes des objets de jeu
		 */
		$controllers->match('/{item}/print','LarpManager\Controllers\ObjetController::printAction')
			->assert('item', '\d+')
			->convert('item', 'converter.item:convert')
			->bind("item.print")
			->method('GET');
		
		/**
		 * Création d'un objet de jeu
		 */
		$controllers->match('/new/{objet}','LarpManager\Controllers\ObjetController::newAction')
			->assert('objet', '\d+')
			->convert('objet', 'converter.objet:convert')
			->bind("item.new")
			->method('GET|POST');
		
		/**
		 * Détail d'un objet de jeu
		 */
		$controllers->match('/{item}/detail','LarpManager\Controllers\ObjetController::detailAction')
			->assert('item', '\d+')
			->convert('item', 'converter.item:convert')
			->bind("item.detail")
			->method('GET');
			
		/**
		 * Mise à jour d'un objet de jeu
		 */
		$controllers->match('/{item}/update','LarpManager\Controllers\ObjetController::updateAction')
			->assert('item', '\d+')
			->convert('item', 'converter.item:convert')
			->bind("item.update")
			->method('GET|POST');
					
		/**
		 * Suppression d'un objet de jeu
		 */
		$controllers->match('/{item}/delete','LarpManager\Controllers\ObjetController::deleteAction')
			->assert('item', '\d+')
			->convert('item', 'converter.item:convert')
			->bind("item.delete")
			->method('GET|POST');
			
		return $controllers;
	}
}