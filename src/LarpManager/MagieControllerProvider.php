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


/**
 * LarpManager\MagieControllerProvider
 * 
 * @author kevin
 */
class MagieControllerProvider implements ControllerProviderInterface
{
	
	/**
	 * Initialise les routes pour la magie
	 *   
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$mustBeScenariste = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_REGLE')) {
				throw new AccessDeniedException();
			}
		};
		
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/domaine','LarpManager\Controllers\MagieController::domaineListAction')
			->bind("magie.domaine.list")
			->before($mustBeScenariste)
			->method('GET');

		$controllers->match('/domaine/{domaine}','LarpManager\Controllers\MagieController::domaineDetailAction')
			->assert('domaine', '\d+')
			->bind("magie.domaine.detail")
			->convert('domaine', 'converter.domaine:convert')
			->before($mustBeScenariste)
			->method('GET');			
		
		$controllers->match('/domaine/add','LarpManager\Controllers\MagieController::domaineAddAction')
			->bind("magie.domaine.add")
			->before($mustBeScenariste)
			->method('GET|POST');
		
		$controllers->match('/domaine/{domaine}/update','LarpManager\Controllers\MagieController::domaineUpdateAction')
			->assert('domaine', '\d+')
			->bind("magie.domaine.update")
			->convert('domaine', 'converter.domaine:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
		
		/**
		 * Supprimer un domaine de magie
		 */
		$controllers->match('/domaine/{domaine}/delete','LarpManager\Controllers\MagieController::domaineDeleteAction')
			->assert('domaine', '\d+')
			->bind("magie.domaine.delete")
			->convert('domaine', 'converter.domaine:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
		
		$controllers->match('/sphere','LarpManager\Controllers\MagieController::sphereListAction')
			->bind("magie.sphere.list")
			->before($mustBeScenariste)
			->method('GET');
			
		$controllers->match('/sphere/{sphere}','LarpManager\Controllers\MagieController::sphereDetailAction')
			->assert('sphere', '\d+')
			->bind("magie.sphere.detail")
			->convert('sphere', 'converter.sphere:convert')
			->before($mustBeScenariste)
			->method('GET');
			
		$controllers->match('/sphere/add','LarpManager\Controllers\MagieController::sphereAddAction')
			->bind("magie.sphere.add")
			->before($mustBeScenariste)
			->method('GET|POST');
			
		$controllers->match('/sphere/{sphere}/update','LarpManager\Controllers\MagieController::sphereUpdateAction')
			->assert('sphere', '\d+')
			->bind("magie.sphere.update")
			->convert('sphere', 'converter.sphere:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
			
		/**
		 * Supprimer une sphere
		 */
		$controllers->match('/sphere/{sphere}/delete','LarpManager\Controllers\MagieController::sphereDeleteAction')
			->assert('sphere', '\d+')
			->bind("magie.sphere.delete")
			->convert('sphere', 'converter.sphere:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
			
		/**
		 * Liste des sortilèges
		 */
		$controllers->match('/sort','LarpManager\Controllers\MagieController::sortListAction')
			->bind("magie.sort.list")
			->before($mustBeScenariste)
			->method('GET');
		
		/**
		 * Détail d'un sortilège
		 */
		$controllers->match('/sort/{sort}','LarpManager\Controllers\MagieController::sortDetailAction')
			->assert('sort', '\d+')
			->bind("magie.sort.detail")
			->convert('sort', 'converter.sort:convert')
			->before($mustBeScenariste)
			->method('GET');

		/**
		 * Obtenir les personnages possédant ce sort
		 */
		$controllers->match('/sort/{sort}/personnages','LarpManager\Controllers\MagieController::sortPersonnagesAction')
			->assert('sort', '\d+')
			->bind("magie.sort.personnages")
			->convert('sort', 'converter.sort:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
			
		/**
		 * Ajouter un sortilège
		 */
		$controllers->match('/sort/add','LarpManager\Controllers\MagieController::sortAddAction')
			->bind("magie.sort.add")
			->before($mustBeScenariste)
			->method('GET|POST');
		
		/**
		 * Modifier un sortilège
		 */
		$controllers->match('/sort/{sort}/update','LarpManager\Controllers\MagieController::sortUpdateAction')
			->assert('sort', '\d+')
			->bind("magie.sort.update")
			->convert('sort', 'converter.sort:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
		
		/**
		 * Supprimer un sortilège
		 */
		$controllers->match('/sort/{sort}/delete','LarpManager\Controllers\MagieController::sortDeleteAction')
			->assert('sort', '\d+')
			->bind("magie.sort.delete")
			->convert('sort', 'converter.sort:convert')
			->before($mustBeScenariste)
			->method('GET|POST');			
		
		/**
		 * Obtenir un document lié à un sortilège
		 */
		$controllers->match('/sort/{sort}/document/{document}','LarpManager\Controllers\MagieController::getSortDocumentAction')
			->bind("magie.sort.document")
			->convert('sort', 'converter.sort:convert')
			->before($mustBeScenariste)
			->method('GET');
					
		/**
		 * Lister les potions
		 */
		$controllers->match('/potion','LarpManager\Controllers\MagieController::potionListAction')
			->bind("magie.potion.list")
			->before($mustBeScenariste)
			->method('GET');
			
		/**
		 * Obtenir le détail d'une potion
		 */
		$controllers->match('/potion/{potion}','LarpManager\Controllers\MagieController::potionDetailAction')
			->assert('potion', '\d+')
			->bind("magie.potion.detail")
			->convert('potion', 'converter.potion:convert')
			->before($mustBeScenariste)
			->method('GET');
			
		/**
		 * Obtenir les personnages possédant cette potion
		 */
		$controllers->match('/potion/{potion}/personnages','LarpManager\Controllers\MagieController::potionPersonnagesAction')
			->assert('potion', '\d+')
			->bind("magie.potion.personnages")
			->convert('potion', 'converter.potion:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
			
		/**
		 * Ajouter une potion
		 */
		$controllers->match('/potion/add','LarpManager\Controllers\MagieController::potionAddAction')
			->bind("magie.potion.add")
			->before($mustBeScenariste)
			->method('GET|POST');
			
		/**
		 * Modifier une potion
		 */
		$controllers->match('/potion/{potion}/update','LarpManager\Controllers\MagieController::potionUpdateAction')
			->assert('potion', '\d+')
			->bind("magie.potion.update")
			->convert('potion', 'converter.potion:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
			
		/**
		 * Supprimer une potion
		 */
		$controllers->match('/potion/{potion}/delete','LarpManager\Controllers\MagieController::potionDeleteAction')
			->assert('potion', '\d+')
			->bind("magie.potion.delete")
			->convert('potion', 'converter.potion:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
			
		/**
		 * Obtenir un document lié à une potion
		 */
		$controllers->match('/potion/{potion}/document/{document}','LarpManager\Controllers\MagieController::getPotionDocumentAction')
			->bind("magie.potion.document")
			->convert('potion', 'converter.potion:convert')
			->before($mustBeScenariste)
			->method('GET');
	
		/**
		 * Lister les prières
		 */
		$controllers->match('/priere','LarpManager\Controllers\MagieController::priereListAction')
			->bind("magie.priere.list")
			->before($mustBeScenariste)
			->method('GET');
				
		/**
		 * Obtenir le détail d'une priere
		 */
		$controllers->match('/priere/{priere}','LarpManager\Controllers\MagieController::priereDetailAction')
			->assert('priere', '\d+')
			->bind("magie.priere.detail")
			->convert('priere', 'converter.priere:convert')
			->before($mustBeScenariste)
			->method('GET');
	
		/**
		 * Obtenir les personnages possédant cette prière
		 */
		$controllers->match('/priere/{priere}/personnages','LarpManager\Controllers\MagieController::prierePersonnagesAction')
		->assert('priere', '\d+')
		->bind("magie.priere.personnages")
		->convert('priere', 'converter.priere:convert')
		->before($mustBeScenariste)
		->method('GET|POST');

		/**
		 * Ajouter une priere
		 */
		$controllers->match('/priere/add','LarpManager\Controllers\MagieController::priereAddAction')
			->bind("magie.priere.add")
			->before($mustBeScenariste)
			->method('GET|POST');
				
		/**
		 * Modifier une priere
		 */
		$controllers->match('/priere/{priere}/update','LarpManager\Controllers\MagieController::priereUpdateAction')
			->assert('priere', '\d+')
			->bind("magie.priere.update")
			->convert('priere', 'converter.priere:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
			
		/**
		 * Supprimer une priere
		 */
		$controllers->match('/priere/{priere}/delete','LarpManager\Controllers\MagieController::priereDeleteAction')
			->assert('priere', '\d+')
			->bind("magie.priere.delete")
			->convert('priere', 'converter.priere:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
				
		/**
		 * Obtenir un document lié à une priere
		 */
		$controllers->match('/priere/{priere}/document/{document}','LarpManager\Controllers\MagieController::getPriereDocumentAction')
			->bind("magie.priere.document")
			->convert('priere', 'converter.priere:convert')
			->method('GET');
			
		return $controllers;
	}
}