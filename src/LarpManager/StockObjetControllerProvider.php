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
 * LarpManager\StockObjetControllerProvider
 * 
 * @author kevin
 *
 */
class StockObjetControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour un objet du stock
	 * Routes :
	 * 	- stock_objet_index
	 * 	- stock_objet_list
	 *  - stock_objet_list_without_proprio
	 *  - stock_objet_list_without_rangement
	 *  - stock_objet_export
	 *  - stock_objet_detail
	 *  - stock_objet_photo
	 *  - stock_objet_add
	 *  - sotck_objet_update
	 *  - stock_objet_clone
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		/**
		 * Liste des objets
		 */
		$controllers->match('/','LarpManager\Controllers\StockObjetController::indexAction')
			->bind("stock_objet_index")
			->method('GET|POST');
				
		$controllers->match('/listWithoutProprio/{page}','LarpManager\Controllers\StockObjetController::listWithoutProprioAction')
			->assert('page', '\d+')
			->bind("stock_objet_list_without_proprio")
			->method('GET|POST');
		
		$controllers->match('/listWithoutResponsable/{page}','LarpManager\Controllers\StockObjetController::listWithoutResponsableAction')
			->assert('page', '\d+')
			->bind("stock_objet_list_without_responsable")
			->method('GET|POST');
			
		$controllers->match('/listWithoutRangement/{page}','LarpManager\Controllers\StockObjetController::listWithoutRangementAction')
			->assert('page', '\d+')
			->bind("stock_objet_list_without_rangement")
			->method('GET|POST');
		
		$controllers->match('/export','LarpManager\Controllers\StockObjetController::exportAction')
			->bind("stock_objet_export")
			->method('GET');
		
		/**
		 * Détail d'un objet
		 */
		$controllers->match('/{objet}','LarpManager\Controllers\StockObjetController::detailAction')
			->assert('objet', '\d+')
			->convert('objet', 'converter.objet:convert')
			->bind("stock_objet_detail")
			->method('GET');
		
		/**
		 * Récupérer la photo d'un objet
		 */
		$controllers->match('/{objet}/photo','LarpManager\Controllers\StockObjetController::photoAction')
			->assert('objet', '\d+')
			->convert('objet', 'converter.objet:convert')
			->bind("stock_objet_photo")
			->method('GET');
		
		/**
		 * Ajout d'un objet
		 */
		$controllers->match('/add','LarpManager\Controllers\StockObjetController::addAction')
			->bind("stock_objet_add")
			->method('GET|POST');
		
		/**
		 * Mise à jour d'un objet
		 */
		$controllers->match('/{objet}/update','LarpManager\Controllers\StockObjetController::updateAction')
			->assert('objet', '\d+')
			->convert('objet', 'converter.objet:convert')
			->bind("stock_objet_update")
			->method('GET|POST');
		
		/**
		 * Clonage d'un objet
		 */
		$controllers->match('/{objet}/clone','LarpManager\Controllers\StockObjetController::cloneAction')
			->assert('objet', '\d+')
			->convert('objet', 'converter.objet:convert')
			->bind("stock_objet_clone")
			->method('GET|POST');
		
		/**
		 * Suppression d'un objet
		 */
		$controllers->match('/{objet}/delete','LarpManager\Controllers\StockObjetController::deleteAction')
			->assert('objet', '\d+')
			->convert('objet', 'converter.objet:convert')
			->bind("stock_objet_delete")
			->method('GET|POST');
		
		/**
		 * Gestion des tags d'un objet
		 */
		$controllers->match('/{objet}/tag','LarpManager\Controllers\StockObjetController::tagAction')
			->assert('objet', '\d+')
			->convert('objet', 'converter.objet:convert')
			->bind("stock_objet_tag")
			->method('GET|POST');
		
		return $controllers;
	}
}

