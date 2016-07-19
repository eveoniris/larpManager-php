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