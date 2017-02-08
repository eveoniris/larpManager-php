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
class GroupeControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les groupes
	 * Routes :
	 *  - groupe.diplomatie
	 * 	- groupe
	 * 	- groupe.add
	 *  - groupe.update
	 *  - groupe.detail
	 *  - groupe.description  
	 *  - groupe.gestion
	 *  - groupe.joueur
	 *  - groupe.place
	 *  - groupe.scenariste
	 *  - groupe.composition
	 *  - groupe.intrigue
	 *  - groupe.personnage.document
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 * @throws AccessDeniedException
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
		 * @var unknown $mustBeAdmin
		 */
		$mustBeAdmin = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur est membre du groupe
		 * @var unknown $mustBeMember
		 */
		$mustBeMember = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('GROUPE_MEMBER', $request->get('index'))) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur est responsable du groupe
		 * @var unknown $mustBeResponsable
		 */
		$mustBeResponsable = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('GROUPE_RESPONSABLE', $request->get('groupe'))) {
				throw new AccessDeniedException();
			}
		};
													

		/**
		 * Surveiller la diplomatie entre groupe
		 */
		$controllers->match('/diplomatie', 'LarpManager\Controllers\GroupeController::diplomatieAction')
			->bind("groupe.diplomatie")
			->method('GET')
			->before($mustBeResponsable);
			
		/**
		 * Surveiller la diplomatie entre groupe
		 */
		$controllers->match('/diplomatie/print', 'LarpManager\Controllers\GroupeController::diplomatiePrintAction')
			->bind("groupe.diplomatie.print")
			->method('GET')
			->before($mustBeResponsable);
		
		/**
		 * Liste des groupes
		 */
		$controllers->match('/admin/list','LarpManager\Controllers\GroupeController::adminListAction')
			->bind("groupe.admin.list")
			->method('GET|POST')
			->before($mustBeScenariste);
								
		/**
		 * Gestion des documents lié au groupe
		 */
		$controllers->match('/admin/{groupe}/documents','LarpManager\Controllers\GroupeController::adminDocumentAction')
			->bind("groupe.admin.documents")
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Gestion des objets lié au groupe
		 */
		$controllers->match('/admin/{groupe}/items','LarpManager\Controllers\GroupeController::adminItemAction')
			->bind("groupe.admin.items")
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeScenariste);
				
			
		/**
		 * Gestion des documents lié à un personnage
		 */
		$controllers->match('/admin/{groupe}/{personnage}/documents','LarpManager\Controllers\GroupeController::personnageDocumentAction')
			->bind("groupe.personnage.documents")
			->assert('groupe', '\d+')
			->assert('personnage', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->convert('personnage', 'converter.personnage:convert')
			->method('GET|POST')
			->before($mustBeScenariste);

		/**
		 * Gestion des objets lié à un personnage
		 */
		$controllers->match('/admin/{groupe}/{personnage}/items','LarpManager\Controllers\GroupeController::personnageItemAction')
			->bind("groupe.personnage.items")
			->assert('groupe', '\d+')
			->assert('personnage', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->convert('personnage', 'converter.personnage:convert')
			->method('GET|POST')
			->before($mustBeScenariste);
			
			
		/**
		 * Gestion de la richesse d'un groupe
		 */
		$controllers->match('/admin/{groupe}/richesse','LarpManager\Controllers\GroupeController::adminRichesseAction')
			->bind("groupe.admin.richesse")
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeScenariste);

		/**
		 * Gestion des ressources liés au groupe
		 */
		$controllers->match('/admin/{groupe}/ressources','LarpManager\Controllers\GroupeController::adminRessourceAction')
			->bind("groupe.admin.ressources")
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeScenariste);

		/**
		 * Gestion des ingredients liés au groupe
		 */
		$controllers->match('/admin/{groupe}/ingredients','LarpManager\Controllers\GroupeController::adminIngredientAction')
			->bind("groupe.admin.ingredients")
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Rechercher un groupe
		 */
		$controllers->match('/search','LarpManager\Controllers\GroupeController::searchAction')
			->bind("groupe.search")
			->method('GET|POST')
			->before($mustBeScenariste);
				
		/**
		 * Detail d'un groupe
		 */
		$controllers->match('/{index}','LarpManager\Controllers\GroupeController::detailAction')
			->assert('index', '\d+')
			->bind("groupe.detail")
			->method('GET')
			->before($mustBeScenariste);			
		
		/**
		 *  Ajout d'un groupe (Scénariste uniquement)
		 */
		$controllers->match('/add','LarpManager\Controllers\GroupeController::addAction')
			->bind("groupe.add")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Restauration des membres du groupe (Admin uniquement)
		 */
		$controllers->match('/{groupe}/restauration','LarpManager\Controllers\GroupeController::restaurationAction')
			->assert('groupe', '\d+')
			->bind("groupe.restauration")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeAdmin);
			
		/**
		 * Membres du groupe (Scénariste uniquement)
		 */
		$controllers->match('/{groupe}/users','LarpManager\Controllers\GroupeController::usersAction')
			->assert('groupe', '\d+')
			->bind("groupe.users")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeScenariste);

		/**
		 * Enveloppe du groupe (Scénariste uniquement)
		 */
		$controllers->match('/{groupe}/envelope','LarpManager\Controllers\GroupeController::envelopeAction')
			->assert('groupe', '\d+')
			->bind("groupe.envelope")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Impression du matériel necessaire
		 */
		$controllers->match('/{groupe}/print/materiel','LarpManager\Controllers\GroupeController::printMaterielAction')
			->assert('groupe', '\d+')
			->bind("groupe.print.materiel")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET')
			->before($mustBeScenariste);
			
		/**
		 * Impression du matériel necessaire
		 */
		$controllers->match('/{groupe}/print/materiel/groupe','LarpManager\Controllers\GroupeController::printMaterielGroupeAction')
			->assert('groupe', '\d+')
			->bind("groupe.print.materiel.groupe")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET')
			->before($mustBeScenariste);
			
		/**
		 * Impression des fiches de perso
		 */
		$controllers->match('/{groupe}/print/perso','LarpManager\Controllers\GroupeController::printPersoAction')
			->assert('groupe', '\d+')
			->bind("groupe.print.perso")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET')
			->before($mustBeScenariste);
			
		/**
		 * Impression du background
		 */
		$controllers->match('/{groupe}/print/background','LarpManager\Controllers\GroupeController::printBackgroundAction')
			->assert('groupe', '\d+')
			->bind("groupe.print.background")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET')
			->before($mustBeScenariste);
			
		/**
		 * lock
		 */
		$controllers->match('/{groupe}/lock','LarpManager\Controllers\GroupeController::lockAction')
			->assert('groupe', '\d+')
			->bind("groupe.lock")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET')
			->before($mustBeScenariste);
			
		/**
		 * unlock
		 */
		$controllers->match('/{groupe}/unlock','LarpManager\Controllers\GroupeController::unlockAction')
			->assert('groupe', '\d+')
			->bind("groupe.unlock")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET')
			->before($mustBeScenariste);
			
		/**
		 * available
		 */
		$controllers->match('/{groupe}/available','LarpManager\Controllers\GroupeController::availableAction')
			->assert('groupe', '\d+')
			->bind("groupe.available")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET')
			->before($mustBeScenariste);
				
		/**
		 * unvailable
		 */
		$controllers->match('/{groupe}/unvailable','LarpManager\Controllers\GroupeController::unvailableAction')
			->assert('groupe', '\d+')
			->bind("groupe.unvailable")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET')
			->before($mustBeScenariste);

		/**
		 * Génération de quêtes commerciales
		 */
		$controllers->match('/quetes/','LarpManager\Controllers\GroupeController::quetesAction')
			->bind("groupe.quetes")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Génération de quêtes commerciales
		 */
		$controllers->match('/{groupe}/quete/','LarpManager\Controllers\GroupeController::queteAction')
			->assert('groupe', '\d+')
			->bind("groupe.quete")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Ajouter un territoire
		 */
		$controllers->match('/{groupe}/territoire/add','LarpManager\Controllers\GroupeController::territoireAddAction')
			->assert('groupe', '\d+')
			->bind("groupe.territoire.add")
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Retirer un territoire
		 */
		$controllers->match('/{groupe}/territoire/{territoire}/remove','LarpManager\Controllers\GroupeController::territoireRemoveAction')
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->assert('territoire', '\d+')
			->convert('territoire', 'converter.territoire:convert')
			->bind("groupe.territoire.remove")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 *  Mise à jour d'un groupe (scénariste uniquement)
		 */
		$controllers->match('/{index}/update','LarpManager\Controllers\GroupeController::updateAction')
			->assert('index', '\d+')
			->bind("groupe.update")
			->method('GET|POST')
			->before($mustBeScenariste);

		/**
		 * Ajout d'un background (scénariste uniquement)
		 */
		$controllers->match('/{index}/background/add','LarpManager\Controllers\GroupeController::addBackgroundAction')
			->assert('index', '\d+')
			->bind("groupe.background.add")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 *  Modification d'un background (scénariste uniquement)
		 */
		$controllers->match('/{index}/background/update','LarpManager\Controllers\GroupeController::updateBackgroundAction')
			->assert('index', '\d+')
			->bind("groupe.background.update")
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 *  Modification de la description du groupe
		 */
		$controllers->match('/{groupe}/description','LarpManager\Controllers\GroupeController::descriptionAction')
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->bind("groupe.description")
			->method('GET|POST')
			->before($mustBeScenariste);
		
		/**
		 * Choisir le scenariste
		 */
		$controllers->match('/{groupe}/scenariste','LarpManager\Controllers\GroupeController::scenaristeAction')
			->bind("groupe.scenariste")
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Ratacher un groupe à un pays
		 */
		$controllers->match('/{groupe}/pays','LarpManager\Controllers\GroupeController::paysAction')
			->bind("groupe.pays")
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeScenariste);
			
		/**
		 * Modifier la composition du groupe
		 */
		$controllers->match('/{groupe}/composition','LarpManager\Controllers\GroupeController::compositionAction')
			->bind("groupe.composition")
			->assert('groupe', '\d+')
			->convert('groupe', 'converter.groupe:convert')
			->method('GET|POST')
			->before($mustBeScenariste);
			
		return $controllers;
	}
}
