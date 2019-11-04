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
 * Role de base : ROLE_USER
 */
class GroupeGnControllerProvider implements ControllerProviderInterface
{
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
		 * Vérifie que l'utilisateur est responsable de ce groupe pour cette session de jeu
		 */
		$mustBeResponsable = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('GROUPE_RESPONSABLE', $request->get('groupe'))) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur est membre du groupe associe au GN (GroupeGn)
		 * @var unknown $mustBeMember
		 */
		$mustBeMemberOfGnGroup = function(Request $request) use ($app) {
		    if (!$app['security.authorization_checker']->isGranted('GROUPE_MEMBER', $request->get('groupeGn'))) {
		        throw new AccessDeniedException();
		    }
		};
		
		/**
		 * Vérifie que l'utilisateur dispose d'un billet pour cette session de jeu
		 */
		$mustHaveBillet = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('GROUPE_BILLET', $request->get('groupeGn'))) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Liste des sessions de jeu d'un groupe
		 */
		$controllers->match('/{groupe}','LarpManager\Controllers\GroupeGnController::listAction')
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->bind("groupeGn.list")
			->method('GET')
			->before($mustBeAdmin);
		
		/**
		 * Ajoute le groupe à une session de jeu
		 */
		$controllers->match('/{groupe}/jeu/add','LarpManager\Controllers\GroupeGnController::addAction')
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->bind("groupeGn.add")
			->method('GET|POST')
			->before($mustBeAdmin);
		
		/**
		 * Modifie la participation d'un groupe à un jeu
		 */
		$controllers->match('/{groupe}/jeu/{groupeGn}/update','LarpManager\Controllers\GroupeGnController::updateAction')
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->assert('groupeGn', '\d+')
			->convert('groupeGn', 'converter.groupeGn:convert')
			->bind("groupeGn.update")
			->method('GET|POST')
			->before($mustBeAdmin);
		
		/**
		 * Modifie le responsable d'un groupe
		 */
		$controllers->match('/{groupe}/jeu/{groupeGn}/responsable','LarpManager\Controllers\GroupeGnController::responsableAction')
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->assert('groupeGn', '\d+')
			->convert('groupeGn', 'converter.groupeGn:convert')
			->bind("groupeGn.responsable")
			->method('GET|POST')
			->before($mustBeAdmin);

		/**
		 * Ajoute un participant du groupe (pour les admins)
		 */
		$controllers->match('/{groupeGn}/participants/add','LarpManager\Controllers\GroupeGnController::participantAddAction')
			->assert('groupeGn', '\d+')
			->convert('groupeGn', 'converter.groupeGn:convert')
			->bind("groupeGn.participants.add")
			->method('GET|POST')
			->before($mustBeAdmin);
			
		/**
		 * Ajoute un participant du groupe (pour les chefs de groupes
		 */
		$controllers->match('/{groupe}/jeu/{groupeGn}/add','LarpManager\Controllers\GroupeGnController::joueurAddAction')
			->assert('groupeGn', '\d+')
			->convert('groupeGn', 'converter.groupeGn:convert')
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->bind("groupeGn.joueur.add")
			->method('GET|POST')
			->before($mustBeResponsable)
			->before($mustHaveBillet);
			
		/**
		 * Retire un participant du groupe
		 */
		$controllers->match('/{groupeGn}/participants/{participant}/remove','LarpManager\Controllers\GroupeGnController::participantRemoveAction')
			->assert('groupeGn', '\d+')
			->convert('groupeGn', 'converter.groupeGn:convert')
			->assert('participant', '\d+')
			->convert('participant', 'converter.participant:convert')
			->bind("groupeGn.participants.remove")
			->method('GET|POST')
			->before($mustBeAdmin);
		
			
		/**
		 * détail d'un groupe
		 */
		$controllers->match('/{groupeGn}/groupe','LarpManager\Controllers\GroupeGnController::groupeAction')
			->assert('groupeGn', '\d+')
			->convert('groupeGn', 'converter.groupeGn:convert')
			->bind("groupeGn.groupe")
			->method('GET')
			->before($mustHaveBillet)
			->before($mustBeMemberOfGnGroup);
			
		/**
		 * Modifie le nombre de place recherché par le chef de groupe
		 */
		$controllers->match('/{groupe}/jeu/{groupeGn}/placeAvailable','LarpManager\Controllers\GroupeGnController::placeAvailableAction')
			->assert('groupeGn', '\d+')
			->convert('groupeGn', 'converter.groupeGn:convert')
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->bind("groupeGn.placeAvailable")
			->method('GET|POST')
			->before($mustBeResponsable)
			->before($mustHaveBillet);		
			
			
		return $controllers;
	}
}