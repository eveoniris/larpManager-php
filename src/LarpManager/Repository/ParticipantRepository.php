<?php

/**
 * LarpManager - A Live Action Role Playing Manager
 * Copyright (C) 2016 Kevin Polez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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