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
use LarpManager\Entities\Gn;
use LarpManager\Entities\Joueur;

/**
 * LarpManager\Repository\GnRepository
 *  
 * @author kevin
 */
class GnRepository extends EntityRepository
{
	
	/**
	 * Trouve le gn actif
	 */
	public function findGnActif()
	{	
		$gn = $this->getEntityManager()
			->createQuery('SELECT g FROM LarpManager\Entities\Gn g WHERE g.actif = true ORDER BY g.date_debut ASC')
			->getSingleResult();

		return $gn;
	}
	
	/**
	 * Trouve les gns correspondant aux critères de recherche
	 *
	 * @param array $criteria
	 */
	public function findCount(array $criteria = array())
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select($qb->expr()->count('g'));
		$qb->from('LarpManager\Entities\Gn','g');
	
		foreach ( $criteria as $criter )
		{
			$qb->addWhere($criter);
		}
	
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	/**
	 * Trouve tous les gns actifs
	 *
	 * @return ArrayCollection $gns
	 */
	public function findByActive()
	{
		$gns = $this->getEntityManager()
					->createQuery('SELECT g FROM LarpManager\Entities\Gn g WHERE g.actif = true ORDER BY g.date_debut ASC')
					->getResult();
		
		return $gns;
	}

	/**
	 * Trouve tous les gns actifs auquel le joueur n'est pas inscrit
	 *
	 * @param LarpManager\Entities\Joueur
	 * @return ArrayCollection $gns
	 */
	public function findByActiveWhereNotSubscribe(Joueur $joueur)
	{
		$gns = $this->getEntityManager()
					->createQuery('SELECT g FROM LarpManager\Entities\Gn g WHERE g.actif = true AND g.id NOT IN (SELECT IDENTITY(jg.gn) FROM LarpManager\Entities\JoueurGn jg WHERE IDENTITY(jg.joueur) = :joueurId ) ORDER BY g.date_debut ASC')
					->setParameter('joueurId', $joueur->getId())
					->getResult();
		
		return $gns;
	}
	
	/**
	 * Trouve tous les gns actifs auquel le joueur est inscrit
	 * 
	 * @param LarpManager\Entities\Joueur
	 * @return ArrayCollection $gns
	 */
	public function findByActiveWhereSubscribe(Joueur $joueur)
	{
		$gns = $this->getEntityManager()
					->createQuery('SELECT g FROM LarpManager\Entities\Gn g WHERE g.actif = true AND g.id IN (SELECT IDENTITY(jg.gn) FROM LarpManager\Entities\JoueurGn jg WHERE IDENTITY(jg.joueur) = :joueurId ) ORDER BY g.date_debut ASC')
					->setParameter('joueurId', $joueur->getId())
					->getResult();
		
		return $gns;		
	}
}