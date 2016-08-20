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