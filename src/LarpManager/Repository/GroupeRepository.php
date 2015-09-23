<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;
use LarpManager\Entities\Groupe;

/**
 * LarpManager\Repository\GroupeRepository
 *  
 * @author kevin
 */
class GroupeRepository extends EntityRepository
{
	
	/**
	 * Trouve les annonces correspondant aux critères de recherche
	 *
	 * @param array $criteria
	 * @param array $options
	 */
	public function findCount(array $criteria = array())
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select($qb->expr()->count('g'));
		$qb->from('LarpManager\Entities\Groupe','g');
	
		foreach ( $criteria as $criter )
		{
			$qb->addWhere($criter);
		}
	
		return $qb->getQuery()->getSingleScalarResult();
	}

	/**
	 * Fourni la liste de tous les groupes classé par numéro
	 * 
	 * @return Collection LarpManager\Entities\Groupe $groupes
	 */
	public function findAllOrderByNumero()
	{
		$groupes = $this->getEntityManager()
						->createQuery('SELECT g FROM LarpManager\Entities\Groupe g ORDER BY g.numero ASC')
						->getResult();
		
		return $groupes;
	}

	/**
	 * Trouve un groupe en fonction de son code
	 * 
	 * @param string $code
	 * @return LarpManager\Entities\Groupe $groupe
	 */
	public function findOneByCode($code)
	{
		$groupes = $this->getEntityManager()
						->createQuery('SELECT g FROM LarpManager\Entities\Groupe g WHERE g.code = :code')
						->setParameter('code', $code)
						->getResult();
		
		return reset($groupes);
	}
}