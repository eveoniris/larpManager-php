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
use LarpManager\Entities\PersonnageLangues;
use LarpManager\Entities\PersonnageLignee;
use Doctrine\ORM\QueryBuilder;

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
     * @param array|null $order
     * @param int|null $limit
     * @param int|null $offset
     */
    public function findList(array $criteria = array(), ?array $order = array(), ?int $limit = null, ?int $offset = null)
    {
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
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->select('distinct p');
        $this->buildSearchFromJoinWhereQuery($qb, $criteria, $orderBy);
        
        if ($offset)
        {
            $qb->setFirstResult($offset);
        }
        if ($limit)
        {
            $qb->setMaxResults($limit);
        }
            
        if (!empty($orderBy))
        {      
            switch ($orderBy)
            {
                case 'groupe':
                    $qb->addOrderBy('gr.nom', $orderDir);
                    break;
                case 'classe':                    
                    $qb->addOrderBy('cl.label_feminin', $orderDir);
                    $qb->addOrderBy('cl.label_masculin', $orderDir);
                    $qb->addOrderBy('p.genre', $orderDir);
                    break;
                default:
                    $qb->orderBy('p.'.$orderBy, $orderDir);
            }          
        }
        
        return $qb->getQuery()->getResult();
    }
    
    
    private function buildSearchFromJoinWhereQuery(QueryBuilder $qb, array $criteria, ?string $orderBy = null)
    {
        $qb->from('LarpManager\Entities\Personnage','p');
        // jointure sur le dernier participant créé (on se base sur le max id des personnage participants)
        $qb->join('p.participants','pa', 'with', 'pa.id =
 (SELECT MAX(pa2.id) 
FROM LarpManager\Entities\Personnage p2 
LEFT JOIN p2.participants pa2
 WHERE p2 = p
)');
        if(array_key_exists("religion",$criteria))
        {
            $qb->join('p.personnagesReligions','ppr');
            $qb->join('ppr.religion','pr');
        }
        
        if(array_key_exists("classe",$criteria) || (!empty($orderBy) && $orderBy == 'classe'))
        {
            $qb->join('p.classe','cl');
        }
        
        if(array_key_exists("competence",$criteria))
        {
            $qb->join('p.competences','cmp');
        }
        
        if(array_key_exists("groupe",$criteria) || (!empty($orderBy) && $orderBy == 'groupe'))
        {
            // on rajoute la jointure sur le groupeGn -> groupe à partir du dernier participant
            $qb->join('pa.groupeGn','grgn');
            $qb->join('grgn.groupe','gr'); 
        }
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
     * Fourni la liste des descendants directs
     */
    public function findDescendants($personnage_id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('d');
        $qb->from(PersonnageLignee::class, 'd');
        $qb->join('d.personnage','p');
        $qb->where ('(d.parent1 = :parent OR d.parent2 = :parent)');
        $qb->orderBy('d.personnage','DESC');
        $qb->setParameter('parent', $personnage_id);

        return $qb->getQuery()->getResult();
    }

    /**
     * Fourni la liste des langues du personnage selon l'ordre :
     * Secret Non/Oui, Diffusion décroissante, libellé de A à Z.
     */
    public function findOrderedLangages(Personnage $personnage): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('pl');
        $qb->from(PersonnageLangues::class, 'pl');
        $qb->join('pl.langue','l');
        $qb->where('pl.personnage = :personnage_id');
        $qb->orderBy('l.secret','ASC');
        $qb->addOrderBy('l.diffusion','DESC');
        $qb->addOrderBy('l.label','ASC');
        $qb->setParameter('personnage_id', $personnage->getId());

        return $qb->getQuery()->getResult();
    }
  
}

