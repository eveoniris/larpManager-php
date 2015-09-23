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
	 * Trouve les annonces correspondant aux critères de recherche
	 *
	 * @param array $criteria
	 * @param array $options
	 */
	public function findCount(array $criteria = array())
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select($qb->expr()->count('p'));
		$qb->from('LarpManager\Entities\Personnage','p');
	
		foreach ( $criteria as $criter )
		{
			$qb->addWhere($criter);
		}
	
		return $qb->getQuery()->getSingleScalarResult();
	}
}