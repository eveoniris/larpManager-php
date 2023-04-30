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
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * LarpManager\Repository\PotionRepository
 * 
 * @author kevin
 */
class PotionRepository extends EntityRepository
{
	/**
	 * Trouve toute les potions en fonction de leur niveau
	 * 
	 * @return ArrayCollection $sorts
	 */
	public function findByNiveau($niveau)
	{
		$potions = $this->getEntityManager()
				->createQuery('SELECT p FROM LarpManager\Entities\Potion p Where p.niveau = ?1 and (p.secret = false or p.secret is null) ORDER BY p.label ASC')
                ->setParameter(1, $niveau)
				->getResult();
		
		return $potions;
	}

    /**
     * Trouve le nombre de potions correspondant aux critères de recherche
     *
     * @param ?string $type
     */
    public function findCount(?string $type, $value)
    {
        $qb = $this->getQueryBuilder($type, $value);
        $qb->select($qb->expr()->count('p'));

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Trouve les potions correspondant aux critères de recherche
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
        $qb->orderBy('p.'.$order['by'], $order['dir']);

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

        $qb->select('p');
        $qb->from('LarpManager\Entities\Potion','p');

        // retire les caractères non imprimable d'une chaine UTF-8
        $value = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', htmlspecialchars($value));

        if ($type && $value)
        {
            switch ($type){
                case 'label':
                    $qb->andWhere('p.label LIKE :value');
                    $qb->setParameter('value', '%'.$value.'%');
                    break;
                case 'description':
                    $qb->andWhere('p.description LIKE :value');
                    $qb->setParameter('value', '%'.$value.'%');
                    break;
                case 'numero':
                    $qb->andWhere('p.numero = :value');
                    $qb->setParameter('value', (int) $value);
                    break;
                case 'id':
                    $qb->andWhere('p.id = :value');
                    $qb->setParameter('value', (int) $value);
                    break;
            }
        }

        return $qb;
    }
}