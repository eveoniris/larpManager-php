<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\DocumentRepository
 *  
 * @author kevin
 */
class DocumentRepository extends EntityRepository
{
	/**
	 * Find all classes ordered by label
	 * @return ArrayCollection $classes
	 */
	public function findAllOrderedByCode()
	{
		$documents = $this->getEntityManager()
				->createQuery('SELECT d FROM LarpManager\Entities\Document d ORDER BY d.code ASC')
				->getResult();
		
		return $documents;
	}
}