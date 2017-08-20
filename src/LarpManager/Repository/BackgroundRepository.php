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

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\BackgroundRepository
 *
 * @author kevin
 */
class BackgroundRepository extends EntityRepository
{

	/**
	 * Trouve les background correspondant aux critères de recherche
	 *
	 * @param array $criteria
	 * @param array $options
	 */
	public function findCount(array $criteria = array())
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select($qb->expr()->count('b'));
		$qb->from('LarpManager\Entities\Background','b');
		
		foreach ( $criteria as $criter )
		{
			$qb->addWhere($criter);
		}
		
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	public function findBackgrounds($gnId)
	{
		
		$backgrounds = $this->getEntityManager()
			->createQuery('SELECT b FROM LarpManager\Entities\Background b JOIN b.gn gn JOIN b.groupe g WHERE gn.id = 2 ORDER BY g.numero ASC')
			->getResult();
		
		return $backgrounds;
	}
}
