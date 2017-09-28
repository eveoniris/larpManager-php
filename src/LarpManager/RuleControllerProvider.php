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
 * LarpManager\RuleControllerProvider
 * 
 * @author kevin
 *
 * role de base ROLE_REGLE
 */
class RuleControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les règles
	 * 
	 * Routes :
	 * 	- rules
	 * 	- rule.delete
	 *  - rule.document
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
		
		/** Page de gestion des règles */
		$controllers->match('/','LarpManager\Controllers\RuleController::listAction')
			->method('GET')
			->bind('rules');
		
		/** Ajout d'une règle */
		$controllers->match('/add','LarpManager\Controllers\RuleController::addAction')
			->method('GET|POST')
			->bind('rule.add')
			->before($mustBeRegle);
		
		/** détail d'une règle */
		$controllers->match('/{rule}/detail','LarpManager\Controllers\RuleController::detailAction')
			->assert('rule', '\d+')
			->convert('rule', 'converter.rule:convert')
			->method('GET')
			->bind('rule.detail');
			
		/** modification d'une règle */
		$controllers->match('/{rule}/update','LarpManager\Controllers\RuleController::updateAction')
			->assert('rule', '\d+')
			->convert('rule', 'converter.rule:convert')
			->method('GET|POST')
			->bind('rule.update')
			->before($mustBeRegle);
				
		/** suppression d'une règle */
		$controllers->match('/{rule}/delete','LarpManager\Controllers\RuleController::deleteAction')
			->assert('rule', '\d+')
			->convert('rule', 'converter.rule:convert')
			->method('GET|POST')
			->bind('rule.delete')
			->before($mustBeRegle);
		
		/** Téléchargement du document */
		$controllers->match('/{rule}/document','LarpManager\Controllers\RuleController::documentAction')
			->assert('rule', '\d+')
			->convert('rule', 'converter.rule:convert')
			->method('GET')
			->bind('rule.document');
					
		return $controllers;
	}
}