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
use LarpManager\Entities\Intrigue;

/**
 * LarpManager\Repository\IntrigueRepository
 *  
 * @author kevin
 */
class IntrigueRepository extends EntityRepository
{
	/**
	 * Recherche d'une liste d'intrigue
	 *
	 * @param unknown $type
	 * @param unknown $value
	 * @param array $order
	 * @param unknown $limit
	 * @param unknown $offset
	 */
	public function findList($type, $value, array $order = array(), $limit, $offset)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select('i');
		$qb->from('LarpManager\Entities\Intrigue','i');
		if ( $type && $value)
		{
			switch ($type){
				case 'titre':
					$qb->andWhere('i.titre LIKE :value');
					$qb->setParameter('value', '%'.$value.'%');
					break;
			}
		}
			
		$qb->setFirstResult($offset);
		$qb->setMaxResults($limit);
		$qb->orderBy('i.'.$order['by'], $order['dir']);
	
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * Trouve le nombre d'intrigue correspondant aux critères de recherche
	 *
	 * @param array $criteria
	 * @param array $options
	 */
	public function findCount($type, $value)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select($qb->expr()->count('i'));
		$qb->from('LarpManager\Entities\Intrigue','i');
	
		if ( $type && $value)
		{
			switch ($type){
				case 'titre':
					$qb->andWhere('i.titre LIKE :value');
					$qb->setParameter('value', '%'.$value.'%');
					break;
			}
		}
	
		return $qb->getQuery()->getSingleScalarResult();
	}
}