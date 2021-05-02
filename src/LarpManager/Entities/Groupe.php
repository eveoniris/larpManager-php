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
use LarpManager\Entities\BaseGroupe;

/**
 * Je définie les relations ManyToMany içi au lieu de le faire dans Mysql Workbench
 * car l'exporteur ne sait pas gérer correctement les relations ManyToMany ayant des
 * paramètres autre que les identifiant des tables concernés (c'est dommage ...)
 *
 * LarpManager\Entities\Groupe
 *
 * @Entity(repositoryClass="LarpManager\Repository\GroupeRepository")
 */
class Groupe extends BaseGroupe
{
	/**
	 * Contructeur.
	 *
	 * Défini le nombre de place disponible à 0
	 */
	public function __construct()
	{
		$this->setClasseOpen(0);
		$this->setLock(false);
		parent::__construct();
	}

	/**
	 * méthode magique transtypage en string
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getNom();
	}

	/**
	 * Fourni la session d'un groupe relatif à un GN
	 * @param Gn $gn
	 */
	public function getSession(Gn $gn)
	{
		foreach ( $this->getGroupeGns() as $groupeGn)
		{
			if ( $groupeGn->getGn() == $gn )
			{
				return $groupeGn;
			}
		}
		return null;
	}

	/**
	 * Fourni la prochaine session de jeu
	 */
	public function getNextSession()
	{
		return $this->getGroupeGns()->last();
	}

	/**
	 * Fourni les informations pour une session de jeu
	 *
	 * @param Gn $gn
	 */
	public function getGroupeGn(Gn $gn)
	{
		foreach ( $this->getGroupeGns() as $groupeGn)
		{
			if ( $groupeGn->getGn() == $gn )
			{
				return $groupeGn;
			}
		}
		return null;
	}

	/**
	 * Fourni les informations pour une session de jeu
	 *
	 * @param Gn $gn
	 */
	public function getGroupeGnById($gnId)
	{
		foreach ( $this->getGroupeGns() as $groupeGn)
		{
			if ( $groupeGn->getGn()->getId() == $gnId )
			{
				return $groupeGn;
			}
		}
		return null;
	}

	/**
	 * Toutes les importations du groupe
	 */
	public function getImportations()
	{
		$ressources = new ArrayCollection();
		foreach ( $this->getTerritoires() as $territoire)
		{
			$ressources = new ArrayCollection(array_merge($ressources->toArray(), $territoire->getImportations()->toArray()));
		}
		return $ressources;
	}

	/**
	 * Toutes les exporations du groupe
	 */
	public function getExportations()
	{
		$ressources = new ArrayCollection();
		foreach ( $this->getTerritoires() as $territoire)
		{
			$ressources = new ArrayCollection(array_merge($ressources->toArray(), $territoire->getExportations()->toArray()));
		}
		return $ressources;
	}

	/**
	 * Fourni tous les ingrédients obtenu par le groupe grace à ses territoires
	 */
	public function getIngredients()
	{
		$ingredients = new ArrayCollection();
		foreach ( $this->getTerritoires() as $territoire)
		{
			$ingredients = new ArrayCollection(array_merge($ingredients->toArray(), $territoire->getIngredients()->toArray()));
		}
		return $ingredients;
	}

	/**
	 * Fourni une version imprimable du matériel
	 */
	public function getMaterielRaw()
	{
		return html_entity_decode(strip_tags($this->getMateriel()));
	}

	/**
	 * Fourni la liste des ressources necessaires à un groupe
	 *
	 * @param unknown $rarete
	 */
	public function getRessourcesNeeded($rarete = null)
	{
		$ressources = new ArrayCollection();

		foreach ($this->getTerritoires() as $territoire )
		{
			$ressources = new ArrayCollection(
					array_unique(
							array_merge($ressources->toArray(), $territoire->getImportations($rarete)->toArray())
					)
			);
		}

		return $ressources;
	}

	/**
	 * Vérifie si un groupe dispose de ressources
	 */
	public function hasRessource()
	{
		foreach ($this->getTerritoires() as $territoire )
		{
			if ( $territoire->getExportations()->count() > 0 ) return true;
		}
		return false;
	}

	/**
	 * Vérifie si un groupe dispose de richesses
	 */
	public function hasRichesse()
	{
		foreach ($this->getTerritoires() as $territoire )
		{
			if ( $territoire->getTresor() > 0 ) return true;
		}
		return false;
	}

	/**
	 * Fourni la richesse totale du groupe (territoire + richesse perso)
	 */
	public function getRichesseTotale()
	{
		$richesse = $this->getRichesse();
		foreach ($this->getTerritoires() as $territoire )
		{
			$richesse += $territoire->getRichesse();
		}
		return $richesse;
	}

	/**
	 * Vérifie si un groupe dispose d'ingrédients
	 */
	public function hasIngredient()
	{
		foreach ($this->getTerritoires() as $territoire )
		{
			if ( $territoire->getIngredients()->count() > 0 ) return true;
		}
		return false;
	}

