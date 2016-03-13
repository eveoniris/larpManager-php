<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\TerritoireRepository
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
		$query = $this->getEntityManager()->createQuery('SELECT t FROM LarpManager\Entities\Territoire t WHERE t.territoire IS NULL ORDER BY t.nom ASC');
		$territoires = $query->getResult();
	
		return $territoires;
	}
	
	/**
	 * Fourni la liste des territoires étant dépendant d'un autre territoire
	 */
	public function findFiefs()
	{
		$query = $this->getEntityManager()->createQuery('SELECT t FROM LarpManager\Entities\Territoire t WHERE t.territoire IS NOT NULL ORDER BY t.nom ASC');
		$territoires = $query->getResult();
		
		return $territoires;
	}
}