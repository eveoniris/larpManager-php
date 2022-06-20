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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\SortRepository
 * 
 * @author kevin
 */
class SortRepository extends EntityRepository
{
	/**
	 * Find all Apprenti sorts ordered by label
	 * @return ArrayCollection $sorts
	 */
	public function findByNiveau($niveau)
	{
		$sorts = $this->getEntityManager()
				->createQuery('SELECT s FROM LarpManager\Entities\Sort s Where s.niveau = ?1 AND (s.secret = 0 OR s.secret IS NULL) ORDER BY s.label ASC')
				->setParameter(1, $niveau)
                ->getResult();
		
		return $sorts;
	}
}