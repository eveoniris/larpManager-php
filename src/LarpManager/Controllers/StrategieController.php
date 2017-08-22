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
 
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

use Silex\Application;

/**
 * LarpManager\Controllers\StrategieController
 *
 * @author kevin
 *
 */
class StrategieController
{
	/**
	 * Présentation des constructions
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$territoires = new ArrayCollection();
		
		// recherche le prochain GN
		$gnRepo = $app['orm.em']->getRepository('\LarpManager\Entities\Gn');
		$gn = $gnRepo->findNext();
		
		
		$groupeRepo = $app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
		$groupes = $groupeRepo->findAll();
		
		foreach ($groupes as $groupe)
		{
			//  les groupes doivent participer au prochain GN
			if ( $groupe->getGroupeGnById($gn->getId())) {
				foreach ( $groupe->getTerritoires() as $territoire)
				{
					$territoires[] = $territoire;
				}
			}
		}
		
		// classement des résultats
		$iterator = $territoires->getIterator();
		$iterator->uasort(function ($first, $second) {
			return strcmp($first->getNom(),$second->getNom());
		});
		$territoires = new ArrayCollection(iterator_to_array($iterator));
		
		return $app['twig']->render('admin/strategie/index.twig', array(
				'gn' => $gn,
				'territoires' => $territoires,
		));
	}
	
	/**
	 * Sortie CSV pour le jeu strategique
	 * 
	 *  Liste des fiefs /
	 *  Nom du groupe qui le contrôle (vide si personne) / 
	 *  Niveau d'ordre social 
	 *  Liste des constructions sur le fief 
	 *  case vide (pour pouvoir le modifier) 
	 *  valeur de défense max 
	 *  valeur de défense actuelle (identique pour l'instant mais ce serait bien de prévoir que ça change)
	 *  case vide (pour gérer les changements)
	 *  case vide (pour mettre les horaires d'attaque ou de défense)
	 * @param Request $request
	 * @param Application $app
	 */
	public function csvAction(Request $request,Application $app)
	{
		$territoires = $app['orm.em']->getRepository('\LarpManager\Entities\Territoire')->findFiefs();
		
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=eveoniris_economie_".date("Ymd").".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$output = fopen("php://output", "w");
		
		fputcsv($output,
				array(
						utf8_decode('fief'),
						utf8_decode('groupe'),
						utf8_decode('Niveau d\'ordre social'),
						utf8_decode('Constructions'),
						utf8_decode('Constructions ajoutées'),
						utf8_decode('Résistance'),
						utf8_decode('Défense max'),
						utf8_decode('Défense actuelle'),
						utf8_decode('Changements'),
						utf8_decode('Horaires')
				), ';');
		
		foreach ( $territoires as $territoire) {
			$line = array();
			
			$line[] = utf8_decode($territoire->getNom());
			$groupe = $territoire->getGroupe();
			if ( $groupe )
			{
				$line[] = utf8_decode('#'.$groupe->getNumero().' '.$groupe->getNom());
			}
			else
			{
				$line[] = utf8_decode('Aucun');
			}
				
			$line[] = utf8_decode($territoire->getStatut());
			$line[] = utf8_decode(join(' - ',$territoire->getConstructions()->toArray()));
			$line[] = '';
			$line[] = utf8_decode($territoire->getResistance());
			$line[] = utf8_decode($territoire->getDefense());
			$line[] = utf8_decode($territoire->getDefense());
			$line[] = '';
			$line[] = '';
			
			fputcsv($output, $line, ';');
		}
		
		fclose($output);
		exit();
	}
}