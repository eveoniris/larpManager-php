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
 * LarpManager\TokenControllerProvider
 * 
 * @author kevin
 *
 */
class TokenControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des token
		 */
		$controllers->match('/','LarpManager\Controllers\TokenController::listAction')
			->bind("token.list")
			->method('GET');

		/**
		 * Liste des token
		 */
		$controllers->match('/print','LarpManager\Controllers\TokenController::printAction')
			->bind("token.print")
			->method('GET');
			
		/**
		 * Liste des token
		 */
		$controllers->match('/download','LarpManager\Controllers\TokenController::downloadAction')
			->bind("token.download")
			->method('GET');
		
		/**
		 * Ajout d'un token
		 */
		$controllers->match('/add','LarpManager\Controllers\TokenController::addAction')
			->bind("token.add")
			->method('GET|POST');
		
		/**
		 * Mise à jour d'un token
		 */
		$controllers->match('/{token}/update','LarpManager\Controllers\TokenController::updateAction')
			->assert('token', '\d+')
			->convert('token', 'converter.token:convert')
			->bind("token.update")
			->method('GET|POST');
		
		/**
		 * Détail d'un token
		 */
		$controllers->match('/{token}','LarpManager\Controllers\TokenController::detailAction')
			->assert('token', '\d+')
			->convert('token', 'converter.token:convert')
			->bind("token.detail")
			->method('GET');
	
		/**
		 * Suppression d'un token
		 */
		$controllers->match('/{token}/delete','LarpManager\Controllers\TokenController::deleteAction')
			->assert('token', '\d+')
			->convert('token', 'converter.token:convert')
			->bind("token.delete")
			->method('GET|POST');
			
		return $controllers;
	}
}