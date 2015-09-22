<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;
use LarpManager\Entities\SecondaryGroup;

/**
 * LarpManager\Repository\SecondaryGroupRepository
 *  
 * @author kevin
 */
class SecondaryGroupRepository extends EntityRepository
{
	
	/**
	 * Trouve les groupes secondaires correspondant aux critères de recherche
	 *
	 * @param array $criteria
	 * @param array $options
	 */
	public function findCount(array $criteria = array())
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select($qb->expr()->count('g'));
		$qb->from('LarpManager\Entities\SecondaryGroup','g');
	
		foreach ( $criteria as $criter )
		{
			$qb->addWhere($criter);
		}
	
		return $qb->getQuery()->getSingleScalarResult();
	}

}