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
	
	public function findAllByTavern($tavernId)
	{
		$participants = $this->getEntityManager()
			->createQuery('SELECT p FROM LarpManager\Entities\Participant p JOIN p.user u JOIN u.etatCivil ec WHERE p.tavern_id = '.$tavernId.' ORDER BY ec.nom ASC, ec.prenom ASC')
			->getResult();
		
		return $participants;
	}
}