<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\PotionRepository
 * 
 * @author kevin
 */
class PotionRepository extends EntityRepository
{
	/**
	 * Trouve toute les potions en fonction de leur niveau
	 * 
	 * @return ArrayCollection $sorts
	 */
	public function findByNiveau($niveau)
	{
		$potions = $this->getEntityManager()
				->createQuery('SELECT p FROM LarpManager\Entities\Potion p Where p.niveau = '.$niveau.' ORDER BY p.label ASC')
				->getResult();
		
		return $potions;
	}
}