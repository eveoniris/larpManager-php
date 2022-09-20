<?php

namespace LarpManager\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\ConnaissanceRepository
 * 
 * @author Kevin F.
 */
class ConnaissanceRepository extends EntityRepository
{
	/**
	 * @return ArrayCollection $Connaissance
	 */
	public function findByNiveau($niveau)
	{
		$connaissances = $this->getEntityManager()
				->createQuery('SELECT c FROM LarpManager\Entities\Connaissance c Where c.niveau = ?1 AND (c.secret = 0 OR c.secret IS NULL) ORDER BY c.label ASC')
				->setParameter(1, $niveau)
                ->getResult();
		
		return $connaissances;
	}

	/**
	 * @return ArrayCollection $religions
	 */
	public function findAllOrderedByLabel()
	{
		$connaissances = $this->getEntityManager()
				->createQuery('SELECT c FROM LarpManager\Entities\Connaissance c ORDER BY c.label ASC')
				->getResult();
		
		return $connaissances;
	}

	/**
	 * @return ArrayCollection $religions
	 */
	public function findAllPublicOrderedByLabel()
	{
		$connaissances = $this->getEntityManager()
				->createQuery('SELECT c FROM LarpManager\Entities\Connaissance c WHERE c.secret = 0 ORDER BY c.label ASC')
				->getResult();
		
		return $connaissances;
	}
}