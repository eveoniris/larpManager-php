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
use Doctrine\ORM\QueryBuilder;

/**
 * LarpManager\Repository\ObjetRepository
 *
 * @author kevin
 */
class ObjetRepository extends EntityRepository
{
    public const CRIT_WITHOUT = -1;

    /**
     * Trouve tous les objets
     */
    public function findAll()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('o');
        $qb->from('LarpManager\Entities\Objet', 'o');
        $qb->orderBy('o.id', 'DESC');
        return $qb->getQuery()->getResult();
    }

    /**
     * Trouve le nombre d'objets correspondant aux critères de recherche
     */
    public function findCount(array $criteria)
    {
        $qb = $this->getQueryBuilder($criteria);
        $qb->select($qb->expr()->count('distinct o'));

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Trouve les objets correspondant aux critères de recherche
     */
    public function findList(array $criteria, array $order = [], int $limit = 50, int $offset = 0)
    {
        $qb = $this->getQueryBuilder($criteria);
        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);
        $qb->orderBy('o.' . $order['by'], $order['dir']);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array $criteria
     * @return QueryBuilder
     */
    protected function getQueryBuilder(array $criteria): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('o');
        $qb->from('LarpManager\Entities\Objet', 'o', 'o');
        $qb->join('o.photo', 'p');

        $allowed = ['nom', 'description', 'numero'];
        foreach ($allowed as $field) {
            if ($criteria[$field] ?? false) {
                $qb->andWhere('o.' . $field . ' LIKE :field ');
                $qb->setParameter('field', '%' . $criteria[$field] . '%');
            }
        }

        if ($criteria['tag'] ?? false) {
            $criter = $criteria['tag'];

            if (is_numeric($criter)) {
                $criter = (int)$criter;

                if ($criter === -1) {
                    $qb->leftjoin('o.tags', 't');
                    $qb->where('t.id is null');
                } else {
                    $qb->join('o.tags', 't');
                    $qb->where('t.id = :tag');
                    $qb->setParameter('tag', $criter);
                }
            } else {
                $qb->join('o.tags', 't');
                $qb->where('t.nom LIKE :tag');
                $qb->setParameter('tag', $criter);
            }
        }

        if ($criteria['numero'] ?? false) {
            $qb->andWhere('o.numero LIKE :numero');
            $qb->setParameter('numero', $criteria['numero']);
        }

        if (isset($criteria['rangement'])) {
            $criter = $criteria['rangement'];

            if (is_numeric($criter)) {
                $criter = (int)$criter;
                if ($criter === -1) {
                    $qb->leftjoin('o.rangement', 'r');
                    $qb->where('r.id is null');
                } else {
                    $qb->where('o.rangement = :rangement');
                    $qb->setParameter('rangement', $criter);
                }
            } else {
                $qb->join('o.rangement', 'r');
                $qb->where('r.label LIKE :rangement');
                $qb->setParameter('rangement', $criter);
            }
        }

        return $qb;
    }
}