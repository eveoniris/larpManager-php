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
use LarpManager\Entities\Groupe;

/**
 * LarpManager\Repository\GroupeRepository
 *  
 * @author kevin
 */
class GroupeRepository extends EntityRepository
{
	
	public function findAll()
	{
		return $this->findBy(array(), array('nom' => 'ASC'));
	}
	
	/**
	 * Trouve les annonces correspondant aux critères de recherche
	 *
	 * @param array $criteria
	 * @param array $options
	 */
	public function findCount(array $criteria = array())
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select($qb->expr()->count('g'));
		$qb->from('LarpManager\Entities\Groupe','g');
	
		foreach ( $criteria as $criter )
		{
			$qb->addWhere($criter);
		}
	
		return $qb->getQuery()->getSingleScalarResult();
	}

	/**
	 * Fourni la liste de tous les groupes classé par numéro
	 * 
	 * @return Collection LarpManager\Entities\Groupe $groupes
	 */
	public function findAllOrderByNumero()
	{
		$groupes = $this->getEntityManager()
						->createQuery('SELECT g FROM LarpManager\Entities\Groupe g ORDER BY g.numero ASC')
						->getResult();
		
		return $groupes;
	}

	/**
	 * Trouve un groupe en fonction de son code
	 * 
	 * @param string $code
	 * @return LarpManager\Entities\Groupe $groupe
	 */
	public function findOneByCode($code)
	{
		$groupes = $this->getEntityManager()
						->createQuery('SELECT g FROM LarpManager\Entities\Groupe g WHERE g.code = :code')
						->setParameter('code', $code)
						->getResult();
		
		return reset($groupes);
	}
}