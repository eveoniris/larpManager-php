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
 * Version 2.1.6-dev (doctrine2-annotation) on 2015-07-02 20:39:27.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use LarpManager\Entities\BaseLangue;

/**
 * LarpManager\Entities\Langue
 *
 * @Entity(repositoryClass="LarpManager\Repository\LangueRepository")
 */
class Langue extends BaseLangue
{
    public const DIFFUSION_COURANTE = 1;
    public const DIFFUSION_COMMUNE = 2;
    public const DIFFUSION_RARE = 0;
    public const DIFFUSION_INCONNUE = null;

    public const SECRET_VISIBLE = 0;
    public const SECRET_HIDDEN = 1;


	/**
	 * @ManyToMany(targetEntity="Territoire", mappedBy="langues")
	 */
	protected $territoireSecondaires;

	public function __construct()
	{
		$this->territoireSecondaires = new ArrayCollection();
		parent::__construct();
	}

	public function __toString()
	{
		return $this->getLabel();
	}

	public function getFullDescription()
	{
		return $this->getLabel() . ' : '.$this->getDescription();
	}

	public function getTerritoireSecondaires()
	{
		return $this->territoireSecondaires;
	}

	public function addTerritoireSecondaire(Territoire $territoire)
	{
		$this->territoireSecondaires[] = $territoire;
		return $this;
	}

	public function removeTerritoireSecondaire(Territoire $territoire)
	{
		$this->territoireSecondaires->removeElement($territoire);
		return $this;

	}

	/**
	 * Fourni la liste des territoires ou la langue est la langue principale.
	 */
	public function getTerritoirePrincipaux()
	{
		return $this->getTerritoires();
	}

	/**
	 * Fourni la catégorie de la langue
	 * @return string
	 */
	public function getCategorie(): string
	{
		switch ( $this->getDiffusion() )
		{
            case self::DIFFUSION_COMMUNE: return 'Commune';
			case self::DIFFUSION_COURANTE: return 'Courante';
			case self::DIFFUSION_RARE: return 'Rare';
			case self::DIFFUSION_INCONNUE:
			default : return 'Inconnue';
		}
	}

	/**
	 * Renvoie le libellé de diffusion, incluant la catégorie
	 * @return string
	 */
	public function getDiffusionLabel() : string
	{
		return ($this->getDiffusion() !== null ? $this->getDiffusion().' - ' : '').$this->getCategorie();
	}

	public function getPrintLabel()
	{
		return preg_replace('/[^a-z0-9]+/', '_', strtolower($this->getLabel()));
	}
}