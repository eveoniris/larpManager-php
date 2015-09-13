<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\ClasseRepository
 *  
 * @author kevin
 */
class ClasseRepository extends EntityRepository
{
	/**
	 * Find all classes ordered by label
	 * @return ArrayCollection $classes
	 */
	public function findAllOrderedByLabel()
	{
		$classes = $this->getEntityManager()
				->createQuery('SELECT c FROM LarpManager\Entities\Classe c ORDER BY c.label_masculin ASC')
				->getResult();
		
		return $classes;
	}
}