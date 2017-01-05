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
 * LarpManager\CompetenceControllerProvider
 * 
 * @author kevin
 *
 */
class IntrigueControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les intrigues
	 * Routes :
	 * 	- intrigue.list
	 * 	- intrigue.add
	 *  - intrigue.update
	 *  - intrigue.detail
	 *  - intrigue.delete
	 *  - intrigue.relecture.add
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Vérifie que l'utilisateur dispose du role SCENARISTE
		 */
		$mustBeScenariste = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Lister les intrigues
		 */
		$controllers->match('/','LarpManager\Controllers\IntrigueController::listAction')
			->bind('intrigue.list')
			->method('GET|POST')
			->before($mustBeScenariste);
		
		/**
		 * Créer une intrigue
		 */
		$controllers->match('/add','LarpManager\Controllers\IntrigueController::addAction')
			->bind('intrigue.add')
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Modifier une intrigue
		 */
		$controllers->match('/{intrigue}/update','LarpManager\Controllers\IntrigueController::updateAction')
			->assert('intrigue', '\d+')
			->bind('intrigue.update')
			->method('GET|POST')
			->convert('intrigue', 'converter.intrigue:convert')
			->before($mustBeScenariste);
			
		/**
		 * Lire une intrigue
		 */
		$controllers->match('/{intrigue}','LarpManager\Controllers\IntrigueController::detailAction')
			->assert('intrigue', '\d+')
			->bind('intrigue.detail')
			->method('GET')
			->convert('intrigue', 'converter.intrigue:convert')
			->before($mustBeScenariste);			
			
		/**
		 * Supprimer une intrigue
		 */
		$controllers->match('/{intrigue}/delete','LarpManager\Controllers\IntrigueController::deleteAction')
			->assert('intrigue', '\d+')
			->bind('intrigue.delete')
			->method('GET|POST')
			->convert('intrigue', 'converter.intrigue:convert')
			->before($mustBeScenariste);
			
		/**
		 * Ajouter une relecture
		 */
		$controllers->match('/{intrigue}/relecture/add','LarpManager\Controllers\IntrigueController::relectureAddAction')
			->assert('intrigue', '\d+')
			->bind('intrigue.relecture.add')
			->method('GET|POST')
			->convert('intrigue', 'converter.intrigue:convert')
			->before($mustBeScenariste);
		 
		
		return $controllers;
	}
}
		