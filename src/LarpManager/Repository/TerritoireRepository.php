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
 * LarpManager\Repository\TerritoireRepository
 *  
 * @author kevin
 */
class TerritoireRepository extends EntityRepository
{
	/**
	 * Fourni la liste des territoires n'étant pas dépendant d'un autre territoire
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function findRoot()
	{
		$query = $this->getEntityManager()->createQuery('SELECT t FROM LarpManager\Entities\Territoire t WHERE t.territoire IS NULL ORDER BY t.nom ASC');
		$territoires = $query->getResult();
	
		return $territoires;
	}
	
	/**
	 * Fourni la liste des territoires étant dépendant d'un autre territoire et possédant des territoires
	 */
	public function findRegions()
	{
		$query = $this->getEntityManager()->createQuery('SELECT t FROM LarpManager\Entities\Territoire t  WHERE t.territoire IS NOT NULL ORDER BY t.nom ASC');
		$territoires = $query->getResult();
		
		$result = array();
		foreach ($territoires as $territoire )
		{
			if ($territoire->getTerritoires()->count() > 0 )
				$result[] = $territoire;
		}
	
		return $result;
	}
	
	/**
	 * Fourni la liste des territoires étant dépendant d'un autre territoire et ne possédant pas de territoires
	 */
	public function findFiefs()
	{
		$query = $this->getEntityManager()->createQuery('SELECT t FROM LarpManager\Entities\Territoire t  WHERE t.territoire IS NOT NULL ORDER BY t.nom ASC');
		$territoires = $query->getResult();
		
		$result = array();
		foreach ($territoires as $territoire )
		{
			if ($territoire->getTerritoires()->count() == 0 )
				$result[] = $territoire;
		}
	
		return $result;
	}

    /**
     * Trouve les fiefs correspondant aux critères de recherche
     *
     * @param array $criteria
     * @param array $order
     * @param unknown $limit
     * @param unknown $offset
     */
    public function findFiefsList(array $criteria = array(), array $order = array(), $limit, $offset)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('distinct t');
        $qb->from('LarpManager\Entities\Territoire','t');
        if(array_key_exists("groupe",$criteria)) $qb->join('t.groupe','tgr');
        if(array_key_exists("pays",$criteria))
        {
            $qb->join('t.territoire','tp');
            $qb->andWhere('tp.territoire IS NULL');
        }

        $qb->andWhere('t.territoire IS NOT NULL');

        foreach ( $criteria as $critere )
        {
            $qb->andWhere($critere);
        }

        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);
        $qb->orderBy('t.'.$order['by'], $order['dir']);

        return $qb->getQuery()->getResult();
    }
    /**
     * Trouve le nombre de fiefs correspondant aux critères de recherche
     *
     * @param array $criteria
     * @param array $options
     */
    public function findFiefsCount(array $criteria = array())
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select($qb->expr()->count('distinct t'));
        $qb->from('LarpManager\Entities\Territoire','t');
        if(array_key_exists("groupe",$criteria)) $qb->join('t.groupe','tgr');
        if(array_key_exists("pays",$criteria))
        {
            $qb->join('t.territoire','tp');
            $qb->andWhere('tp.territoire IS NULL');
        }
        $qb->andWhere('t.territoire IS NOT NULL');

        foreach ( $criteria as $critere )
        {
            $qb->andWhere($critere);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}