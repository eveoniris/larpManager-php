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
use LarpManager\Entities\Joueur;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Repository\JoueurRepository
 *  
 * @author kevin
 */
class JoueurRepository extends EntityRepository
{
	/**
	 * Recherche à partir du prénom
	 * 
	 * @return ArrayCollection $joueur
	 */
	public function findByFirstName($firstName)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select('j')
			->from('LarpManager\Entities\Joueur','j')
			->where($qb->expr()->like('j.prenom', $qb->expr()->literal('%'.$firstName.'%')))
			->orderBy('j.prenom','ASC');
		
		$result = $qb->getQuery()->getResult();
		return new ArrayCollection($result);
	}
	
	/**
	 * Recherche à partir du nom de famille
	 *
	 * @return ArrayCollection $joueur
	 */
	public function findByLastName($lastName)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select('j')
			->from('LarpManager\Entities\Joueur','j')
			->where($qb->expr()->like('j.nom', $qb->expr()->literal('%'.$lastName.'%')))
			->orderBy('j.nom','ASC');
	
		$result = $qb->getQuery()->getResult();
		return new ArrayCollection($result);
	}
}