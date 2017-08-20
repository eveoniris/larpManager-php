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
 * LarpManager\GroupeControllerProvider
 * 
 * @author kevin
 *
 */
class GroupeSecondaireControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Vérifie que l'utilisateur dispose du role REGLE
		 */
		$mustBeOrga = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur dispose du role SCENARISTE
		 * @var unknown
		 */
		$mustBeScenariste = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
				throw new AccessDeniedException();
			}
		};
										
		/**
		 * Liste des groupes secondaires (pour les orgas)
		 */		
		$controllers->match('/admin/list','LarpManager\Controllers\GroupeSecondaireController::adminListAction')
			->bind("groupeSecondaire.admin.list")
			->method('GET')
			->before($mustBeOrga);
			
		/**
		 * Detail d'un groupe secondaire (pour les orgas)
		 */
		$controllers->match('/admin/{groupe}','LarpManager\Controllers\GroupeSecondaireController::adminDetailAction')
			->assert('groupe', '\d+')
			->bind("groupeSecondaire.admin.detail")
			->method('GET')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->before($mustBeOrga);
		
		/**
		 * Ajouter un membre (pour les orgas)
		 */
		$controllers->match('/admin/{groupeSecondaire}/newMembre','LarpManager\Controllers\GroupeSecondaireController::adminNewMembreAction')
			->assert('groupeSecondaire', '\d+')
			->bind("groupeSecondaire.admin.newMembre")
			->method('GET|POST')
			->convert('groupeSecondaire', 'converter.secondaryGroup:convert')
			->before($mustBeScenariste);
			
		/**
		 * Autorise l'accés aux secrets à un membre du groupe
		 */
		$controllers->match('/admin/{groupe}/secret/{membre}/on','LarpManager\Controllers\GroupeSecondaireController::adminSecretOnAction')
			->assert('groupe', '\d+')
			->assert('membre', '\d+')
			->bind("groupeSecondaire.admin.secret.on")
			->method('GET')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->convert('membre', 'converter.membre:convert')
			->before($mustBeOrga);
		
		/**
		 * Retire l'accés aux secrets à un membre du groupe
		 */
		$controllers->match('/admin/{groupe}/secret/{membre}/off','LarpManager\Controllers\GroupeSecondaireController::adminSecretOffAction')
			->assert('groupe', '\d+')
			->assert('membre', '\d+')
			->bind("groupeSecondaire.admin.secret.off")
			->method('GET')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->convert('membre', 'converter.membre:convert')
			->before($mustBeOrga);
		
		/**
		 * Retire l'accés aux secrets à un membre du groupe
		 */
		$controllers->match('/admin/{groupe}/membre/{membre}/remove','LarpManager\Controllers\GroupeSecondaireController::adminRemoveMembreAction')
			->assert('groupe', '\d+')
			->assert('membre', '\d+')
			->bind("groupeSecondaire.admin.member.remove")
			->method('GET')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->convert('membre', 'converter.membre:convert')
			->before($mustBeOrga);			
		
		/**
		 * Supprime une candidature
		 */
		$controllers->match('/admin/{groupe}/postulant/{postulant}/remove','LarpManager\Controllers\GroupeSecondaireController::adminRemovePostulantAction')
			->assert('groupe', '\d+')
			->assert('postulant', '\d+')
			->bind("groupeSecondaire.admin.postulant.remove")
			->method('GET')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->convert('postulant', 'converter.postulant:convert')
			->before($mustBeOrga);
		
		/**
		 * Accepte une candidature
		 */
		$controllers->match('/admin/{groupe}/postulant/{postulant}/accept','LarpManager\Controllers\GroupeSecondaireController::adminAcceptPostulantAction')
			->assert('groupe', '\d+')
			->assert('postulant', '\d+')
			->bind("groupeSecondaire.admin.postulant.accept")
			->method('GET')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->convert('postulant', 'converter.postulant:convert')
			->before($mustBeOrga);

		/**
		 * Ajouter un groupe secondaire (pour les orgas)
		 */
		$controllers->match('/admin/add','LarpManager\Controllers\GroupeSecondaireController::adminAddAction')
			->bind("groupeSecondaire.admin.add")
			->method('GET|POST')
			->before($mustBeOrga);

		/**
		 * Modification du matériel d'un groupe secondaire
		 */
		$controllers->match('/{groupeSecondaire}/materiel/update','LarpManager\Controllers\GroupeSecondaireController::materielUpdateAction')
			->assert('groupeSecondaire', '\d+')
			->bind("groupeSecondaire.materiel.update")
			->method('GET|POST')
			->convert('groupeSecondaire', 'converter.secondaryGroup:convert')
			->before($mustBeOrga);
			
		/**
		 * Impression du matériel d'un groupe secondaire
		 */
		$controllers->match('/{groupeSecondaire}/materiel/print','LarpManager\Controllers\GroupeSecondaireController::materielPrintAction')
			->assert('groupeSecondaire', '\d+')
			->bind("groupeSecondaire.materiel.print")
			->method('GET|POST')
			->convert('groupeSecondaire', 'converter.secondaryGroup:convert')
			->before($mustBeOrga);
			
		/**
		 * Impression du matériel des groupes secondaires
		 */
		$controllers->match('/materiel/print','LarpManager\Controllers\GroupeSecondaireController::materielPrintAllAction')
			->bind("groupeSecondaire.materiel.printAll")
			->method('GET|POST')
			->before($mustBeOrga);			
			
		/**
		 * Modification d'un groupe secondaire (pour les orgas)
		 */
		$controllers->match('/admin/{groupe}/update','LarpManager\Controllers\GroupeSecondaireController::adminUpdateAction')
			->assert('groupe', '\d+')
			->bind("groupeSecondaire.admin.update")
			->method('GET|POST')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->before($mustBeOrga);

		/**
		 * Accepter une candidature (pour les orgas)
		 */
		$controllers->match('/{groupe}/reponse','LarpManager\Controllers\GroupeSecondaireController::adminReponseAction')
			->assert('groupe', '\d+')
			->bind("groupeSecondaire.admin.reponse")
			->method('GET|POST')
			->convert('groupe', 'converter.secondaryGroup:convert')
			->before($mustBeOrga);		
			
		return $controllers;
	}
}