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
 * LarpManager\StockTagControllerProvider
 * 
 * @author kevin
 *
 */
class StockTagControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les tags 
	 * Routes :
	 * 	- stock_tag_index
	 * 	- stock_tag_add
	 *  - stock_tag_update
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\StockTagController::indexAction')
			->bind("stock_tag_index")
			->method('GET');
				
		$controllers->match('/add','LarpManager\Controllers\StockTagController::addAction')
			->bind("stock_tag_add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\StockTagController::updateAction')
			->assert('index', '\d+')
			->bind("stock_tag_update")
			->method('GET|POST');
					
		return $controllers;
	}
}