	/**
	 * Fourni les backgrounds du groupe en fonction de la visibilitée
	 * @param unknown $visibility
	 */
	public function getBacks($visibility = null)
	{
		$backgrounds = new ArrayCollection();
		foreach ( $this->getBackgrounds() as $background)
		{
			if ( $visibility != null )
			{
				if ( $background->getVisibility() == $visibility )
				{
					$backgrounds[] = $background;
				}
			}
			else
			{
				$backgrounds[] = $background;
			}

		}
		return $backgrounds;
	}

	/**
	 * Determine si un groupe est allié avec ce groupe
	 * @param Groupe $groupe
	 */
	public function isAllyTo(Groupe $groupe)
	{
		foreach ( $this->getAlliances() as $alliance)
		{
			if ($alliance->getGroupe() == $groupe
				|| $alliance->getRequestedGroupe() == $groupe )
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Determine si un groupe est en attente d'alliance avec ce groupe
	 * @param Groupe $groupe
	 * @return boolean
	 */
	public function isWaitingAlliance(Groupe $groupe)
	{
		foreach ( $this->getWaitingAlliances() as $alliance)
		{
			if ($alliance->getGroupe() == $groupe
					|| $alliance->getRequestedGroupe() == $groupe )
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Determine si un groupe est ennemi avec ce groupe
	 * @param Groupe $groupe
	 */
	public function isEnemyTo(Groupe $groupe)
	{
		foreach ( $this->getEnnemies() as $war)
		{
			if ($war->getGroupe() == $groupe
				|| $war->getRequestedGroupe() == $groupe )
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Determine si un groupe est ennemi avec ce groupe
	 * @param Groupe $groupe
	 */
	public function isWaitingPeaceTo(Groupe $groupe)
	{
		foreach ( $this->getWaitingPeace() as $war)
		{
			if ($war->getGroupe() == $groupe
					|| $war->getRequestedGroupe() == $groupe )
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Fourni la liste des toutes les alliances de ce groupe
	 */
	public function getAlliances()
	{
		$alliances = new ArrayCollection();

		foreach ( $this->groupeAllieRelatedByGroupeIds as $alliance)
		{
			if ( $alliance->getGroupeAccepted() && $alliance->getGroupeAllieAccepted() )
			{
				$alliances[] = $alliance;
			}
		}

		foreach ( $this->groupeAllieRelatedByGroupeAllieIds as $alliance)
		{
			if ( $alliance->getGroupeAccepted() && $alliance->getGroupeAllieAccepted() )
			{
				$alliances[] = $alliance;
			}
		}

		return $alliances;
	}

	/**
	 * Fourni la liste de toutes les alliances en cours de négotiation
	 */
	public function getWaitingAlliances()
	{
		$alliances = new ArrayCollection();

		foreach ( $this->groupeAllieRelatedByGroupeIds as $alliance)
		{
			if ( ! $alliance->getGroupeAccepted() || ! $alliance->getGroupeAllieAccepted() )
			{
				$alliances[] = $alliance;
			}
		}

		foreach ( $this->groupeAllieRelatedByGroupeAllieIds as $alliance)
		{
			if ( ! $alliance->getGroupeAccepted() || ! $alliance->getGroupeAllieAccepted() )
			{
				$alliances[] = $alliance;
			}
		}

		return $alliances;
	}

	/**
	 * Fourni la liste de toutes les demandes d'alliances
	 */
	public function getRequestedAlliances()
	{
		$alliances = new ArrayCollection();

		foreach ( $this->groupeAllieRelatedByGroupeAllieIds as $alliance)
		{
			if ( ! $alliance->getGroupeAllieAccepted() )
			{
				$alliances[] = $alliance;
			}

		}
		return $alliances;
	}

	/**
	 * Fourni la liste de toutes les alliances demandés
	 */
	public function getSelfRequestedAlliances()
	{
		$alliances = new ArrayCollection();

		foreach ( $this->groupeAllieRelatedByGroupeIds as $alliance)
		{
			if ( ! $alliance->getGroupeAllieAccepted() )
			{
				$alliances[] = $alliance;
			}

		}
		return $alliances;
	}

	/**
	 * Fourni tous les ennemis du groupe
	 */
	public function getEnnemies()
	{
		$enemies = new ArrayCollection();

		foreach ( $this->groupeEnemyRelatedByGroupeIds as $enemy)
		{
			if ( $enemy->getGroupePeace() == false || $enemy->getGroupeEnemyPeace() == false )
			{
				$enemies[] = $enemy;
			}
		}

		foreach ( $this->groupeEnemyRelatedByGroupeEnemyIds as $enemy)
		{
			if ( $enemy->getGroupePeace() == false || $enemy->getGroupeEnemyPeace() == false )
			{
				$enemies[] = $enemy;
			}
		}

		return $enemies;
	}

	/**
	 * Fourni la liste des anciens ennemis
	 */
	public function getOldEnemies()
	{
		$enemies = new ArrayCollection();

		foreach ( $this->groupeEnemyRelatedByGroupeIds as $enemy)
		{
			if ( $enemy->getGroupePeace() == true && $enemy->getGroupeEnemyPeace() == true )
			{
				$enemies[] = $enemy;
			}
		}

		foreach ( $this->groupeEnemyRelatedByGroupeEnemyIds as $enemy)
		{
			if ( $enemy->getGroupePeace() == true && $enemy->getGroupeEnemyPeace() == true )
			{
				$enemies[] = $enemy;
			}
		}

		return $enemies;
	}

	/**
	 * Fournie toutes les negociation de paix en cours
	 */
	public function getWaitingPeace()
	{
		$enemies = new ArrayCollection();

		foreach ( $this->groupeEnemyRelatedByGroupeIds as $enemy)
		{
			if ( ( $enemy->getGroupePeace() == true || $enemy->getGroupeEnemyPeace() == true)
				&& (! ( $enemy->getGroupePeace() == true && $enemy->getGroupeEnemyPeace() == true)) )
			{
				$enemies[] = $enemy;
			}
		}

		foreach ( $this->groupeEnemyRelatedByGroupeEnemyIds as $enemy)
		{
			if ( ( $enemy->getGroupePeace() == true || $enemy->getGroupeEnemyPeace() == true)
				&& (! ( $enemy->getGroupePeace() == true && $enemy->getGroupeEnemyPeace() == true)) )
			{
				$enemies[] = $enemy;
			}
		}

		return $enemies;
	}

	/**
	 * Trouve le personnage de l'utilisateur dans ce groupe
	 *
	 * @param User $user
	 */
	public function getPersonnage(User $user)
	{
		$participant = $user->getParticipantByGroupe($this);
		if ( $participant )
		{
			return $participant->getPersonnage();
		}
		return null;
	}

	/**
	 * Fourni le nombre de place disponible pour un groupe
	 * en fonction des territoires qu'il controle
	 */
	public function getPlaceTotal()
	{
		return 12 + ( 2 *  count($this->getTerritoires()));
	}

	/**
	 * Vérifie si le groupe dispose de suffisement de place disponible
	 *
	 * @return boolean
	 */
	public function hasEnoughPlace()
	{
		return $this->getClasseOpen() > count($this->getPersonnages());
	}

	/**
	 * Vérifie si le groupe dispose de suffisement de classe disponible
	 */
	public function hasEnoughClasse($gn)
	{
		return  ( count($this->getAvailableClasses($gn)) > 0 );
	}

	/**
	 * Fourni la liste des classes disponibles (non actuellement utilisé par un personnage)
	 * Ce type de liste est utile pour le formulaire de création d'un personnage
	 *
	 * @return Collection LarpManager\Entities\Classe
	 */
	public function getAvailableClasses(Gn $gn)
	{
		$groupeGn = $this->getGroupeGn($gn);
		$groupeClasses = $this->getGroupeClasses();
		$base = clone $groupeClasses;

		foreach ( $groupeGn->getPersonnages() as $personnage)
		{
			$id = $personnage->getClasse()->getId();

			foreach (  $base as $key => $groupeClasse)
			{
				if ( $groupeClasse->getClasse()->getId() == $id )
				{
					unset($base[$key]);
					break;
				}
			}
		}

		$availableClasses = array();

		foreach ( $base as $groupeClasse)
		{
			$availableClasses[] = $groupeClasse->getClasse();
		}

		return $availableClasses;
	}

	/**
	 * Get User entity related by `scenariste_id` (many to one).
	 *
	 * @return \LarpManager\Entities\User
	 */
	public function getScenariste()
	{
		return $this->getUserRelatedByScenaristeId();
	}

	/**
	 * Set User entity related by `scenariste_id` (many to one).
	 *
	 * @param \LarpManager\Entities\User $user
	 * @return \LarpManager\Entities\Groupe
	 */
	public function setScenariste($user)
	{
		return $this->setUserRelatedByScenaristeId($user);
	}

	/**
	 * Fourni la liste des classes
	 *
	 * @return Array LarpManager\Entities\Classe
	 */
	public function getClasses()
	{
		$classes = array();
		$groupeClasses =  $this->getGroupeClasses();
		foreach ( $groupeClasses as $groupeClasse)
		{
			$classes[] = $groupeClasse->getClasse();
		}
		return $classes;
	}

	/**
	 * Ajoute une classe dans le groupe
	 *
	 * @param GroupeClasse $groupeClasse
	 */
	public function addGroupeClass(GroupeClasse $groupeClasse)
	{
		return $this->addGroupeClasse($groupeClasse);
	}

	/**
	 * Retire une classe du groupe
	 * @param GroupeClasse $groupeClasse
	 */
	public function removeGroupeClass(GroupeClasse $groupeClasse)
	{
		return $this->removeGroupeClasse($groupeClasse);
	}

}
