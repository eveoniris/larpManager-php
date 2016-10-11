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
 * LarpManager\PersonnageControllerProvider
 * 
 * @author kevin
 *
 * Securité de base : ROLE_USER
 */
class PersonnageControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les personnages
	 * 
	 * Routes :
	 * 	- personnage.admin.list
	 *  - personnage.admin.detail
	 *  - personnage.admin.add
	 *  - personnage.admin.update
	 *  - personnage.admin.religion.delete
	 *  - personnage.admin.religion.add
	 *  - personnage.admin.origine.update
	 *  - personnage.detail
	 *  - personnage.update
	 *  - personnage.delete
	 *  - personnage.competence.add
	 *  - personnage.competence.remove
	 *  - personnage.religion.add
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 * @throws AccessDeniedException
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Vérifie que l'utilisateur dispose du role ORGA
		 */
		$mustBeOrga = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA')) {
				throw new AccessDeniedException();
			}
		};
				
		/**
		 * Vérifie que l'utilisateur posséde la ressource demandée
		 */
		$mustOwn = function(Request $request) use ($app) {
			$personnage = $request->get('personnage');
			if ( ! $app['security.authorization_checker']->isGranted('OWN_PERSONNAGE', $personnage)) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Liste des personnages (orga)
		 */
		$controllers->match('/','LarpManager\Controllers\PersonnageController::accueilAction')
			->bind("personnage")
			->method('GET');
			

		/**
		 * Liste des personnages (orga)
		 */
		$controllers->match('/admin/list','LarpManager\Controllers\PersonnageController::adminListAction')
			->bind("personnage.admin.list")
			->method('GET|POST')
			->before($mustBeOrga);
			
		/**
		 * Liste des personnages pour impression (orga)
		 */
		$controllers->match('/admin/print','LarpManager\Controllers\PersonnageController::adminPrintAction')
			->bind("personnage.admin.print")
			->method('GET')
			->before($mustBeOrga);
			
		/**
		 * Liste des personnages pour téléchargement (orga)
		 */
		$controllers->match('/admin/download','LarpManager\Controllers\PersonnageController::adminDownloadAction')
			->bind("personnage.admin.download")
			->method('GET')
			->before($mustBeOrga);
			
		/**
		 * Ajoute un jeton à tous les personnages
		 */
		$controllers->match('/vieillir','LarpManager\Controllers\PersonnageController::vieillirAction')
			->bind("personnage.vieillir")
			->method('GET|POST')
			->before($mustBeOrga);
						
		/**
		 * Modification du statut d'un personnage (vivant / mort)
		 */
		$controllers->match('/admin/{personnage}/statut','LarpManager\Controllers\PersonnageController::adminStatutAction')
			->bind("personnage.admin.statut")
			->assert('personnage', '\d+')
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);

		/**
		 * Materiel lié à un personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/materiel','LarpManager\Controllers\PersonnageController::adminMaterielAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.materiel")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Detail d'un personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/detail','LarpManager\Controllers\PersonnageController::adminDetailAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.detail")
			->method('GET')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Gestion des points d'expériences (orga)
		 */
		$controllers->match('/admin/{personnage}/xp','LarpManager\Controllers\PersonnageController::adminXpAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.xp")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Ajout d'un personnage (orga)
		 */
		$controllers->match('/admin/add','LarpManager\Controllers\PersonnageController::adminAddAction')
			->bind("personnage.admin.add")
			->method('GET|POST')
			->before($mustBeOrga);
			
		/**
		 * Modification d'un personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/update','LarpManager\Controllers\PersonnageController::adminUpdateAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.update")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			

		/**
		 * Transfert d'un personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/transfert','LarpManager\Controllers\PersonnageController::adminTransfertAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.transfert")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Modification de la renomme d'un personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/update/renomme','LarpManager\Controllers\PersonnageController::adminUpdateRenommeAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.update.renomme")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Ajout d'un jeton (orga)
		 */
		$controllers->match('/admin/{personnage}/token/add/{token}','LarpManager\Controllers\PersonnageController::adminTokenAddAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.token.add")
			->method('GET')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);

		/**
		 * Suppression d'un jeton (orga)
		 */
		$controllers->match('/admin/{personnage}/token/delete/{personnageToken}','LarpManager\Controllers\PersonnageController::adminTokenDeleteAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.token.delete")
			->method('GET')
			->convert('personnage', 'converter.personnage:convert')
			->convert('personnageToken', 'converter.personnageToken:convert')
			->before($mustBeOrga);
			
			
		/**
		 * Ajout d'un trigger (orga)
		 */
		$controllers->match('/admin/{personnage}/trigger/add','LarpManager\Controllers\PersonnageController::adminTriggerAddAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.trigger.add")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Suppression d'un trigger (orga)
		 */
		$controllers->match('/admin/{personnage}/trigger/{trigger}/delete','LarpManager\Controllers\PersonnageController::adminTriggerDeleteAction')
			->assert('personnage', '\d+')
			->assert('trigger', '\d+')
			->bind("personnage.admin.trigger.delete")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->convert('trigger', 'converter.personnageTrigger:convert')
			->before($mustBeOrga);
			
		/**
		 * Ajout d'un domaine (orga)
		 */
		$controllers->match('/admin/{personnage}/update/domaine','LarpManager\Controllers\PersonnageController::adminUpdateDomaineAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.update.domaine")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Ajout d'un sortilège (orga)
		 */
		$controllers->match('/admin/{personnage}/update/sort','LarpManager\Controllers\PersonnageController::adminUpdateSortAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.update.sort")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);			

		/**
		 * Ajout d'une potion (orga)
		 */
		$controllers->match('/admin/{personnage}/update/potion','LarpManager\Controllers\PersonnageController::adminUpdatePotionAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.update.potion")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Mise à jours des langues
		 */
		$controllers->match('/admin/{personnage}/update/langue','LarpManager\Controllers\PersonnageController::adminUpdateLangueAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.update.langue")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Mise à jours des prieres
		 */
		$controllers->match('/admin/{personnage}/update/priere','LarpManager\Controllers\PersonnageController::adminUpdatePriereAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.update.priere")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Mise à jours des prieres
		 */
		$controllers->match('/admin/{personnage}/update/age','LarpManager\Controllers\PersonnageController::adminUpdateAgeAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.update.age")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Suppression d'une religion d'un personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/update/religion/{personnageReligion}/delete','LarpManager\Controllers\PersonnageController::adminRemoveReligionAction')
			->assert('personnage', '\d+')
			->assert('personnageReligion', '\d+')
			->bind("personnage.admin.religion.delete")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->convert('personnageReligion', 'converter.personnageReligion:convert')
			->before($mustBeOrga);
		
		/**
		 * Ajout d'une religion à un personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/update/religion/add','LarpManager\Controllers\PersonnageController::adminAddReligionAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.religion.add")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
		
		/**
		 * Suppression d'une langue d'un personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/update/langue/{personnageLangue}/delete','LarpManager\Controllers\PersonnageController::adminRemoveLangueAction')
			->assert('personnage', '\d+')
			->assert('personnageLangue', '\d+')
			->bind("personnage.admin.langue.delete")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->convert('personnageLangue', 'converter.personnageLangue:convert')
			->before($mustBeOrga);		
			
		/**
		 * Suppression d'un personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/delete','LarpManager\Controllers\PersonnageController::adminDeleteAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.delete")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Ajout d'une compétence au personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/competence/add','LarpManager\Controllers\PersonnageController::adminAddCompetenceAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.competence.add")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
		
		/**
		 * Export du personnage
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{personnage}/export','LarpManager\Controllers\PersonnageController::exportAction')
			->assert('personnage', '\d+')
			->bind("personnage.export")
			->method('GET')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustOwn);			
		
		
		/**
		 * Formulaire de choix du domaine de magie
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{personnage}/magie/domaine','LarpManager\Controllers\PersonnageController::domaineMagieAction')
			->assert('personnage', '\d+')
			->bind("personnage.magie.domaine")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustOwn);
			
		/**
		 * Formulaire de choix d'un nouveau sort
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{personnage}/magie/sort/{niveau}','LarpManager\Controllers\PersonnageController::sortAction')
			->assert('personnage', '\d+')
			->assert('niveau', '\d+')
			->bind("personnage.magie.sort")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustOwn);			
						
		/**
		 * Formulaire d'ajout des langues gagnés grace à litterature initie
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{personnage}/litterature/initie','LarpManager\Controllers\PersonnageController::litteratureInitieAction')
			->assert('personnage', '\d+')
			->bind("personnage.litterature.initie")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustOwn);
		
		/**
		 * Formulaire d'ajout des langues gagné grace à litterature expert
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{personnage}/litterature/expert','LarpManager\Controllers\PersonnageController::litteratureExpertAction')
			->assert('personnage', '\d+')
			->bind("personnage.litterature.expert")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustOwn);
			
		/**
		 * Retire la dernière compétence acquise par un personnage
		 * Accessible uniquement aux orgas
		 */
		$controllers->match('/{personnage}/competence/remove','LarpManager\Controllers\PersonnageController::removeCompetenceAction')
			->assert('personnage', '\d+')
			->bind("personnage.competence.remove")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
		
		/**
		 * Modifie l'origine d'un personnage
		 * Accessible uniquement aux orgas
		 */
		$controllers->match('/admin/{personnage}/origine/update','LarpManager\Controllers\PersonnageController::adminUpdateOriginAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.origine.update")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Ajoute un background au personnage
		 * Accessible uniquement aux orgas
		 */
		$controllers->match('/admin/{personnage}/background/add','LarpManager\Controllers\PersonnageController::adminAddBackgroundAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.background.add")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Modifie le background d'un personnage
		 * Accessible uniquement aux orgas
		 */
		$controllers->match('/admin/{personnage}/background/{background}/update','LarpManager\Controllers\PersonnageController::adminUpdateBackgroundAction')
			->assert('personnage', '\d+')
			->assert('background', '\d+')
			->bind("personnage.admin.background.update")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->convert('background', 'converter.personnageBackground:convert')
			->before($mustBeOrga);			

		/** Affichage de la page trombine */
		$controllers->match('/{personnage}/trombine/update','LarpManager\Controllers\PersonnageController::trombineAction')
			->assert('personnage', '\d+')
			->bind('personnage.trombine.update')
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustOwn);
			
		/** Récupére une trombine */
		$controllers->match('/{personnage}/trombine','LarpManager\Controllers\PersonnageController::getTrombineAction')
			->assert('personnage', '\d+')
			->bind('personnage.trombine')
			->method('GET')
			->convert('personnage', 'converter.personnage:convert');
			
		/**
		 * Création d'un nouveau personnage
		 */
		$controllers->match('/new','LarpManager\Controllers\PersonnageController::newAction')
			->bind("personnage.new")
			->method('GET|POST');
					
		return $controllers;
	}
}
