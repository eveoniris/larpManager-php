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
 * Version 2.1.6-dev (doctrine2-annotation) on 2015-08-19 12:25:00.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use LarpManager\Entities\BaseAppelation;

/**
 * LarpManager\Entities\Appelation
 *
 * @Entity()
 */
class Appelation extends BaseAppelation
{
	public function __toString()
	{
		return $this->getLabelTree();
	}
	

	/**
	 * Calcule le nombre d'étape necessaire pour revenir au parent le plus ancien
	 */
	public function stepCount($count = 0)
	{
		if ( $this->getAppelation() )
		{
			return $this->getAppelation()->stepCount($count+1);
		}
		return $count;
	}
	
	public function getLabelTree()
	{
		$string = $this->getLabel();

		if ( $this->getAppelations()->count() != 0 )
		{
			$string .= ' > ';
			$string .= implode(', ', $this->getAppelations()->toArray());
		}
		
		return $string;
	}
}