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
use Silex\Application;

/**
 * LarpManager\Controllers\StockController
 *
 * @author kevin
 *
 */
class StockController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		
		$qb = $repo->createQueryBuilder('objet');
		$qb->select('COUNT(objet)');
		$objet_count = $qb->getQuery()->getSingleScalarResult();
		
		$qb = $repo->createQueryBuilder('o');
		$qb->select('COUNT(o)');
		$qb->where('o.proprietaire IS NULL');
		$objet_without_proprio_count = $qb->getQuery()->getSingleScalarResult();
		
		$qb = $repo->createQueryBuilder('o');
		$qb->select('COUNT(o)');
		$qb->where('o.userRelatedByResponsableId IS NULL');
		$objet_without_responsable_count = $qb->getQuery()->getSingleScalarResult();
		
		$qb = $repo->createQueryBuilder('o');
		$qb->select('COUNT(o)');
		$qb->where('o.rangement IS NULL');
		$objet_without_rangement_count = $qb->getQuery()->getSingleScalarResult();
		
		
		$last_add = $repo->findBy(array(), array('creation_date' => 'DESC'),10,0);
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etats = $repo->findAll();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Tag');
		$tags = $repo->findAll();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Localisation');
		$localisations = $repo->findAll();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Rangement');
		$rangements = $repo->findAll();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Proprietaire');
		$proprietaires = $repo->findAll();
		
		return $app['twig']->render('stock/index.twig', array(
				'objet_count' => $objet_count,
				'last_add' => $last_add,
				'objet_without_proprio_count' => $objet_without_proprio_count,
				'objet_without_responsable_count' => $objet_without_responsable_count,
				'objet_without_rangement_count' => $objet_without_rangement_count,
				'etats' => $etats,
				'tags' => $tags,
				'localisations' => $localisations,
				'rangements' => $rangements,
				'proprietaires' => $proprietaires,
		));
	}	
}