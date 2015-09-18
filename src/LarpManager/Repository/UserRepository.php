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
	 * Trouve les utilisateurs correspondant aux critères de recherche
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
			$qb->addWhere($criter);
		}
		
		return $qb->getQuery()->getSingleScalarResult();
	}
}
