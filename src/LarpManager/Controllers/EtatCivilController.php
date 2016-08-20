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

namespace LarpManager\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

use LarpManager\Entities\EtatCivil;

/**
 * LarpManager\Controllers\EtatCivilController
 *
 * @author kevin
 *
 */
class EtatCivilController
{

	/**
	 * Affiche l'état civil de l'utilisateur
	 * @param Application $app
	 * @param Request $request
	 */
	public function detailAction(Application $app, Request $request, EtatCivil $etatCivil)
	{
		return $app['twig']->render('admin/etatCivil/detail.twig', array(
				'etatCivil' => $etatCivil,
		));
	}
}