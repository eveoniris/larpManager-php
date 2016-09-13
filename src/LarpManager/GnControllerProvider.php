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
 * LarpManager\GnControllerProvider
 * 
 * @author kevin
 * 
 * Role de base : ROLE_USER
 */
class GnControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les gns
	 * Routes :
	 * 	- gn
	 * 	- gn.add
	 *  - gn.update
	 *  - gn.detail
	 *  - gn.billetterie
	 *  - gn.participants
	 *  - gn.fedegn
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Vérifie que l'utilisateur dispose du role ADMIN
		 */
		$mustBeAdmin = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Voir la liste des gns
		 */
		$controllers->match('/','LarpManager\Controllers\GnController::listAction')
			->bind("gn.list")
			->method('GET')
			->before($mustBeAdmin);

		/**
		 * Ajouter un gn
		 */
		$controllers->match('/add','LarpManager\Controllers\GnController::addAction')
			->bind("gn.add")
			->method('GET|POST')
			->before($mustBeAdmin);

		/**
		 * Modifier un gn
		 */
		$controllers->match('/{gn}/update','LarpManager\Controllers\GnController::updateAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.update")
			->method('GET|POST')
			->before($mustBeAdmin);

		/**
		 * Supprimer un gn
		 */
		$controllers->match('/{gn}/delete','LarpManager\Controllers\GnController::deleteAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.delete")
			->before($mustBeAdmin);
			
		/**
		 * voir le détail d'un gn
		 */
		$controllers->match('/{gn}','LarpManager\Controllers\GnController::detailAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.detail")
			->before($mustBeAdmin);

		/**
		 * Liste des participants à un gn
		 */
		$controllers->match('/{gn}/participants', 'LarpManager\Controllers\GnController::participantsAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.participants")
			->before($mustBeAdmin);
			
		/**
		 * Modifier un gn
		 */
		$controllers->match('/{gn}/fedegn','LarpManager\Controllers\GnController::fedegnAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.fedegn")
			->method('GET')
			->before($mustBeAdmin);

		/**
		 * Affiche la billetterie d'un GN
		 */
		$controllers->match('/{gn}/billetterie', 'LarpManager\Controllers\GnController::billetterieAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind('gn.billetterie')
			->method('GET');
			
		/**
		 * Liste des groupes prévu sur un jeu
		 */
		$controllers->match('/{gn}/groupes','LarpManager\Controllers\GnController::groupesAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.groupes")
			->method('GET');
			
		return $controllers;
	}
}