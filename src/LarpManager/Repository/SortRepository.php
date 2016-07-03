<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\SortRepository
 * 
 * @author kevin
 */
class SortRepository extends EntityRepository
{
	/**
	 * Find all Apprenti sorts ordered by label
	 * @return ArrayCollection $sorts
	 */
	public function findByNiveau($niveau)
	{
		$sorts = $this->getEntityManager()
				->createQuery('SELECT s FROM LarpManager\Entities\Sort s Where s.niveau = '.$niveau.' ORDER BY s.label ASC')
				->getResult();
		
		return $sorts;
	}
}