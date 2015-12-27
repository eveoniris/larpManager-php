<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\PersonnageSecondaireRepository
 * 
 * @author kevin
 */
class PersonnageSecondaireRepository extends EntityRepository
{
	/**
	 * Trouve tous les personnages secondaires
	 * @return ArrayCollection $personnageSecondaire
	 */
	public function findAll()
	{
		$personnageSecondaires = $this->getEntityManager()
				->createQuery('SELECT ps FROM LarpManager\Entities\PersonnageSecondaire ps')
				->getResult();
		
		return $personnageSecondaires;
	}
}