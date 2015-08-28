<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;
use LarpManager\Entities\Age;

/**
 * LarpManager\Repository\AgeRepository
 *  
 * @author kevin
 */
class AgeRepository extends EntityRepository
{
	/**
	 * Trouve tous les ages classé par index
	 * 
	 * @return ArrayCollection $classes
	 */
	public function findAllOrderedByLabel()
	{
		$ages = $this->getEntityManager()
				->createQuery('SELECT a FROM LarpManager\Entities\Age a ORDER BY a.label ASC')
				->getResult();
		
		return $ages;
	}
}