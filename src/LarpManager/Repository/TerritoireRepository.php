<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\AgeRepository
 *  
 * @author kevin
 */
class TerritoireRepository extends EntityRepository
{
	/**
	 * Fourni la liste des territoires n'étant pas dépendant d'un autre territoire
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function findRoot()
	{
		$query = $this->app['orm.em']->createQuery('SELECT t FROM LarpManager\Entities\Territoire t WHERE t.territoire IS NULL');
		$territoires = $query->getResult();
	
		return $territoires;
	}
}