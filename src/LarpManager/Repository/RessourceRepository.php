<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\RessourceRepository
 *
 * @author kevin
 */
class RessourceRepository extends EntityRepository
{
	/**
	 * Fourni la liste des ressources communes
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function findCommun()
	{
		$query = $this->getEntityManager()->createQuery('SELECT r FROM LarpManager\Entities\Ressource r JOIN r.rarete ra WHERE ra.label LIKE \'Commun\' ORDER BY r.label ASC');
		$ressources = $query->getResult();

		return $ressources;
	}

	/**
	 * Fourni la liste des ressources rares
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function findRare()
	{
		$query = $this->getEntityManager()->createQuery('SELECT r FROM LarpManager\Entities\Ressource r JOIN r.rarete ra WHERE ra.label LIKE \'Rare\' ORDER BY r.label ASC');
		$ressources = $query->getResult();

		return $ressources;
	}
}