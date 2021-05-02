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
 * Version 2.1.6-dev (doctrine2-annotation) on 2015-06-30 20:35:19.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use LarpManager\Entities\BaseGn;

/**
 * LarpManager\Entities\Gn
 *
 * @Entity(repositoryClass="LarpManager\Repository\GnRepository")
 */
class Gn extends BaseGn
{
	public function __toString()
	{
		return $this->getLabel();	
	}
	
	/**
	 * Fourni la liste de tous les personnages prévu sur un jeu
	 */
	public function getPersonnages()
	{
		$personnages = new ArrayCollection();
		$participants = $this->getParticipants();
		
		foreach ($participants as $participant)
		{
			$personnage = $participant->getPersonnage();
			if ( $personnage)
			{
				$personnages[] = $personnage;
			}
		}
		
		return $personnages;
	}
	
	/**
	 * Fourni la liste de tous les personnages ayant une certaine renommé prévu sur un jeu
	 */
	public function getPersonnagesRenom($renom = 0)
	{
		$personnages = new ArrayCollection();
		$participants = $this->getParticipants();
		
		foreach ($participants as $participant)
		{
			$personnage = $participant->getPersonnage();
			if ( $personnage and $personnage->getRenomme() > $renom)
			{
				$personnages[] = $personnage;
			}
		}
		
		return $personnages;
	}
	
	/**
	 * Fourni la liste des groupes de PJ prévu sur un jeu
	 */
	public function getGroupeGnsPj()
	{
		$groupeGns = new ArrayCollection();
	
		foreach ($this->getGroupeGns() as $groupeGn)
		{
			if ( $groupeGn->getGroupe()->getPj() ) $groupeGns[] = $groupeGn;
		}
	
		return $groupeGns;
	}
	
	/**
	 * Fourni la liste des groupes prévu sur un jeu
	 */
	public function getGroupes()
	{
		$groupes = new ArrayCollection();
		
		foreach ($this->getGroupeGns() as $groupeGn)
		{
			$groupes[] = $groupeGn->getGroupe();
		}
		
		return $groupes;
	}
	
	/**
	 * Fourni la liste des groupes réservés sur le jeu
	 */
	public function getGroupesReserves()
	{
		$groupes = new ArrayCollection();
	
		foreach ($this->getGroupeGns() as $groupeGn)
		{
			if ( ! $groupeGn->getFree() )
				$groupes[] = $groupeGn->getGroupe();
		}
	
		return $groupes;
	}
	
	/**
	 * Fourni la liste des tous les participants pour la FédéGN
	 */
	public function getParticipantsFedeGn()
	{
		$participants = new ArrayCollection();
		
		foreach ($this->getBillets() as $billet)
		{
			if ( $billet->getFedegn() == true )
			{
				$participants = new ArrayCollection(array_merge($participants->toArray(), $billet->getParticipants()->toArray()));
			}
		}
		
		return $participants;
	}
	
	/**
	 * Fourni la liste de tous les participants à un GN mais n'ayant pas encore acheté de billets
	 */
	public function getParticipantsWithoutBillet()
	{
		$participants = new ArrayCollection();
		
		foreach( $this->getParticipants() as $participant)
		{
			if ( ! $participant->getBillet() )
			{
				$participants[] = $participant;
			}
		}
		
		return $participants;
	}
	
	/**
	 * Fourni la liste de tous les participants à un GN ayant un billet mais n'étant pas encore dans un groupe
	 */
	public function getParticipantsWithoutGroup()
	{
		$participants = new ArrayCollection();
		
		foreach( $this->getParticipants() as $participant)
		{
			if ( $participant->getBillet() && ! $participant->getGroupeGn() )
			{
				$participants[] = $participant;
			}
		}
		
		return $participants;
	}
	
	/**
	 * Fourni la liste de tous les participants à un GN ayant un billet mais n'ayant pas de perso
	 */
	public function getParticipantsWithoutPerso()
	{
		$participants = new ArrayCollection();
	
		foreach( $this->getParticipants() as $participant)
		{
			if ( $participant->getBillet() )
			{
				if ( ! $participant->getPersonnage() )
				{
					$participants[] = $participant;
				}
			}
		}
	
		return $participants;
	}
	
	/**
	 * Fourni la liste de tous les participants inscrit en tant que PNJ
	 */
	public function getParticipantsPnj()
	{
		$participants = new ArrayCollection();
		
		foreach( $this->getParticipants() as $participant)
		{
			if ( $participant->getBillet() )
			{
				if ( $participant->getBillet()->getLabel() ==  'Inscription PNJ')
				{
					$participants[] = $participant;
				}
				if ( $participant->getBillet()->getLabel() ==  'Gratuit PNJ')
				{
					$participants[] = $participant;
				}
			}
		}
		
		return $participants;
	}
	
	/**
	 * Indique si le jeu est passé ou pas
	 * 
	 * @return boolean
	 */
	public function isPast()
	{
		$now = new \Datetime('NOW');
		if ( $this->getDateFin() <  $now ) return true;
		return false;
	}
	
	/**
	 * Fourni le nombre de billet vendu pour ce jeu
	 */
	public function getBilletCount()
	{
		$count = 0;
		foreach ( $this->getParticipants() as $participant)
		{
			if ( $participant->getBillet() ) $count++;
		}
		return $count;
	}
	
	/**
	 * Fourni la liste des utilisateurs de ce GN
	 */
	public function getUsers()
	{
		$result = new ArrayCollection();
		
		foreach ( $this->getBillets() as $billet)
		{
			foreach ( $billet->getParticipants() as $participant)
			{
				$result[] = $participant->getUser();	
			}
		}
		
		return $result;
	}	
	public function getBesoinValidationCi() 
	{
          return $this->getConditionsInscription() != '' && $this->getConditionsInscription() != null;	
	}
}
