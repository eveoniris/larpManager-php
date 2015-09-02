<?php

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