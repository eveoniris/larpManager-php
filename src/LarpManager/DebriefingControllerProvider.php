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
		 * Liste des debriefing
		 */
		$controllers->match('/','LarpManager\Controllers\DebriefingController::listAction')
			->bind("debriefing.list")
			->method('GET');

		/**
		 * Liste des debriefing
		 */
		$controllers->match('/print','LarpManager\Controllers\DebriefingController::printAction')
			->bind("debriefing.print")
			->method('GET');
			
		/**
		 * Liste des debriefing
		 */
		$controllers->match('/download','LarpManager\Controllers\DebriefingController::downloadAction')
			->bind("debriefing.download")
			->method('GET');
		
		/**
		 * Ajout d'un debriefing
		 */
		$controllers->match('/add','LarpManager\Controllers\DebriefingController::addAction')
			->bind("debriefing.add")
			->method('GET|POST');
		
		/**
		 * Mise à jour d'un debriefing
		 */
		$controllers->match('/{debriefing}/update','LarpManager\Controllers\DebriefingController::updateAction')
			->assert('debriefing', '\d+')
			->convert('debriefing', 'converter.debriefing:convert')
			->bind("debriefing.update")
			->method('GET|POST');
		
		/**
		 * Détail d'un debriefing
		 */
		$controllers->match('/{debriefing}','LarpManager\Controllers\DebriefingController::detailAction')
			->assert('debriefing', '\d+')
			->convert('debriefing', 'converter.debriefing:convert')
			->bind("debriefing.detail")
			->method('GET');
	
		/**
		 * Suppression d'un debriefing
		 */
		$controllers->match('/{debriefing}/delete','LarpManager\Controllers\DebriefingController::deleteAction')
			->assert('debriefing', '\d+')
			->convert('debriefing', 'converter.debriefing:convert')
			->bind("debriefing.delete")
			->method('GET|POST');

        /**
         * Affichage d'un document
         */
        $controllers->match('/admin/{debriefing}/document','LarpManager\Controllers\DebriefingController::documentAction')
            ->bind("debriefing.document")
            ->assert('debriefing', '\d+')
            ->convert('debriefing', 'converter.debriefing:convert')
            ->method('GET|POST')
            ->before($mustBeScenariste);
			
		return $controllers;
	}
}