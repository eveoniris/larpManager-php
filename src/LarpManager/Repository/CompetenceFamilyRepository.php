<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\CompetenceFamilyRepository
 * 
 * @author kevin
 */
class CompetenceFamilyRepository extends EntityRepository
{
	/**
	 * Find all classes ordered by label
	 * @return ArrayCollection $competenceFamilies
	 */
	public function findAllOrderedByLabel()
	{
		$competenceFamilies = $this->getEntityManager()
				->createQuery('SELECT cf FROM LarpManager\Entities\CompetenceFamily cf ORDER BY cf.label ASC')
				->getResult();
		
		return $competenceFamilies;
	}
}