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

use LarpManager\Form\Type\LocalisationType;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * LarpManager\Controllers\StockLocalisationController
 *
 * @author kevin
 *
 */
class StockLocalisationController
{
	/**
	 * @description affiche la liste des localisation
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Localisation');
		$localisations = $repo->findAll();
	
		return $app['twig']->render('stock/localisation/index.twig', array('localisations' => $localisations));
	}
	
	/**
	 * @description ajoute une localisation
	 */
	public function addAction(Request $request, Application $app)
	{
		$localisation = new \LarpManager\Entities\Localisation();
	
		$form = $app['form.factory']->createBuilder(new LocalisationType(), $localisation)
				->add('save','submit')
				->getForm();
	
		// on passe la requête de l'utilisateur au formulaire
		$form->handleRequest($request);
	
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$localisation = $form->getData();
			$app['orm.em']->persist($localisation);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La localisation a été ajoutée.');
			return $app->redirect($app['url_generator']->generate('stock_localisation_index'));
		}
	
		return $app['twig']->render('stock/localisation/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour une localisation
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Localisation');
		$localisation = $repo->find($id);
	
		$form = $app['form.factory']->createBuilder(new LocalisationType(), $localisation)
			->add('update','submit')
			->add('delete','submit')
			->getForm();
				
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$localisation = $form->getData();
	
			if ($form->get('update')->isClicked()) 
			{
				$app['orm.em']->persist($localisation);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La localisation a été mise à jour');
			}
			else if ($form->get('delete')->isClicked()) 
			{
				$app['orm.em']->remove($localisation);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La localisation a été suprimée');
			}
			

			return $app->redirect($app['url_generator']->generate('stock_localisation_index'));
		}
		return $app['twig']->render('stock/localisation/update.twig', array(
				'localisation' => $localisation,
				'form' => $form->createView()));
	}	
}