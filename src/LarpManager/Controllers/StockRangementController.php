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

use LarpManager\Form\Type\RangementType;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * LarpManager\Controllers\StockRangementController
 *
 * @author kevin
 *
 */
class StockRangementController
{
	/**
	 * @description affiche la liste des rangements
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Rangement');
		$rangements = $repo->findAll();
	
		return $app['twig']->render('stock/rangement/index.twig', array('rangements' => $rangements));
	}
	
	/**
	 * @description ajoute un rangement
	 */
	public function addAction(Request $request, Application $app)
	{
		$rangement = new \LarpManager\Entities\Rangement();
	
		$form = $app['form.factory']->createBuilder(new RangementType(), $rangement)
				->add('save','submit')
				->getForm();
	
		// on passe la requête de l'utilisateur au formulaire
		$form->handleRequest($request);
	
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$rangement = $form->getData();
			$app['orm.em']->persist($rangement);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'Le rangement a été ajoutée.');
			return $app->redirect($app['url_generator']->generate('stock_rangement_index'));
		}
	
		return $app['twig']->render('stock/rangement/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un rangement
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Rangement');
		$rangement = $repo->find($id);
	
		$form = $app['form.factory']->createBuilder(new RangementType(), $rangement)
			->add('update','submit')
			->add('delete','submit')
			->getForm();
				
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$rangement = $form->getData();
	
			if ($form->get('update')->isClicked()) 
			{
				$app['orm.em']->persist($rangement);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le rangement a été mise à jour');
			}
			else if ($form->get('delete')->isClicked()) 
			{
				$app['orm.em']->remove($rangement);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le rangement a été suprimé');
			}
			

			return $app->redirect($app['url_generator']->generate('stock_rangement_index'));
		}
		return $app['twig']->render('stock/rangement/update.twig', array(
				'rangement' => $rangement,
				'form' => $form->createView()));
	}	
}
