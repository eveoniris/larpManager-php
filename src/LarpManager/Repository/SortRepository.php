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
 * LarpManager\Repository\SortRepository
 * 
 * @author kevin
 */
class SortRepository extends EntityRepository
{
	/**
	 * Find all Apprenti sorts ordered by label
	 * @return ArrayCollection $sorts
	 */
	public function findByNiveau($niveau)
	{
		$sorts = $this->getEntityManager()
				->createQuery('SELECT s FROM LarpManager\Entities\Sort s Where s.niveau = ?1 AND (s.secret = 0 OR s.secret IS NULL) ORDER BY s.label ASC')
				->setParameter(1, $niveau)
                ->getResult();
		
		return $sorts;
	}
    /**
     * Trouve le nombre de sorts correspondant aux critères de recherche
     *
     * @param ?string $type
     */
    public function findCount(?string $type, $value)
    {
        $qb = $this->getQueryBuilder($type, $value);
        $qb->select($qb->expr()->count('s'));

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Trouve les sorts correspondant aux critères de recherche
     *
     * @param ?string $type
     * @param array $order
     * @param int $limit
     * @param int $offset
     */
    public function findList(?string $type, $value, array $order = [], int $limit = 50, int $offset = 0)
    {
        $qb = $this->getQueryBuilder($type, $value);
        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);
        $qb->orderBy('s.'.$order['by'], $order['dir']);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string|null $type
     * @param $value
     * @return QueryBuilder
     */
    protected function getQueryBuilder(?string $type, $value): QueryBuilder
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('s');
        $qb->from('LarpManager\Entities\Sort','s');

        // retire les caractères non imprimable d'une chaine UTF-8
        $value = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', htmlspecialchars($value));

        if ($type && $value)
        {
            switch ($type){
                case 'label':
                    $qb->andWhere('s.label LIKE :value');
                    $qb->setParameter('value', '%'.$value.'%');
                    break;
                case 'domaine':
                    $qb->join('s.domaine','d');
                    $qb->andWhere('d.label LIKE :value');
                    $qb->setParameter('value', '%'.$value.'%');
                    break;
                case 'description':
                    $qb->andWhere('s.description LIKE :value');
                    $qb->setParameter('value', '%'.$value.'%');
                    break;
                case 'id':
                    $qb->andWhere('s.id = :value');
                    $qb->setParameter('value', (int) $value);
                    break;
            }

        }

        return $qb;
    }
}