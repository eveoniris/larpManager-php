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
}