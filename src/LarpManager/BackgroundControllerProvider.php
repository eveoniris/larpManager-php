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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\BackgroundControllerProvider
 * 
 * @author kevin
 *
 */
class BackgroundControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour le background
	 * 
	 * @param Application $app
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$mustBeScenariste = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
				throw new AccessDeniedException();
			}
		};
		
		$mustBeUser = function(Request $request) use ($app) {
			if ( ! $app['user'] ) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Liste tous les background
		 */
		$controllers->match('/list','LarpManager\Controllers\BackgroundController::listAction')
			->bind("background.list")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Ajoute un background
		 */
		$controllers->match('/add','LarpManager\Controllers\BackgroundController::addAction')
			->bind("background.add")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Ajoute un background
		 */
		$controllers->match('/{background}/update','LarpManager\Controllers\BackgroundController::updateAction')
			->bind("background.update")
			->convert('background', 'converter.background:convert')
			->method('GET|POST')
			->before($mustBeScenariste);

		/**
		 * Détail d'un background
		 */
		$controllers->match('/{background}/detail','LarpManager\Controllers\BackgroundController::detailAction')
			->bind("background.detail")
			->convert('background', 'converter.background:convert')
			->method('GET')
			->before($mustBeScenariste);
			
		/**
		 * Suppression d'un background
		 */
		$controllers->match('/{background}/delete','LarpManager\Controllers\BackgroundController::deleteAction')
			->bind("background.delete")
			->convert('background', 'converter.background:convert')
			->method('GET|POST')
			->before($mustBeScenariste);
			
		return $controllers;
	}
}