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
 * Version 2.1.6-dev (doctrine2-annotation) on 2015-08-18 21:25:16.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use LarpManager\Entities\BaseBackground;

/**
 * LarpManager\Entities\Background
 *
 * @Entity(repositoryClass="LarpManager\Repository\BackgroundRepository")
 */
class Background extends BaseBackground
{
	/**
	 * Définir la date de création et la date de mise à jour lors de la création d'un nouveau background
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setUpdateDate(new \Datetime('NOW'));
		$this->setCreationDate(new \Datetime('NOW'));
	}
	
}