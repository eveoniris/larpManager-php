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
class CompetenceControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les competences
	 * Routes :
	 * 	- competence.list
	 * 	- competence.add
	 *  - competence.update
	 *  - competence.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Vérifie que l'utilisateur dispose du role REGLE
		 */
		$mustBeRegle = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_REGLE')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Liste des competences
		 */
		$controllers->match('/','LarpManager\Controllers\CompetenceController::indexAction')
			->bind("competence")
			->method('GET')
			;
			
		/**
		 * Liste du métériel necessaire pour les compétences
		 */
		$controllers->match('/materiel','LarpManager\Controllers\CompetenceController::materielAction')
			->bind("competence.materiel")
			->method('GET')
			->before($mustBeRegle);
		
		/**
		 * Obtenir un document lié à une compétence
		 */
		$controllers->match('{competence}/document/{document}','LarpManager\Controllers\CompetenceController::getDocumentAction')
			->bind("competence.document")
			->convert('competence', 'converter.competence:convert')
			->method('GET')
			->before($mustBeRegle);

		/**
		 * Retirer un document lié à une compétence
		 */
		$controllers->match('{competence}/remove/document','LarpManager\Controllers\CompetenceController::removeDocumentAction')
			->bind("competence.document.remove")
			->convert('competence', 'converter.competence:convert')
			->method('GET')
			->before($mustBeRegle);
			
		/**
		 * Ajout d'une compétence
		 */
		$controllers->match('/add','LarpManager\Controllers\CompetenceController::addAction')
			->bind("competence.add")
			->method('GET|POST')
			->before($mustBeRegle);
		
		/**
		 * Modification d'une compétence
		 */
		$controllers->match('/{competence}/update','LarpManager\Controllers\CompetenceController::updateAction')
			->assert('competence', '\d+')
			->bind("competence.update")
			->method('GET|POST')
			->convert('competence', 'converter.competence:convert')
			->before($mustBeRegle);
		
		/**
		 * Suppression d'une compétence
		 */
		$controllers->match('/{competence}/delete','LarpManager\Controllers\CompetenceController::deleteAction')
			->assert('competence', '\d+')
			->bind("competence.delete")
			->method('GET|POST')
			->convert('competence', 'converter.competence:convert')
			->before($mustBeRegle);

		/**
		 * Detail d'une compétence
		 */
		$controllers->match('/{competence}','LarpManager\Controllers\CompetenceController::detailAction')
			->assert('index', '\d+')
			->bind("competence.detail")
			->method('GET')
			->convert('competence', 'converter.competence:convert')
			->before($mustBeRegle);
			
		return $controllers;
	}
}