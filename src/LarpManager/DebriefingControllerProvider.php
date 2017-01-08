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
 * LarpManager\DebriefingControllerProvider
 * 
 * @author kevin
 *
 */
class DebriefingControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour le debriefing
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
		 * Liste tous les debriefing
		 */
		$controllers->match('/list','LarpManager\Controllers\DebriefingController::listAction')
			->bind("debriefing.list")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Ajoute un debriefing
		 */
		$controllers->match('/add','LarpManager\Controllers\DebriefingController::addAction')
			->bind("debriefing.add")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Ajoute un debriefing
		 */
		$controllers->match('/{debriefing}/update','LarpManager\Controllers\DebriefingController::updateAction')
			->bind("debriefing.update")
			->assert('debriefing', '\d+')
			->convert('debriefing', 'converter.debriefing:convert')
			->method('GET|POST')
			->before($mustBeScenariste);

		/**
		 * Détail d'un debriefing
		 */
		$controllers->match('/{debriefing}/detail','LarpManager\Controllers\DebriefingController::detailAction')
			->bind("debriefing.detail")
			->assert('debriefing', '\d+')
			->convert('debriefing', 'converter.debriefing:convert')
			->method('GET')
			->before($mustBeScenariste);
			
		/**
		 * Suppression d'un debriefing
		 */
		$controllers->match('/{debriefing}/delete','LarpManager\Controllers\DebriefingController::deleteAction')
			->bind("debriefing.delete")
			->assert('debriefing', '\d+')
			->convert('debriefing', 'converter.debriefing:convert')
			->method('GET|POST')
			->before($mustBeScenariste);
			
		return $controllers;
	}
}