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
 * LarpManager\Repository\CompetenceRepository
 * @author kevin
 *
 */
class CompetenceRepository extends EntityRepository
{
	/**
	 * Find all competences ordered by label
	 * @return ArrayCollection $competences
	 */
	public function findAllOrderedByLabel()
	{
		$competences = $this->getEntityManager()
				->createQuery('SELECT c FROM LarpManager\Entities\Competence c JOIN c.competenceFamily cf JOIN c.level l ORDER BY cf.label ASC, l.index ASC')
				->getResult();
		
		return $competences;

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
    	    ->from('LarpManager\Entities\Competence', 'c')
    	    ->join('c.competenceFamily', 'cf')
    	    ->join('c.level', 'l')
    	    ->addOrderBy('cf.label')
            ->addOrderBy('l.index');
	    return $queryBuilder;	    
	}
}