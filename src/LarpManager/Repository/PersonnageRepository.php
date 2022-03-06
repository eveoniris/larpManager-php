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
use LarpManager\Services\Utilities;
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
        
        $qb->select($qb->expr()->count('distinct p'));
        $this->buildSearchFromJoinWhereQuery($qb, $criteria);
        
        return $qb->getQuery()->getSingleScalarResult();
    }
    
    
    /**
     * Trouve les personnages correspondant aux critères de recherche
     *
     * @param array $criteria
     * @param array $order
     * @param int|null $limit
     * @param int|null $offset
     */
    public function findList(array $criteria = array(), array $order = array(), $limit, $offset)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->select('distinct p');
        $this->buildSearchFromJoinWhereQuery($qb, $criteria);
        
        $orderBy = '';
        $orderDir = 'ASC';
        if ($order && array_key_exists('by', $order))
        {
            $orderBy = $order['by'];
            if (array_key_exists('dir', $order))
            {
                $orderDir = $order['dir'];
            }
        }
        
        // attention, il y a des propriétés sur lesquelles on ne peut pas appliquer le order by car elles ne sont pas en base mais calculées
        $orderByCalculatedFields = new ArrayCollection(['pugilat', 'heroisme', 'user', 'hasAnomalie', 'status']);
        if ($orderByCalculatedFields->contains($orderBy))
        {
            $resultArray = $qb->getQuery()->getResult();
            
            $sortByFunctionName = '';
            $isAsc = $orderDir == 'ASC';
            switch ($orderBy)
            {
                case 'pugilat':
                    $sortByFunctionName = $isAsc ? 'sortByPugilat': 'sortByPugilatDesc';
                    break;
                case 'heroisme':
                    $sortByFunctionName = $isAsc ? 'sortByHeroisme': 'sortByHeroismeDesc';
                    break;
                case 'user':
                    $sortByFunctionName = $isAsc ? 'sortByUser': 'sortByUserDesc';
                    break;
                case 'hasAnomalie':
                    $sortByFunctionName = $isAsc ? 'sortByHasAnomalie': 'sortByHasAnomalieDesc';
                    break;
                case 'status':
                    $sortByFunctionName = $isAsc ? 'sortByStatus': 'sortByStatusDesc';
                    break;
                default:
                    $sortByFunctionName = '';
            }
            
            Utilities::stable_uasort($resultArray, array($this, $sortByFunctionName));
            $resultCollection = new ArrayCollection($resultArray);
            return $resultCollection->slice($offset, $limit);
        }
        else
        {
            $qb->setFirstResult($offset);
            $qb->setMaxResults($limit);
            if (!empty($orderBy))
            {
                $qb->orderBy('p.'.$order['by'], $order['dir']);
            }
            return $qb->getQuery()->getResult();
        }
    }
    
    
    private function buildSearchFromJoinWhereQuery($qb, array $criteria)
    {
        $qb->from('LarpManager\Entities\Personnage','p');
        $qb->join('p.participants','pa');
        if(array_key_exists("religion",$criteria))
        {
            $qb->join('p.personnagesReligions','ppr');
            $qb->join('ppr.religion','pr');
        }
        if(array_key_exists("classe",$criteria)) $qb->join('p.classe','cl');
        if(array_key_exists("competence",$criteria)) $qb->join('p.competences','cmp');
        if(array_key_exists("groupe",$criteria)) $qb->join('p.groupe','gr');
        //		$qb->join('pa.gn','gn');
        
        // ajoute les conditions
        if(array_key_exists('classe',$criteria))
        {
            $qb->andWhere('cl.id = :classeId')->setParameter('classeId', $criteria['classe']);
        }
        if(array_key_exists('competence',$criteria))
        {
            $qb->andWhere('cmp.id = :competenceId')->setParameter('competenceId', $criteria['competence']);
        }
        if(array_key_exists('groupe',$criteria))
        {
            $qb->andWhere('gr.id = :groupeId')->setParameter('groupeId', $criteria['groupe']);
        }
        if(array_key_exists('religion',$criteria))
        {
            $qb->andWhere('pr.id = :religionId')->setParameter('religionId', $criteria['religion']);
        }
        if(array_key_exists('id',$criteria))
        {
            $qb->andWhere('p.id = :personnageId')->setParameter('personnageId', $criteria['id']);
        }
        if(array_key_exists('nom',$criteria))
        {
            $qb->andWhere('LOWER(p.nom) LIKE :nom OR LOWER(p.surnom) LIKE :nom')->setParameter('nom', '%'.$criteria['nom'].'%');
        }
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
    
    /**
     * Trouve tous les backgrounds liés aux personnages.
     * @param int $gnId
     */
    public function findBackgrounds($gnId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('pb');
        $qb->from('LarpManager\Entities\Participant','pa');
        $qb->join('pa.personnage', 'p');
        $qb->join('pa.groupeGn', 'gg');
        $qb->join('p.personnageBackground', 'pb');
        $qb->join('gg.gn', 'gn');
        $qb->andWhere('gn.id = :gnId')->setParameter('gnId', $gnId);
        return $qb->getQuery()->getResult();
        
    }
    
    
    
    function sortByPugilat(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortBy($a->getPugilat(), $b->getPugilat());
    }
    
    function sortByPugilatDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortByDesc($a->getPugilat(), $b->getPugilat());
    }
    
    function sortByHeroisme(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortBy($a->getHeroisme(), $b->getHeroisme());
    }
    
    function sortByHeroismeDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortByDesc($a->getHeroisme(), $b->getHeroisme());
    }
    
    function sortByUser(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortBy($a->getUserFullName(), $b->getUserFullName());
    }
    
    function sortByUserDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortByDesc($a->getUserFullName(), $b->getUserFullName());
    }
    
    function sortByHasAnomalie(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortBy($a->hasAnomalie(), $b->hasAnomalie());
    }
    
    function sortByHasAnomalieDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortByDesc($a->hasAnomalie(), $b->hasAnomalie());
    }
    
    function sortByStatus(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortBy($a->getStatusCode(), $b->getStatusCode());
    }
    
    function sortByStatusDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortByDesc($a->getStatusCode(), $b->getStatusCode());
    }
}