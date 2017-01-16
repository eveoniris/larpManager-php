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
class RumeurControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les rumeurs
	 * Routes :
	 * 	- rumeur.list
	 * 	- rumeur.add
	 *  - rumeur.update
	 *  - rumeur.detail
	 *  - rumeur.delete
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
		 * Lister les rumeurs
		 */
		$controllers->match('/','LarpManager\Controllers\RumeurController::listAction')
			->bind('rumeur.list')
			->method('GET|POST')
			->before($mustBeScenariste);
		
		/**
		 * Créer une rumeur
		 */
		$controllers->match('/add','LarpManager\Controllers\RumeurController::addAction')
			->bind('rumeur.add')
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Modifier une rumeur
		 */
		$controllers->match('/{rumeur}/update','LarpManager\Controllers\RumeurController::updateAction')
			->assert('rumeur', '\d+')
			->bind('rumeur.update')
			->method('GET|POST')
			->convert('rumeur', 'converter.rumeur:convert')
			->before($mustBeScenariste);
			
		/**
		 * Lire une rumeur
		 */
		$controllers->match('/{rumeur}','LarpManager\Controllers\RumeurController::detailAction')
			->assert('rumeur', '\d+')
			->bind('rumeur.detail')
			->method('GET')
			->convert('rumeur', 'converter.rumeur:convert')
			->before($mustBeScenariste);			
			
		/**
		 * Supprimer une rumeur
		 */
		$controllers->match('/{rumeur}/delete','LarpManager\Controllers\RumeurController::deleteAction')
			->assert('rumeur', '\d+')
			->bind('rumeur.delete')
			->method('GET|POST')
			->convert('rumeur', 'converter.rumeur:convert')
			->before($mustBeScenariste);
					 
		
		return $controllers;
	}
}
		