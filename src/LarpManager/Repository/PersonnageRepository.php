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
use LarpManager\Entities\Personnage;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Repository\PersonnageRepository
 *  
 * @author kevin
 */
class PersonnageRepository extends EntityRepository
{
	/**
	 * Trouve le nombre de personnages correspondant aux critères de recherche
	 *
	 * @param array $criteria
	 * @param array $options
	 */
	public function findCount(array $criteria = array())
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select($qb->expr()->count('p'));
		$qb->from('LarpManager\Entities\Personnage','p');
	
		foreach ( $criteria as $critere )
		{
			$qb->andWhere($critere);
			
		}
	
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	/**
	 * Trouve les personnages correspondant aux critères de recherche
	 * 
	 * @param array $criteria
	 * @param array $order
	 * @param unknown $limit
	 * @param unknown $offset
	 */
	public function findList(array $criteria = array(), array $order = array(), $limit, $offset)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select('p');
		$qb->from('LarpManager\Entities\Personnage','p');
		
		foreach ( $criteria as $critere )
		{
			$qb->andWhere($critere);
		}
		
		$qb->setFirstResult($offset);
		$qb->setMaxResults($limit);
		$qb->orderBy('p.'.$order['by'], $order['dir']);
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * Find multiple personnage
	 * @param array $ids
	 */
	public function findByIds(array $ids)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select('p');
		$qb->from('LarpManager\Entities\Personnage','p');
		$qb->andWhere('p.id IN (:ids)')->setParameter('ids', $ids);
		
		return $qb->getQuery()->getResult();
	}
}