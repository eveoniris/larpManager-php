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

use LarpManager\Form\Type\EtatType;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * LarpManager\Controllers\StockEtatController
 *
 * @author kevin
 *
 */
class StockEtatController
{

	/**
	 * @description affiche la liste des etats
	 */
	public function indexAction(Request $request, Application $app)
	{		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etats = $repo->findAll();
	
		return $app['twig']->render('stock/etat/index.twig', array('etats' => $etats));
	}
		
	/**
	 * @description ajoute un etat
	 */
	public function addAction(Request $request, Application $app)
	{
		$etat = new \LarpManager\Entities\Etat();
	
		$form = $app['form.factory']->createBuilder(new EtatType(), $etat)
				->add('save','submit')
				->getForm();
	
		// on passe la requête de l'utilisateur au formulaire
		$form->handleRequest($request);
	
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$etat = $form->getData();
			$app['orm.em']->persist($etat);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'L\'état a été ajouté.');
	
			return $app->redirect($app['url_generator']->generate('stock_etat_index'));
		}
	
		return $app['twig']->render('stock/etat/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un etat
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etat = $repo->find($id);
	
		$form = $app['form.factory']->createBuilder(new EtatType(), $etat)
				->add('update','submit')
				->add('delete','submit')
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$etat = $form->getData();
	
			if ($form->get('update')->isClicked()) 
			{
				$app['orm.em']->persist($etat);
				$app['orm.em']->flush();			
				$app['session']->getFlashBag()->add('success', 'L\'état a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked()) 
			{
				$app['orm.em']->remove($etat);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'L\'état a été supprimé.');
			}
			
			return $app->redirect($app['url_generator']->generate('stock_etat_index'));
			
		}
		return $app['twig']->render('stock/etat/update.twig', array(
				'etat' => $etat,
				'form' => $form->createView()));
	}	
}