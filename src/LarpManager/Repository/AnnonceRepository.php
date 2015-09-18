<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\AnnonceRepository
 *
 * @author kevin
 */
class AnnonceRepository extends EntityRepository
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
		
		$qb->select($qb->expr()->count('a'));
		$qb->from('LarpManager\Entities\Annonce','a');
		
		foreach ( $criteria as $criter )
		{
			$qb->addWhere($criter);
		}
		
		return $qb->getQuery()->getSingleScalarResult();
	}
}
