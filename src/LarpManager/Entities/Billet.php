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
 
/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-annotation) on 2016-08-19 17:34:50.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use LarpManager\Entities\BaseBillet;
use LarpManager\Entities\User;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * LarpManager\Entities\Billet
 *
 * @Entity(repositoryClass="LarpManager\Repository\BilletRepository")
 */
class Billet extends BaseBillet
{
	/**
	 * Constructeur
	 */
	public function __construct()
	{
		$this->setCreationDate(new \Datetime('NOW'));
		$this->setUpdateDate(new \Datetime('NOW'));
		$this->setFedegn(false);
	}
	
	/**
	 * To string
	 */
	public function __toString()
	{ 
		return $this->getGn()->getLabel(). ' - ' . $this->getLabel();
	}
	
	/**
	 * Fourni la liste de tous les utilisateurs ayant ce billet
	 */
	public function getUsers()
	{
		$result = new ArrayCollection();
		
		foreach ( $this->getParticipants() as $participant)
		{
			$result[] = $participant->getUser();
		}
		
		return $result;
	}
	
	/**
	 * Défini le créateur du billet
	 * 
	 * @param User $user
	 */
	public function setCreateur(User $user)
	{
		$this->setUser($user);
		return $this;
	}
	
	/**
	 * Récupére le créateur du billet
	 */
	public function getCreateur()
	{
		return $this->getUser();
	}
	
	/**
	 * Fourni le label complet d'un billet
	 */
	public function fullLabel()
	{
		return $this->getGn()->getLabel(). ' - ' . $this->getLabel();
	}
	
	/**
	 * Indique si le billet est pour un PNJ ou non
	 * 
	 * @return bool
	 */
	public function isPnj() : bool
	{
	    return stripos($this->getLabel(), 'PNJ') > 0; 
	}
}