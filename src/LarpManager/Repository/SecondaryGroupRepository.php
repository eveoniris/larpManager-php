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
use LarpManager\Entities\SecondaryGroup;

/**
 * LarpManager\Repository\SecondaryGroupRepository
 *  
 * @author kevin
 */
class SecondaryGroupRepository extends EntityRepository
{
	/**
	 * Trouve tous les groupes secondaire publics
	 */
	public function findAllPublic()
	{
		$groupes = $this->getEntityManager()
			->createQuery('SELECT g FROM LarpManager\Entities\SecondaryGroup g WHERE g.secret = false or g.secret is null')
			->getResult();
	
		return $groupes;
	}

	/**
	 * Trouve les groupes secondaires correspondants aux critères de recherche
	 * 
	 * @param array $criteria
	 * @param array $order
	 * @param unknown $limit
	 * @param unknown $offset
	 */
	public function findList(array $criteria = array(), array $order = array(), $limit = 50, $offset = 0)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('distinct g');
		$qb->from('LarpManager\Entities\SecondaryGroup','g');
		foreach ( $criteria as $critere )
		{
			$qb->andWhere('?1');
            $qb->setParameter(1, $critere);
		}

		$qb->setFirstResult($offset);
		$qb->setMaxResults($limit);
		$qb->orderBy('g.'.$order['by'], $order['dir']);
		return $qb->getQuery()->getResult();
	}	

	/**
	 * Compte les groupes secondaires correspondants aux critères de recherche
	 *
	 * @param array $criteria
	 * @param array $options
	 */
	public function findCount(array $criteria = array())
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select($qb->expr()->count('g'));
		$qb->from('LarpManager\Entities\SecondaryGroup','g');
	
		foreach ( $criteria as $critere )
		{
            $qb->andWhere('?1');
            $qb->setParameter(1, $critere);
		}
	
		return $qb->getQuery()->getSingleScalarResult();
	}

}