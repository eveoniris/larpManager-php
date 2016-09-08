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
 * LarpManager\GroupeGnControllerProvider
 * 
 * @author kevin
 *
 */
class GroupeGnControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Ajoute le groupe à un jeu
		 */
		$controllers->match('/{groupe}','LarpManager\Controllers\GroupeGnController::listAction')
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->bind("groupeGn.list")
			->method('GET');
		
		/**
		 * Ajoute le groupe à un jeu
		 */
		$controllers->match('/{groupe}/jeu/add','LarpManager\Controllers\GroupeGnController::addAction')
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->bind("groupeGn.add")
			->method('GET|POST');
		
		/**
		 * Modifie la participation d'un groupe à un jeu
		 */
		$controllers->match('/{groupe}/jeu/{groupeGn}/update','LarpManager\Controllers\GroupeGnController::updateAction')
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->assert('groupeGn', '\d+')
			->convert('groupeGn', 'converter.groupeGn:convert')
			->bind("groupeGn.update")
			->method('GET|POST');
		
		/**
		 * Modifie le responsable d'un groupe
		 */
		$controllers->match('/{groupe}/jeu/{groupeGn}/responsable','LarpManager\Controllers\GroupeGnController::responsableAction')
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->assert('groupeGn', '\d+')
			->convert('groupeGn', 'converter.groupeGn:convert')
			->bind("groupeGn.responsable")
			->method('GET|POST');

		/**
		 * Ajoute un participant du groupe
		 */
		$controllers->match('/{groupeGn}/participants/add','LarpManager\Controllers\GroupeGnController::participantAddAction')
			->assert('groupeGn', '\d+')
			->convert('groupeGn', 'converter.groupeGn:convert')
			->bind("groupeGn.participants.add")
			->method('GET|POST');
			
		/**
		 * Retire un participant du groupe
		 */
		$controllers->match('/{groupeGn}/participants/{participant}/remove','LarpManager\Controllers\GroupeGnController::participantRemoveAction')
			->assert('groupeGn', '\d+')
			->convert('groupeGn', 'converter.groupeGn:convert')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("groupeGn.participants.remove")
			->method('GET|POST');
			
		return $controllers;
	}
}