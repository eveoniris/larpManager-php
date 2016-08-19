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

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\GroupeSecondaireTypeControllerProvider
 * 
 * @author kevin
 *
 */
class GroupeSecondaireTypeControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\GroupeSecondaireTypeController::adminListAction')
			->bind("groupeSecondaire.admin.type.list")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\GroupeSecondaireTypeController::adminAddAction')
			->bind("groupeSecondaire.admin.type.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\GroupeSecondaireTypeController::adminUpdateAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.admin.type.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\GroupeSecondaireTypeController::adminDetailAction')
			->assert('index', '\d+')
			->bind("groupeSecondaire.admin.type.detail")
			->method('GET');
			
		return $controllers;
	}
}