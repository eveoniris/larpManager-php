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
 * LarpManager\TechnologieControllerProvider
 * @author kevin
 *
 */
class TechnologieControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les classes
	 * Routes :
	 * 	- technologie
	 * 	- technologie.add
	 *  - technologie.update
	 *  - technologie.detail
	 *  - technologie.delete
	 *  - technologie.document
	 *  - technologie.document.delete
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
		
		$controllers->match('/','LarpManager\Controllers\TechnologieController::indexAction')
			->bind("technologie")
			->method('GET')
			->before($mustBeScenariste);
		
		$controllers->match('/add','LarpManager\Controllers\TechnologieController::addAction')
			->bind("technologie.add")
			->method('GET|POST')
			->before($mustBeScenariste);
				
		$controllers->match('/{technologie}/update','LarpManager\Controllers\TechnologieController::updateAction')
			->assert('technologie', '\d+')
			->convert('technologie', 'converter.technologie:convert')
			->bind("technologie.update")
			->method('GET|POST')
			->before($mustBeScenariste);
		
		$controllers->match('/{technologie}','LarpManager\Controllers\TechnologieController::detailAction')
			->assert('technologie', '\d+')
			->convert('technologie', 'converter.technologie:convert')
			->bind("technologie.detail")
			->method('GET')
			->before($mustBeScenariste);
		
		$controllers->match('/{technologie}/delete','LarpManager\Controllers\TechnologieController::deleteAction')
			->assert('technologie', '\d+')
			->convert('technologie', 'converter.technologie:convert')
			->bind("technologie.delete")
			->method('GET|POST')
			->before($mustBeScenariste);
							
		$controllers->match('/{technologie}/personnages','LarpManager\Controllers\TechnologieController::personnagesAction')
			->assert('technologie', '\d+')
			->convert('technologie', 'converter.technologie:convert')
			->bind("technologie.personnages")
			->method('GET')
			->before($mustBeScenariste);
							
		return $controllers;
	}
}