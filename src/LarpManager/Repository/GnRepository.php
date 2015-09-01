<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;
use LarpManager\Entities\Gn;

/**
 * LarpManager\Repository\GnRepository
 *  
 * @author kevin
 */
class GnRepository extends EntityRepository
{
	/**
	 * Trouve tous les gns actifs
	 * 
	 * @return ArrayCollection $gns
	 */
	public function findAllActive()
	{
		$gns = $this->getEntityManager()
				->createQuery('SELECT g FROM LarpManager\Entities\Gn g WHERE g.actif = true ORDER BY g.date_debut ASC')
				->getResult();
		
		return $gns;
	}
}