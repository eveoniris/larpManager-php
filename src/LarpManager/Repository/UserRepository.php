<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;
use LarpManager\Entities\User;

/**
 * LarpManager\Repository\UserRepository
 *
 * @author kevin
 */
class UserRepository extends EntityRepository
{
	/**
	 * Utilisateurs sans etat-civil
	 */
	public function findWithoutEtatCivil()
	{
		$query = $this->getEntityManager()
			->createQuery('SELECT u FROM LarpManager\Entities\User u WHERE IDENTITY(u.etatCivil) IS NULL ORDER BY u.email ASC');
		
		$users = $query->getResult();
		return $users;
	}

	/**
	 * Utilisateurs sans trombine
	 */
	public function findWithoutTrombine()
	{
		$query = $this->getEntityManager()
			->createQuery('SELECT u FROM LarpManager\Entities\User u WHERE u.trombineUrl IS NULL ORDER BY u.email ASC');
	
		$users = $query->getResult();
		return $users;
	}
	
	/**
	 * Utilisateurs sans groupes
	 */
	public function findWithoutGroup()
	{
		$query = $this->getEntityManager()
			->createQuery('SELECT u FROM LarpManager\Entities\User u 
						LEFT JOIN u.participants p
						WHERE  IDENTITY(p.groupe) IS NULL ORDER BY u.email ASC');
		
		$users = $query->getResult();
		return $users;
	}
	
	/**
	 * Utilisateurs sans personnages
	 */
	public function findWithoutPersonnage()
	{
		$query = $this->getEntityManager()
			->createQuery('SELECT u FROM LarpManager\Entities\User u 
						LEFT JOIN u.participants p
						LEFT JOIN p.personnage perso
						WHERE IDENTITY(p.groupe) IS NOT NULL 
							AND perso.id IS NULL
							 ORDER BY u.email ASC');
		
		$users = $query->getResult();
		return $users;
	}
	
	/**
	 * Utilisateurs sans personnages secondaires
	 */
	public function findWithoutSecondaryPersonnage()
	{
		$query = $this->getEntityManager()
			->createQuery('SELECT u FROM LarpManager\Entities\User u
						LEFT JOIN u.participants p
						WHERE IDENTITY(p.personnageSecondaire) IS NULL ORDER BY u.email ASC');
		
		$users = $query->getResult();
		return $users;
	}
	
	/**
	 * Trouve le nombre d'utilisateurs correspondant aux critères de recherche
	 *
	 * @param array $criteria
	 * @param array $options
	 */
	public function findCount(array $criteria = array())
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		
		$qb->select($qb->expr()->count('u'));
		$qb->from('LarpManager\Entities\User','u');
		
		foreach ( $criteria as $criter )
		{
			$qb->andWhere($criter);
		}
		
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	/**
	 * Trouve les utilisateurs correspondant aux critères de recherche
	 * 
	 * @param array $criteria
	 * @param array $order
	 * @param unknown $limit
	 * @param unknown $offset
	 */
	public function findList(array $criteria = array(), array $order = array(), $limit, $offset)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
	
		$qb->select('u');
		$qb->from('LarpManager\Entities\User','u');
	
		foreach ( $criteria as $critere )
		{
			$qb->andWhere($critere);
		}
	
		$qb->setFirstResult($offset);
		$qb->setMaxResults($limit);
		$qb->orderBy('u.'.$order['by'], $order['dir']);
	
		return $qb->getQuery()->getResult();
	}
}
