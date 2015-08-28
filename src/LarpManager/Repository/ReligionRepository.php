<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\ReligionRepository
 * 
 * @author kevin
 */
class ReligionRepository extends EntityRepository
{
	/**
	 * Find all religions ordered by label
	 * @return ArrayCollection $religions
	 */
	public function findAllOrderedByLabel()
	{
		$religions = $this->getEntityManager()
				->createQuery('SELECT r FROM LarpManager\Entities\Religion r ORDER BY r.label ASC')
				->getResult();
		
		return $religions;
	}
}