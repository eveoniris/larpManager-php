<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\BackgroundRepository
 *
 * @author kevin
 */
class BackgroundRepository extends EntityRepository
{

	/**
	 * Trouve les background correspondant aux critères de recherche
	 *
	 * @param array $criteria
	 * @param array $options
	 */
	public function findCount(array $criteria = array())
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select($qb->expr()->count('b'));
		$qb->from('LarpManager\Entities\Background','b');
		
		foreach ( $criteria as $criter )
		{
			$qb->addWhere($criter);
		}
		
		return $qb->getQuery()->getSingleScalarResult();
	}
}
