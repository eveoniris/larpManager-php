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
 * LarpManager\DocumentControllerProvider
 * 
 * @author kevin
 *
 */
class DocumentControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les documents
	 * Routes :
	 * 	- document
	 * 	- document.add
	 *  - document.update
	 *  - document.detail
	 *  - document.delete
	 *  
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des documents
		 */
		$controllers->match('/','LarpManager\Controllers\DocumentController::indexAction')
			->bind("document")
			->method('GET|POST');
			
		/**
		 * Imprimer la liste des documents
		 */
		$controllers->match('/print','LarpManager\Controllers\DocumentController::printAction')
			->bind("document.print")
			->method('GET');
			
		/**
		 * Télécharger la liste des documents
		 */
		$controllers->match('/download','LarpManager\Controllers\DocumentController::downloadAction')
			->bind("document.download")
			->method('GET');
		
		/**
		 * Ajoute un document
		 */
		$controllers->match('/add','LarpManager\Controllers\DocumentController::addAction')
			->bind("document.add")
			->method('GET|POST');

		/**
		 * Mise à jour d'un document
		 */
		$controllers->match('/{document}/update','LarpManager\Controllers\DocumentController::updateAction')
			->assert('document', '\d+')
			->convert('document', 'converter.document:convert')
			->bind("document.update")
			->method('GET|POST');

		/**
		 * Detail d'un document
		 */
		$controllers->match('/{document}','LarpManager\Controllers\DocumentController::detailAction')
			->assert('document', '\d+')
			->convert('document', 'converter.document:convert')
			->bind("document.detail")
			->method('GET');

		/**
		 * Supression d'un document
		 */
		$controllers->match('/{document}/delete','LarpManager\Controllers\DocumentController::deleteAction')
			->assert('document', '\d+')
			->convert('document', 'converter.document:convert')
			->bind("document.delete")
			->method('GET|POST');

		$controllers->match('/get/{document}', 'LarpManager\Controllers\DocumentController::getAction')
			->bind("document.get")
			->method('GET');
			
		return $controllers;
	}
}