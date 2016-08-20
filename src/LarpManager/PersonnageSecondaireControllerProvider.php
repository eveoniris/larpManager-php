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
 * LarpManager\PersonnageSecondaireControllerProvider
 * 
 * @author kevin
 *
 */
class PersonnageSecondaireControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
	
		/** Liste des archétypes de personnage secondaire */
		$controllers->match('/list','LarpManager\Controllers\PersonnageSecondaireController::indexAction')
			->bind("personnageSecondaire.list")
			->method('GET');

		/** Formulaire d'ajout d'un archétype de personnage secondaire */
		$controllers->match('/add','LarpManager\Controllers\PersonnageSecondaireController::addAction')
			->bind("personnageSecondaire.add")
			->method('GET|POST');
		
		/** Formulaire de modification d'un archétype de personnage secondaire */
		$controllers->match('/{personnageSecondaire}/update','LarpManager\Controllers\PersonnageSecondaireController::updateAction')
			->assert('personnageSecondaire', '\d+')
			->bind("personnageSecondaire.update")
			->method('GET|POST')
			->convert('personnageSecondaire', 'converter.personnageSecondaire:convert');
		
		/** Formulaire de supression d'un archétype de personnage secondaire */
		$controllers->match('/{personnageSecondaire}/delete','LarpManager\Controllers\PersonnageSecondaireController::deleteAction')
			->assert('personnageSecondaire', '\d+')
			->bind("personnageSecondaire.delete")
			->method('GET|POST')
			->convert('personnageSecondaire', 'converter.personnageSecondaire:convert');
			
		return $controllers;
	}
}