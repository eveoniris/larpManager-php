<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;
use LarpManager\Entities\User;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Repository\UserRepository
 *
 * @author kevin
 */
class UserRepository extends EntityRepository
{

	/**
	 * Trouve le nombre d'utilisateurs correspondant aux critères de recherche
	 *
	 * @param array $criteria
	 * @param array $options
	 */
	public function findCount(array $criteria = array())
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select($qb->expr()->count('u'));
		$qb->from('LarpManager\Entities\User','u');
		
		foreach ( $criteria as $criter )
		{
			$qb->andWhere($criter);
		}
		
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	/**
	 * Trouve les utilisateurs correspondant aux critères de recherche
	 * 
	 * @param array $criteria
	 * @param array $order
	 * @param unknown $limit
	 * @param unknown $offset
	 */
	public function findList(array $criteria = array(), array $order = array(), $limit, $offset)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select('u');
		$qb->from('LarpManager\Entities\User','u');
	
		foreach ( $criteria as $critere )
		{
			$qb->andWhere($critere);
		}
	
		$qb->setFirstResult($offset);
		$qb->setMaxResults($limit);
		$qb->orderBy('u.'.$order['by'], $order['dir']);
	
		return $qb->getQuery()->getResult();
	}
}
