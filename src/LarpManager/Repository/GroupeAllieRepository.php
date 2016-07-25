<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\GnRepository
 *
 * @author kevin
 */
class GroupeAllieRepository extends EntityRepository
{

	/**
	 * Fourni toutes les alliances en cours
	 */
	public function findByAlliances()
	{
		$alliances = $this->getEntityManager()
			->createQuery('SELECT ga FROM LarpManager\Entities\GroupeAllie ga JOIN ga.groupeRelatedByGroupeId g  WHERE ga.groupe_accepted = true AND ga.groupe_allie_accepted = true ORDER BY g.nom ASC')
			->getResult();

		return $alliances;
	}

	/**
	 * Fourni toutes les demandes d'alliances
	 *
	 * @param array $criteria
	 */
	public function findByDemandeAlliances()
	{
		$demandeAlliances = $this->getEntityManager()
			->createQuery('SELECT ga FROM LarpManager\Entities\GroupeAllie ga JOIN ga.groupeRelatedByGroupeId g  WHERE ( ga.groupe_accepted = true OR ga.groupe_allie_accepted = true)  AND (ga.groupe_accepted = false OR ga.groupe_allie_accepted = false) ORDER BY g.nom ASC')
			->getResult();

		return $demandeAlliances;
	}
}