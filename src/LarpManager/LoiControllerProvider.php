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
 * LarpManager\LoiControllerProvider
 * @author kevin
 *
 */
class LoiControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les classes
	 * Routes :
	 * 	- loi
	 * 	- loi.add
	 *  - loi.update
	 *  - loi.detail
	 *  - loi.delete
	 *  - loi.document
	 *  - loi.document.delete
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
		
		$controllers->match('/','LarpManager\Controllers\LoiController::indexAction')
			->bind("loi")
			->method('GET')
			->before($mustBeScenariste);
		
		$controllers->match('/add','LarpManager\Controllers\LoiController::addAction')
			->bind("loi.add")
			->method('GET|POST')
			->before($mustBeScenariste);
				
		$controllers->match('/{loi}/update','LarpManager\Controllers\LoiController::updateAction')
			->assert('loi', '\d+')
			->convert('loi', 'converter.loi:convert')
			->bind("loi.update")
			->method('GET|POST')
			->before($mustBeScenariste);
		
		$controllers->match('/{loi}','LarpManager\Controllers\LoiController::detailAction')
			->assert('loi', '\d+')
			->convert('loi', 'converter.loi:convert')
			->bind("loi.detail")
			->method('GET')
			->before($mustBeScenariste);
		
		$controllers->match('/{loi}/delete','LarpManager\Controllers\LoiController::deleteAction')
			->assert('loi', '\d+')
			->convert('loi', 'converter.loi:convert')
			->bind("loi.delete")
			->method('GET|POST')
			->before($mustBeScenariste);
		
		/**
		 * Obtenir un document lié à une loi
		 */
		$controllers->match('{loi}/document','LarpManager\Controllers\LoiController::getDocumentAction')
			->bind("loi.document")
			->convert('loi', 'converter.loi:convert')
			->method('GET');
			
		/**
		 * Retirer un document lié à une loi
		 */
		$controllers->match('{loi}/document/remove','LarpManager\Controllers\LoiController::removeDocumentAction')
			->bind("loi.document.remove")
			->convert('loi', 'converter.loi:convert')
			->method('GET')
			->before($mustBeScenariste);
							
		return $controllers;
	}
}