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

/**
 * LarpManager\MessageControllerProvider
 * 
 * @author kevin
 *
 */
class MessageControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les lieus
	 * Routes :
	 * 	- message.new
	 * 	- message.archive
	 *  - message.list.archive
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		/**
		 * Vue de la messagerie d'un utilisateur
		 */
		$controllers->match('/', 'LarpManager\Controllers\MessageController::messagerieAction')
			->bind('messagerie')
			->method('GET');

		/**
		 * Archiver un message
		 */
		$controllers->match('/{message}/archive', 'LarpManager\Controllers\MessageController::messageArchiveAction')
			->bind('message.archive')
			->assert('message', '\d+')
			->convert('message', 'converter.message:convert')
			->method('GET');
			
		/**
		 * Répondre à un message
		 */
		$controllers->match('/{message}/response', 'LarpManager\Controllers\MessageController::messageResponseAction')
			->bind('message.response')
			->assert('message', '\d+')
			->convert('message', 'converter.message:convert')
			->method('GET|POST');
		
		/**
		 * Nouveau message
		 */
		$controllers->match('/new', 'LarpManager\Controllers\MessageController::newAction')
			->bind('message.new')
			->method('GET|POST');
			
		/**
		 * Liste des messages archivés
		 */
		$controllers->match('/archives', 'LarpManager\Controllers\MessageController::archiveAction')
			->bind('message.archives')
			->method('GET|POST');
			
		/**
		 * Liste des messages envoyés
		 */
		$controllers->match('/envoye', 'LarpManager\Controllers\MessageController::envoyeAction')
			->bind('message.envoye')
			->method('GET');
				
		return $controllers;
	}
}