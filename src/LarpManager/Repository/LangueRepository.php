<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\LangueRepository
 * 
 * @author kevin
 */
class LangueRepository extends EntityRepository
{
	/**
	 * Find all langues ordered by label
	 * @return ArrayCollection $langues
	 */
	public function findAllOrderedByLabel()
	{
		$langues = $this->getEntityManager()
				->createQuery('SELECT l FROM LarpManager\Entities\Langue l ORDER BY l.label ASC')
				->getResult();
		
		return $langues;

	}
}