<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\GnRepository
 *
 * @author kevin
 */
class GroupeEnemyRepository extends EntityRepository
{

	/**
	 * Fourni touttes les guerres en cours
	 */
	public function findByWar()
	{
		$alliances = $this->getEntityManager()
			->createQuery('SELECT ge FROM LarpManager\Entities\GroupeEnemy ge JOIN ge.groupeRelatedByGroupeId g WHERE ge.groupe_peace = false AND ge.groupe_enemy_peace = false ORDER BY g.nom ASC')
			->getResult();

		return $alliances;
	}

	/**
	 * Fourni toutes les demandes de paix
	 *
	 * @param array $criteria
	 */
	public function findByRequestPeace()
	{
		$demandeAlliances = $this->getEntityManager()
			->createQuery('SELECT ge FROM LarpManager\Entities\GroupeEnemy ge JOIN ge.groupeRelatedByGroupeId g WHERE (ge.groupe_peace = false OR ge.groupe_enemy_peace = false) AND (ge.groupe_peace = true OR ge.groupe_enemy_peace = true) ORDER BY g.nom ASC')
			->getResult();

		return $demandeAlliances;
	}
}