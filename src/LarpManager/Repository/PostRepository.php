<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\PostRepository
 * 
 * @author kevin
 */
class PostRepository extends EntityRepository
{
	/**
	 * trouve tous les derniers posts classé par date de publication (en prennant en compte les réponses)
	 * @return ArrayCollection $religionLevels
	 */
	public function findOrderByCreationDate()
	{
		$posts = $this->getEntityManager()
				->createQuery('SELECT p FROM LarpManager\Entities\Post p ORDER BY p.creationDate DESC')
				->getResult();
		
		return $posts;
	}
}