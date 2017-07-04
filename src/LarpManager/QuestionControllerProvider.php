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
 * LarpManager\QuestionControllerProvider
 * 
 * @author kevin
 *
 */
class QuestionControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les questions
	 * Routes :
	 * 	- question
	 * 	- question.add
	 *  - question.update
	 *  - question.detail
	 *  - question.delete
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
		 * Vérifie que l'utilisateur dispose du role ADMIN
		 */
		$mustBeAdmin = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Lister les questions
		 */
		$controllers->match('/','LarpManager\Controllers\QuestionController::indexAction')
			->bind('question')
			->method('GET|POST')
			->before($mustBeAdmin);
		
		/**
		 * Créer une question
		 */
		$controllers->match('/add','LarpManager\Controllers\QuestionController::addAction')
			->bind('question.add')
			->method('GET|POST')
			->before($mustBeAdmin);
			
		/**
		 * Modifier une question
		 */
		$controllers->match('/{question}/update','LarpManager\Controllers\QuestionController::updateAction')
			->assert('question', '\d+')
			->bind('question.update')
			->method('GET|POST')
			->convert('question', 'converter.question:convert')
			->before($mustBeAdmin);
			
		/**
		 * Lire une question
		 */
		$controllers->match('/{question}','LarpManager\Controllers\QuestionController::detailAction')
			->assert('question', '\d+')
			->bind('question.detail')
			->method('GET')
			->convert('question', 'converter.question:convert')
			->before($mustBeAdmin);			
			
		/**
		 * Supprimer une question
		 */
		$controllers->match('/{question}/delete','LarpManager\Controllers\QuestionController::deleteAction')
			->assert('question', '\d+')
			->bind('question.delete')
			->method('GET|POST')
			->convert('question', 'converter.question:convert')
			->before($mustBeAdmin);
		
		return $controllers;
	}
}
		