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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * LarpManager\Repository\ClasseRepository
 *  
 * @author kevin
 */
class ClasseRepository extends EntityRepository
{
	/**
	 * Find all classes ordered by label
	 * @return ArrayCollection $classes
	 */
	public function findAllOrderedByLabel()
	{
		$classes = $this->getEntityManager()
				->createQuery('SELECT c FROM LarpManager\Entities\Classe c ORDER BY c.label_masculin ASC')
				->getResult();
		
		return $classes;
	}
	
	
	/**
	 * Returns a query builder to find all competences ordered by label
	 * @return QueryBuilder $competences
	 */
	public function getQueryBuilderFindAllOrderedByLabel() : QueryBuilder
	{
	    $queryBuilder = $this->getEntityManager()
	    ->createQueryBuilder()
	    ->select('c')
	    ->from('LarpManager\Entities\Classe', 'c')
	    ->addOrderBy('c.label_feminin')
	    ->addOrderBy('c.label_masculin');
	    return $queryBuilder;	    
	}
	
	/**
	 * Trouve toutes les classes disponibles à la création d'un personnage
	 */
	public function findAllCreation()
	{
		$classes = $this->getEntityManager()
			->createQuery('SELECT c FROM LarpManager\Entities\Classe c WHERE c.creation = true ORDER BY c.label_masculin ASC')
			->getResult();
		
		return $classes;
	}
}