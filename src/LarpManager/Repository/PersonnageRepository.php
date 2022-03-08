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
            
            Utilities::stable_uasort($resultArray, array('LarpManager\Repository\PersonnageRepository', $sortByFunctionName));
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
            $qb->andWhere('p.nom LIKE :nom OR p.surnom LIKE :nom')->setParameter('nom', '%'.$criteria['nom'].'%');
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
    
    /**
     * Tri sur Pugilat
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByPugilat(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortBy($a->getPugilat(), $b->getPugilat());
    }
    
    /**
     * Tri sur Pugilat Desc
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByPugilatDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return self::sortByPugilat($b, $a);
    }
    
    /**
     * Tri sur Heroisme
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByHeroisme(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortBy($a->getHeroisme(), $b->getHeroisme());
    }
    
    /**
     * Tri sur Heroisme Desc
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByHeroismeDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return self::sortByHeroisme($b, $a);
    }
    
    /**
     * Tri sur User Full Name
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByUser(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortBy($a->getUserFullName(), $b->getUserFullName());
    }
    
    /**
     * Tri sur User Full Name Desc
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByUserDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return self::sortByUser($b, $a);
    }
    
    /**
     * Tri sur HasAnomalie
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByHasAnomalie(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortBy($a->hasAnomalie(), $b->hasAnomalie());
    }
    
    /**
     * Tri sur HasAnomalieDesc
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByHasAnomalieDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return self::sortByHasAnomalie($b, $a);
    }
    
    /**
     * Tri sur Status Code
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByStatusCode(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortBy($a->getStatusCode(), $b->getStatusCode());
    }
    
    /**
     * Tri sur Status Code Desc
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByStatusCodeDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return self::sortByStatus($b, $a);
    }
    
    /**
     * Tri sur Status On Active GN 
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByStatusOnActiveGn(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortBy($a->getStatusOnActiveGnCode(), $b->getStatusOnActiveGnCode());
    }
    
    /**
     * Tri sur Status On Active GN Desc
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByStatusOnActiveGnDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return self::sortByStatusOnActiveGn($b, $a);
    }
    
    /**
     * Tri sur Nom
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByNom(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return Utilities::sortBy($a->getNom(), $b->getNom());
    }
    
    /**
     * Tri sur Nom Desc
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByNomDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return self::sortByNom($b, $a);
    }
    
    /**
     * Tri sur Status GN, du + récent (+ grand) au - récent (+ petit) puis par nom ASC
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByStatusGn(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        $aStatus = $a->getStatusGnCode();
        $bStatus = $b->getStatusGnCode();
        if ($aStatus == $bStatus) {
            return self::sortByNom($a, $b);
        }
        // on prend le statut à l'envers, ici 0 = mort donc on veut plutôt du + grand au + petit
        return ($aStatus > $bStatus) ? -1 : 1;
    }
    
    /**
     * Tri sur Status GN DESC, du - récent (+ petit) au + récent (+ grand) puis par nom DESC
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByStatusGnDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return self::sortByStatusGn($b, $a);
    }
        
    /**
     * Tri sur Last Participant GN Number, du + récent (+ grand) au - récent (+ petit) puis par nom ASC
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByLastParticipantGnNumber(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        $aStatus = $a->getLastParticipantGnNumber();
        $bStatus = $b->getLastParticipantGnNumber();
        if ($aStatus == $bStatus) {
            return self::sortByNom($a, $b);
        }
        // on prend le statut à l'envers, ici 0 = mort donc on veut plutôt du + grand au + petit
        return ($aStatus > $bStatus) ? -1 : 1;
        //return Utilities::sortBy($a->getLastParticipantGnNumber(), $b->getLastParticipantGnNumber());
    }
    
    /**
     * Tri sur Last Participant GN Number DESC, du - récent (+ petit) au + récent (+ grand) puis par nom DESC
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByLastParticipantGnNumberDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return self::sortByLastParticipantGnNumber($b, $a);
    }
    
    /**
     * Tri sur Status :
     * - d'abord les PJs vivants sur le GN actif, 
     * - puis les PNJ, 
     * - puis les PJ anciens, 
     * - puis les morts, 
     * et pour chaque groupe, du + récent gn (+ grand) au - récent (+ petit) puis par nom ASC 
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByStatus(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        $aStatus = $a->getStatusOnActiveGnCode();
        $bStatus = $b->getStatusOnActiveGnCode();
        
        // si les 2 sont pnj ou les 2 sont morts, on se base sur le gn
        if ($a->isPnj() && $b->isPnj() || !$a->getVivant() && !$b->getVivant())
        {
            return self::sortByLastParticipantGnNumber($a, $b);
        }
        if ($aStatus == $bStatus) {
            return self::sortByStatusGn($a, $b);
        }
        // on prend le statut à l'envers, ici 0 = mort donc on veut plutôt du + grand au + petit
        return ($aStatus > $bStatus) ? -1 : 1;
    }
    
    /**
     * Tri sur Status DESC:
     * - d'abord les morts,
     * - puis les PJ anciens, 
     * - puis les PNJ, 
     * - puis les PJs vivants sur le GN actif
     * et pour chaque groupe, du - récent gn (+ petit) au + récent (+ grand) puis par nom DESC
     * @param \LarpManager\Entities\Personnage $a
     * @param \LarpManager\Entities\Personnage $b
     * @return number
     */
    public static function sortByStatusDesc(\LarpManager\Entities\Personnage $a, \LarpManager\Entities\Personnage $b)
    {
        return self::sortByStatusAdvanced($b,$a);
    }
}