<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\ReligionLevelRepository
 * 
 * @author kevin
 */
class ReligionLevelRepository extends EntityRepository
{
	/**
	 * trouve tous les niveaux de religion classé par index
	 * @return ArrayCollection $religionLevels
	 */
	public function findAllOrderedByIndex()
	{
		$religionLevels = $this->getEntityManager()
				->createQuery('SELECT rl FROM LarpManager\Entities\ReligionLevel rl ORDER BY rl.index ASC')
				->getResult();
		
		return $religionLevels;
	}
}