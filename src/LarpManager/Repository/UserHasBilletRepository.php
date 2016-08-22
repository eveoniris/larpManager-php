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
use LarpManager\Entities\Gn;

/**
 * LarpManager\Repository\UserHasBilletRepository
 *  
 * @author kevin
 */
class UserHasBilletRepository extends EntityRepository
{
	

	/**
	 * Fourni la liste de tous les utilisateurs ayant un billet pour ce GN
	 * 
	 * @param Gn $gn
	 */
	public function findAllByGn(Gn $gn)
	{
		$userHasBillets = $this->getEntityManager()
					->createQuery('SELECT uhb FROM LarpManager\Entities\UserHasBillet uhb LEFT JOIN uhb.billet b LEFT JOIN b.gn gn LEFT JOIN uhb.user u LEFT JOIN u.etatCivil ec WHERE gn.id = :gnId ORDER BY ec.nom ASC, ec.prenom ASC')
					->setParameter('gnId',$gn->getId())
					->getResult();
		
		return $userHasBillets;
	}
}