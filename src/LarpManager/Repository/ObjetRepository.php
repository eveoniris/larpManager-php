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
 * LarpManager\Repository\ObjetRepository
 * 
 * @author kevin
 */
class ObjetRepository extends EntityRepository
{
	/**
	 * Trouve tous les objets
	 */
	public function findAll()
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select('o');
		$qb->from('LarpManager\Entities\Objet','o');
		$qb->orderBy('o.id','DESC');
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * Trouve tous les objets correspondant au tag
	 * @return ArrayCollection $classes
	 */
	public function findByTag($tag)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select('o');
		$qb->from('LarpManager\Entities\Objet','o');
		$qb->join('o.photo','p');
		$qb->join('o.tags','t');
		$qb->where('t.nom LIKE :tag');
		$qb->orderBy('o.id','DESC');
		$qb->setParameter('tag', $tag->getNom());
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * Trouve tous les objets sans tag
	 * @return ArrayCollection $classes
	 */
	public function findWithoutTag()
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select('o');
		$qb->from('LarpManager\Entities\Objet','o');
		$qb->orderBy('o.id','DESC');
		$qb->leftjoin('o.tags','t');
		$qb->where('t.id is null');
				
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * Trouve tous les objets correspondant au tag
	 * @return ArrayCollection $classes
	 */
	public function findByRangement($rangement)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select('o');
		$qb->from('LarpManager\Entities\Objet','o');
		$qb->join('o.rangement','r');
		$qb->where('r.id = :rangement');
		$qb->orderBy('o.id','DESC');
		$qb->setParameter('rangement', $rangement->getId());
	
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * Trouve tous les objets sans rangement
	 * @return ArrayCollection $classes
	 */
	public function findWithoutRangement()
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select('o');
		$qb->from('LarpManager\Entities\Objet','o');
		$qb->leftjoin('o.rangement','r');
		$qb->orderBy('o.id','DESC');
		$qb->where('r.id is null');
	
		return $qb->getQuery()->getResult();
	}
}