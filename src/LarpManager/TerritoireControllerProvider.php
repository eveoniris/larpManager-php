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
 * LarpManager\TerritoireControllerProvider
 * 
 * @author kevin
 *
 */
class TerritoireControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les territoires
	 * Routes :
	 * 	- territoire.admin.list
	 * 	- territoire.admin.detail
	 *  - territoire.admin.add
	 *  - territoire.admin.update
	 *  - territoire.admin.update.blason
	 *  - territoire.admin.updateStrategie
	 *  - territoire.admin.delete
	 *  - territoire.admin.topic.add
	 *  - territoire.admin.topic.delete
	 *  - territoire.admin.event.add
	 *  - territoire.admin.event.update
	 *  - territoire.admin.event.delete
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Vérifie que l'utilisateur dispose du role CARTOGRAPHE
		 */
		$mustBeCartographe = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_CARTOGRAPHE')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur dispose du role SCENARISTE
		 */
		$mustBeScenariste = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur est membre du groupe controllant le territoire
		 */
		$mustBeOnGroupe = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('TERRITOIRE_MEMBER',  $request->get('territoire'))) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Détail d'un territoire (pour les joueurs)
		 */
		$controllers->match('/{territoire}/joueur','LarpManager\Controllers\TerritoireController::detailJoueurAction')
			->assert('territoire', '\d+')
			->bind("territoire.detail")
			->method('GET')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeOnGroupe);
		
		/**
		 * Liste des territoires
		 */
		$controllers->match('/list','LarpManager\Controllers\TerritoireController::listAction')
			->bind("territoire.admin.list")
			->method('GET')
			->before($mustBeCartographe);
			
		/**
		 * Ajout d'un territoire
		 */
		$controllers->match('/add','LarpManager\Controllers\TerritoireController::addAction')
			->bind("territoire.admin.add")
			->method('GET|POST')
			->before($mustBeScenariste);
		
		/**
		 * Mise à jour d'un territoire
		 */
		$controllers->match('/{territoire}/update','LarpManager\Controllers\TerritoireController::updateAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.update")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeCartographe);

		/**
		 * Mise à jour du blason
		 */
		$controllers->match('/{territoire}/blason/update','LarpManager\Controllers\TerritoireController::updateBlasonAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.update.blason")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeCartographe);
				
		/**
		 * Détail d'un territoire
		 */
		$controllers->match('/{territoire}','LarpManager\Controllers\TerritoireController::detailAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.detail")
			->convert('territoire', 'converter.territoire:convert')
			->method('GET')
			->before($mustBeCartographe);
			
		/**
		 * Ajout d'un événement
		 */
		$controllers->match('/{territoire}/event/add','LarpManager\Controllers\TerritoireController::addEventAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.event.add")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->convert('event', 'converter.event:convert')
			->before($mustBeCartographe);
				
		/**
		 * Suppression d'un événement
		 */
		$controllers->match('/{territoire}/event/{event}/delete','LarpManager\Controllers\TerritoireController::deleteEventAction')
			->assert('territoire', '\d+')
			->assert('event', '\d+')
			->bind("territoire.admin.event.delete")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->convert('event', 'converter.event:convert')
			->before($mustBeCartographe);
				
		/**
		 * Mise à jour d'un événement
		 */
		$controllers->match('/{territoire}/event/{event}/update','LarpManager\Controllers\TerritoireController::updateEventAction')
			->assert('territoire', '\d+')
			->assert('event', '\d+')
			->bind("territoire.admin.event.update")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->convert('event', 'converter.event:convert')
			->before($mustBeCartographe);
			
		/**
		 * Impression des territoires
		 */
		$controllers->match('/print','LarpManager\Controllers\TerritoireController::printAction')
			->bind("territoire.admin.print")
			->method('GET')
			->before($mustBeScenariste);
		
		/**
		 * Suppression d'un territoire
		 */
		$controllers->match('/{territoire}/delete','LarpManager\Controllers\TerritoireController::deleteAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.delete")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);

		/**
		 * Gestiond des quêtes commerciales
		 */
		$controllers->match('/quete','LarpManager\Controllers\TerritoireController::queteAction')
			->bind("territoire.admin.quete")
			->method('GET')
			->before($mustBeScenariste);
		
		/**
		 * Liste des nobles
		 */
		$controllers->match('/noble','LarpManager\Controllers\TerritoireController::nobleAction')
			->bind("territoire.admin.noble")
			->method('GET')
			->before($mustBeScenariste);
		
		/**
		 * Ajout d'une construction
		 */
		$controllers->match('/{territoire}/construction/add','LarpManager\Controllers\TerritoireController::constructionAddAction')
			->assert('territoire', '\d+')
			->bind("territoire.construction.add")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);
		
		/**
		 * Retrait d'une construction
		 */
		$controllers->match('/{territoire}/construction/{construction}/remove','LarpManager\Controllers\TerritoireController::constructionRemoveAction')
			->assert('territoire', '\d+')
			->assert('construction', '\d+')
			->bind("territoire.construction.remove")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->convert('construction', 'converter.construction:convert')
			->before($mustBeScenariste);
		
		/**
		 * Mise à jour des ingrédients
		 */
		$controllers->match('/{territoire}/ingredients/update','LarpManager\Controllers\TerritoireController::updateIngredientsAction')
			->assert('territoire', '\d+')
			->bind("territoire.ingredients.update")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);
		
		/**
		 * Mise à jour jeu stratégique
		 */
		$controllers->match('/{territoire}/updateStrategie','LarpManager\Controllers\TerritoireController::updateStrategieAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.updateStrategie")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);					

		/**
		 * Ajout d'un forum
		 */
		$controllers->match('/{territoire}/topic/add','LarpManager\Controllers\TerritoireController::addTopicAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.topic.add")
			->method('GET')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);			
		
		/**
		 * Suppression du forum
		 */
		$controllers->match('/{territoire}/topic/delete','LarpManager\Controllers\TerritoireController::deleteTopicAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.topic.delete")
			->method('GET')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);	
					
		
		return $controllers;
	}
}