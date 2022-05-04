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
		 * Vérifie que l'utilisateur dispose du role SCENARISTE
		 */
		$mustBeScenariste = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Voir la liste des gns
		 */
		$controllers->match('/','LarpManager\Controllers\GnController::listAction')
			->bind("gn.list")
			->method('GET');

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
			->bind("gn.detail");
			
		/**
		 * Gestion des billets d'un GN
		 */
		$controllers->match('/{gn}/billet','LarpManager\Controllers\GnController::billetAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.billet")
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
		 * Liste des participants à un gn n'ayant pas encore de billet
		 */
		$controllers->match('/{gn}/participants/withoutbillet', 'LarpManager\Controllers\GnController::participantsWithoutBilletAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.participants.withoutbillet")
			->before($mustBeAdmin);
			
		/**
		 * Liste des participants à un gn ayant un billet mais n'ayant pas encore de groupe
		 */
		$controllers->match('/{gn}/participants/withoutgroup', 'LarpManager\Controllers\GnController::participantsWithoutGroupAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.participants.withoutgroup")
			->before($mustBeAdmin);
			
		/**
		 * Liste des participants à un gn ayant un billet mais pas de personnages
		 */
			$controllers->match('/{gn}/participants/withoutperso', 'LarpManager\Controllers\GnController::participantsWithoutPersoAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.participants.withoutperso")
			->before($mustBeAdmin);
			
		/**
		 * Liste des participants pnj
		 */
			$controllers->match('/{gn}/participants/pnj', 'LarpManager\Controllers\GnController::pnjsAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.participants.pnj")
			->before($mustBeAdmin);
			
		/**
		 * Liste des participants à un gn n'ayant pas encore de billet
		 */
		$controllers->match('/{gn}/participants/withoutbillet.csv', 'LarpManager\Controllers\GnController::participantsWithoutBilletCSVAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.participants.withoutbillet.csv")
			->before($mustBeAdmin);
				
		/**
		 * Liste des participants à un gn ayant un billet mais n'ayant pas encore de groupe
		 */
		$controllers->match('/{gn}/participants/withoutgroup.csv', 'LarpManager\Controllers\GnController::participantsWithoutGroupCSVAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.participants.withoutgroup.csv")
			->before($mustBeAdmin);
			
		/**
		 * Liste des participants à un gn ayant un billet mais n'ayant pas encore de personnage
		 */
		$controllers->match('/{gn}/participants/withoutperso.csv', 'LarpManager\Controllers\GnController::participantsWithoutPersoCSVAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.participants.withoutperso.csv")
			->before($mustBeAdmin);
			
		/**
		 * Liste des groupes réservés
		 */
		$controllers->match('/{gn}/groupesReserves', 'LarpManager\Controllers\GnController::groupesReservesAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.groupesReserves")
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
		 *  Impression des backgrounds de chefs de groupes pour un jeu
		 */
		$controllers->match('/{gn}/groupes/backgrounds/chef','LarpManager\Controllers\GnController::backgroundsChefAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind('gn.groupes.backgrounds.chef')
			->method('GET');
			
		/**
		 *  Impression des backgrounds de groupes pour un jeu
		 */
		$controllers->match('/{gn}/groupes/backgrounds/groupe','LarpManager\Controllers\GnController::backgroundsGroupeAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind('gn.groupes.backgrounds.groupe')
			->method('GET');
			
		/**
		 *  Impression des backgrounds des membres groupes pour un jeu
		 */
		$controllers->match('/{gn}/groupes/backgrounds/membres','LarpManager\Controllers\GnController::backgroundsMembresAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind('gn.groupes.backgrounds.membres')
			->method('GET');
			
		/**
		 *  Impression des enveloppes pour un jeu
		 */
		$controllers->match('/{gn}/groupes/enveloppes','LarpManager\Controllers\GnController::enveloppesAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind('gn.groupes.enveloppes')
			->method('GET');
			
		/**
		 * Liste des groupes prévu sur un jeu
		 */
		$controllers->match('/{gn}/groupes','LarpManager\Controllers\GnController::groupesAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.groupes")
			->method('GET');
			
		/**
		 * Liste des groupes recherchant des joueurs
		 */
		$controllers->match('/{gn}/groupesPlaces','LarpManager\Controllers\GnController::groupesPlacesAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.groupesPlaces")
			->method('GET');
			
		/**
		 * La fiche de personnage d'un participant au gn.
		 */
		$controllers->match('/{gn}/personnage', 'LarpManager\Controllers\GnController::personnageAction')
			->assert('gn', '\d+')
			->convert('gn', 'converter.gn:convert')
			->bind("gn.personnage")
			->method('GET');

			
		/**
		 * Impression des fiches de perso
		 */
		$controllers->match('/{gn}/print/perso','LarpManager\Controllers\GnController::printPersoAction')
			->assert('gn', '\d+')
			->bind("gn.print.perso")
			->convert('gn', 'converter.gn:convert')
			->method('GET')
			->before($mustBeScenariste);			
		return $controllers;
	}
}