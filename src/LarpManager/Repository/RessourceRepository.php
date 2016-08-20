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