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
 * LarpManager\StockEtatControllerProvider
 * 
 * @author kevin
 *
 */
class StockEtatControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les états du stock
	 * Routes :
	 * 	- stock_etat_index
	 * 	- stock_etat_add
	 *  - stock_etat_update
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\StockEtatController::indexAction')
			->bind("stock_etat_index")
			->method('GET');
				
		$controllers->match('/add','LarpManager\Controllers\StockEtatController::addAction')
			->bind("stock_etat_add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\StockEtatController::updateAction')
			->assert('index', '\d+')
			->bind("stock_etat_update")
			->method('GET|POST');
			
		return $controllers;
	}
}
		
		