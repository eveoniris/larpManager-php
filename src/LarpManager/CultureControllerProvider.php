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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\CultureControllerProvider
 * @author kevin
 *
 */
class CultureControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les classes
	 * Routes :
	 * 	- culture
	 * 	- culture.add
	 *  - culture.update
	 *  - culture.detail
	 *  - culture.delete
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
			
		$controllers->match('/','LarpManager\Controllers\CultureController::indexAction')
			->bind("culture")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\CultureController::addAction')
			->bind("culture.add")
			->method('GET|POST');
				
		$controllers->match('/{culture}/update','LarpManager\Controllers\CultureController::updateAction')
			->assert('culture', '\d+')
			->convert('culture', 'converter.culture:convert')
			->bind("culture.update")
			->method('GET|POST');
		
		$controllers->match('/{culture}','LarpManager\Controllers\CultureController::detailAction')
			->assert('culture', '\d+')
			->convert('culture', 'converter.culture:convert')
			->bind("culture.detail")
			->method('GET');
		
		$controllers->match('/{culture}/delete','LarpManager\Controllers\CultureController::deleteAction')
			->assert('culture', '\d+')
			->convert('culture', 'converter.culture:convert')
			->bind("culture.delete")
			->method('GET|POST');
							
		return $controllers;
	}
}