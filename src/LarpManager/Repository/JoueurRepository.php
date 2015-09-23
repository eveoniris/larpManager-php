<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;
use LarpManager\Entities\Joueur;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Repository\JoueurRepository
 *  
 * @author kevin
 */
class JoueurRepository extends EntityRepository
{
	/**
	 * Recherche à partir du prénom
	 * 
	 * @return ArrayCollection $joueur
	 */
	public function findByFirstName($firstName)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select('j')
			->from('LarpManager\Entities\Joueur','j')
			->where($qb->expr()->like('j.prenom', $qb->expr()->literal('%'.$firstName.'%')))
			->orderBy('j.prenom','ASC');
		
		$result = $qb->getQuery()->getResult();
		return new ArrayCollection($result);
	}
	
	/**
	 * Recherche à partir du nom de famille
	 *
	 * @return ArrayCollection $joueur
	 */
	public function findByLastName($lastName)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select('j')
			->from('LarpManager\Entities\Joueur','j')
			->where($qb->expr()->like('j.nom', $qb->expr()->literal('%'.$lastName.'%')))
			->orderBy('j.nom','ASC');
	
		$result = $qb->getQuery()->getResult();
		return new ArrayCollection($result);
	}
}