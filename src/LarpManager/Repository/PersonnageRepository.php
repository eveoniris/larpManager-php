<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;
use LarpManager\Entities\Personnage;
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
	
		$qb->select($qb->expr()->count('p'));
		$qb->from('LarpManager\Entities\Personnage','p');
	
		foreach ( $criteria as $critere )
		{
			$qb->andWhere($critere);
			
		}
	
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	/**
	 * Trouve les personnages correspondant aux critères de recherche
	 * 
	 * @param array $criteria
	 * @param array $order
	 * @param unknown $limit
	 * @param unknown $offset
	 */
	public function findList(array $criteria = array(), array $order = array(), $limit, $offset)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select('p');
		$qb->from('LarpManager\Entities\Personnage','p');
		
		foreach ( $criteria as $critere )
		{
			$qb->andWhere($critere);
		}
		
		$qb->setFirstResult($offset);
		$qb->setMaxResults($limit);
		$qb->orderBy('p.'.$order['by'], $order['dir']);
		
		return $qb->getQuery()->getResult();
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
}