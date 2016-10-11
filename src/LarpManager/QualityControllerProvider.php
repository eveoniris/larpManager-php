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
 * LarpManager\QualityControllerProvider
 *
 * @author kevin
 *
 * role de base : ROLE_SCENARISTE
 */
class QualityControllerProvider implements ControllerProviderInterface
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
		 * Liste tous les quality
		 */
		$controllers->match('/','LarpManager\Controllers\QualityController::listAction')
			->bind("quality")
			->method('GET');
			
		/**
		 * Ajoute une quality
		 */
		$controllers->match('/add','LarpManager\Controllers\QualityController::addAction')
			->bind("quality.add")
			->method('GET|POST');
			
		/**
		 * Met à jour une quality
		 */
		$controllers->match('/{quality}/update','LarpManager\Controllers\QualityController::updateAction')
			->bind("quality.update")
			->assert('quality', '\d+')
			->convert('quality', 'converter.quality:convert')
			->method('GET|POST');
	
		/**
		 * Détail d'une quality
		 */
		$controllers->match('/{quality}/detail','LarpManager\Controllers\QualityController::detailAction')
			->bind("quality.detail")
			->assert('quality', '\d+')
			->convert('quality', 'converter.quality:convert')
			->method('GET');
			
		/**
		 * Suppression d'une quality
		 */
		$controllers->match('/{quality}/delete','LarpManager\Controllers\QualityController::deleteAction')
			->bind("quality.delete")
			->assert('quality', '\d+')
			->convert('quality', 'converter.quality:convert')
			->method('GET|POST');
			
		return $controllers;
	}
}