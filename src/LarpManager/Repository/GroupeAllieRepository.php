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