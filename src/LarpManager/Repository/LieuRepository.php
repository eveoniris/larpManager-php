<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\LieuRepository
 * 
 * @author kevin
 */
class LieuRepository extends EntityRepository
{
	/**
	 * Trouve tous les lieux classé par ordre alphabétique
	 * @return ArrayCollection $classes
	 */
	public function findAllOrderedByNom()
	{
		$lieux = $this->getEntityManager()
			->createQuery('SELECT l FROM LarpManager\Entities\Lieu l ORDER BY l.nom ASC')
			->getResult();
	
		return $lieux;
	}
}