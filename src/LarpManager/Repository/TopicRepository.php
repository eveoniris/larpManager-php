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
use LarpManager\Entities\Topic;

/**
 * LarpManager\Repository\TopicRepository
 *  
 * @author kevin
 */
class TopicRepository extends EntityRepository
{
	/**
	 * Trouve tous les topics de premier niveau (qui ne sont pas des sous-forums)
	 */
	public function findAllRoot()
	{
		$query = $this->getEntityManager()
				->createQuery('SELECT t FROM LarpManager\Entities\Topic t WHERE IDENTITY(t.topic) IS NULL ORDER BY t.creation_date ASC');
		
		$topics = $query->getResult();
		return $topics;
	}
	
	/**
	 * Trouve tous les topics classés par date de création.
	 * Si le topicId est fourni, retourne la liste des topics appartenant à ce topic.
	 * Si le topicId n'est pas fourni, retourne la liste des topics de premier niveau.
	 * 
	 * @param integer $topicId
	 * @return ArrayCollection $topics
	 */
	public function findAllOrderedByCreationDate($topicId = NULL)
	{
		$query = $this->getEntityManager()
				->createQuery('SELECT t FROM LarpManager\Entities\Topic t ORDER BY t.creation_date ASC');
		
		if ( NULL == $topicId)
		{
			$query->addWhere('t.topic_id IS NULL');
		}
		else
		{
			$query->addWhere('t.topic_id = :topic_id');
			$query->setParameter('topic_id', $topicId);
		}
		
		$topics = $query->getResult();
		
		return $topics;
	}
	
	/**
	 * Trouve tous les topics correspondant aux gns dans lequel est inscrit le joueur
	 * 
	 * @param integer $joueurId
	 */
	public function findAllRelatedToJoueurReferedGns($joueurId)
	{
		$topics = $this->getEntityManager()
			->createQuery('SELECT t FROM LarpManager\Entities\Topic t WHERE t.id IN ( SELECT IDENTITY(g.topic) FROM LarpManager\Entities\Gn g WHERE g.actif = true AND IDENTITY(t.topic) IS NULL AND g.id IN (SELECT IDENTITY(jg.gn) FROM LarpManager\Entities\JoueurGn jg WHERE IDENTITY(jg.joueur) = :joueurId))')
			->setParameter('joueurId', $joueurId)
			->getResult();
		
		return $topics;
	}
	
	/**
	 * Trouve tous les topics correspondant aux groupes dans lequel est inscrit le joueur
	 * 
	 * @param integer $joueurId
	 */
	public function findAllRelatedToJoueurReferedGroupes($joueurId)
	{
		$topics = $this->getEntityManager()
			->createQuery('SELECT t FROM LarpManager\Entities\Topic t JOIN t.groupe g JOIN g.joueurs j WHERE j.id = :joueurId AND t.topic_id IS NULL')
			->setParameter('joueurId', $joueurId)
			->getResult();
		
		return $topics;
	}
}