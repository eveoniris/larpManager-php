<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\AgeRepository
 *  
 * @author kevin
 */
class AppelationRepository extends EntityRepository
{
	/**
	 * Fourni la liste des appelations n'étant pas dépendante d'une autre appelation
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function findRoot()
	{
		$query = $this->app['orm.em']->createQuery('SELECT a FROM LarpManager\Entities\Appelation a WHERE a.appelation IS NULL');
		$appelations = $query->getResult();
	
		return $appelations;
	}
}

