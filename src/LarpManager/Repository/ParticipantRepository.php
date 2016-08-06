<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\ParticipantRepository
 * @author kevin
 *
 */
class ParticipantRepository extends EntityRepository
{
	/**
	 * Find all participant ordered by username
	 * @return ArrayCollection $competences
	 */
	public function findAllOrderedByUsername()
	{
		$participants = $this->getEntityManager()
				->createQuery('SELECT p FROM LarpManager\Entities\Participant p JOIN p.user u JOIN u.etatCivil ec ORDER BY ec.nom ASC, ec.prenom ASC')
				->getResult();
		
		return $participants;
	}
	
	/**
	 * Find all participant ordered by username
	 * @return ArrayCollection $competences
	 */
	public function findAllOrderedByUsernameGroupe()
	{
		$participants = $this->getEntityManager()
		->createQuery('SELECT p FROM LarpManager\Entities\Participant p JOIN p.user u JOIN u.etatCivil ec WHERE p.groupe IS NOT NULL ORDER BY ec.nom ASC, ec.prenom ASC')
		->getResult();
	
		return $participants;
	}
	
	/**
	 * Find all participant ordered by username
	 * @return ArrayCollection $competences
	 */
	public function findAllOrderedByUsernameNonGroupe()
	{
		$participants = $this->getEntityManager()
		->createQuery('SELECT p FROM LarpManager\Entities\Participant p JOIN p.user u JOIN u.etatCivil ec WHERE p.groupe IS NULL ORDER BY ec.nom ASC, ec.prenom ASC')
		->getResult();
	
		return $participants;
	}
	
	/**
	 * Find all participant ordered by username
	 * @return ArrayCollection $competences
	 */
	public function findAllOrderedByUsernameGroupeFedegn()
	{
		$participants = $this->getEntityManager()
		->createQuery('SELECT p FROM LarpManager\Entities\Participant p JOIN p.user u JOIN u.etatCivil ec LEFT JOIN p.groupe g WHERE p.groupe IS NOT NULL AND g.pj = true AND ec.fedegn IS NOT NULL AND REGEXP(ec.fedegn, \'^[A-Za-z0-9]{5}\') = true ORDER BY ec.nom ASC, ec.prenom ASC')
		->getResult();
	
		return $participants;
	}
	

	/**
	 * Find all participant ordered by username
	 * @return ArrayCollection $competences
	 */
	public function findAllOrderedByUsernameGroupeNonFedegn()
	{
		$participants = $this->getEntityManager()
		->createQuery('SELECT p FROM LarpManager\Entities\Participant p JOIN p.user u JOIN u.etatCivil ec LEFT JOIN p.groupe g WHERE p.groupe IS NOT NULL AND g.pj = true AND (ec.fedegn IS NULL OR REGEXP(ec.fedegn, \'^[A-Za-z0-9]{5}\') = false) ORDER BY ec.nom ASC, ec.prenom ASC')
		->getResult();
	
		return $participants;
	}
	
	/**
	 * Find all participant ordered by username
	 * @return ArrayCollection $competences
	 */
	public function findAllOrderedByUsernameGroupePNJNonFedegn()
	{
		$participants = $this->getEntityManager()
		->createQuery('SELECT p FROM LarpManager\Entities\Participant p JOIN p.user u JOIN u.etatCivil ec LEFT JOIN p.groupe g WHERE p.groupe IS NOT NULL AND g.pj = false AND (ec.fedegn IS NULL OR REGEXP(ec.fedegn, \'^[A-Za-z0-9]{5}\') = false) ORDER BY ec.nom ASC, ec.prenom ASC')
		->getResult();
	
		return $participants;
	}
	
	/**
	 * Fourni tous les participants inscrit dans une taverne
	 * @param unknown $tavernId
	 */
	public function findAllByTavern($tavernId)
	{
		$participants = $this->getEntityManager()
			->createQuery('SELECT p FROM LarpManager\Entities\Participant p JOIN p.user u JOIN u.etatCivil ec WHERE p.tavern_id = '.$tavernId.' ORDER BY ec.nom ASC, ec.prenom ASC')
			->getResult();
		
		return $participants;
	}
	
	/**
	 * Fourni tous les participants n'ayant pas de groupe
	 */
	public function findAllByGroupeNull()
	{
		$participants = $this->getEntityManager()
		->createQuery('SELECT p FROM LarpManager\Entities\Participant p JOIN p.user u JOIN u.etatCivil ec WHERE p.groupe IS NULL ORDER BY ec.nom ASC, ec.prenom ASC')
		->getResult();
		
		return $participants;
	}
}