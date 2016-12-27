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
use LarpManager\Entities\User;

/**
 * LarpManager\Repository\UserRepository
 *
 * @author kevin
 */
class UserRepository extends EntityRepository
{
	/**
	 * Fourni la liste des derniers utilisateurs connectés
	 */
	public function lastConnected()
	{
		$query = $this->getEntityManager()
			->createQuery('SELECT u FROM LarpManager\Entities\User u WHERE u.lastConnectionDate > CURRENT_TIMESTAMP() - 720 ORDER BY u.lastConnectionDate DESC');
		
		$users = $query->getArrayResult();
		return $users;		
	}
	
	/**
	 * Utilisateurs sans etat-civil
	 */
	public function findWithoutEtatCivil()
	{
		$query = $this->getEntityManager()
			->createQuery('SELECT u FROM LarpManager\Entities\User u WHERE IDENTITY(u.etatCivil) IS NULL ORDER BY u.email ASC');
		
		$users = $query->getResult();
		return $users;
	}
	
	/**
	 * Trouve le nombre d'utilisateurs correspondant aux critères de recherche
	 *
	 * @param array $criteria
	 * @param array $options
	 */
	public function findCount(array $criteria = array())
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select($qb->expr()->count('u'));
		$qb->from('LarpManager\Entities\User','u');
		
		foreach ( $criteria as $criter )
		{
			$qb->andWhere($criter);
		}
		
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	/**
	 * Trouve les utilisateurs correspondant aux critères de recherche
	 * 
	 * @param array $criteria
	 * @param array $order
	 * @param unknown $limit
	 * @param unknown $offset
	 */
	public function findList($type, $value, array $order = array(), $limit, $offset)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select('u');
		$qb->from('LarpManager\Entities\User','u');

		if ( $type && $value)
		{
			switch ($type){
				case 'username':			
					$qb->andWhere('u.username LIKE :value');
					$qb->setParameter('value', '%'.$value.'%');
					break;
				case 'nom':
					$qb->join('u.etatCivil','ec');
					$qb->andWhere('ec.nom LIKE :value');
					$qb->setParameter('value', '%'.$value.'%');
					break;
				case 'email':
					$qb->andWhere('u.email LIKE :value');
					$qb->setParameter('value', '%'.$value.'%');
					break;
				case 'id':
					$qb->andWhere('u.id = :value');
					$qb->setParameter('value', $value);
					break;
			}
			
		}
			
		$qb->setFirstResult($offset);
		$qb->setMaxResults($limit);
		$qb->orderBy('u.'.$order['by'], $order['dir']);
	
		return $qb->getQuery()->getResult();
	}
}
