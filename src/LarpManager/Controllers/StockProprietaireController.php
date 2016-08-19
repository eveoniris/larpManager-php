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

use LarpManager\Form\Type\ProprietaireType;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * LarpManager\Controllers\StockProprietaireController
 *
 * @author kevin
 *
 */
class StockProprietaireController
{
	/**
	 * @description affiche la liste des proprietaire
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Proprietaire');
		$proprietaires = $repo->findAll();
	
		return $app['twig']->render('stock/proprietaire/index.twig', array('proprietaires' => $proprietaires));
	}
	
	/**
	 * @description Ajoute un proprietaire
	 */
	public function addAction(Request $request, Application $app)
	{
		$proprietaire = new \LarpManager\Entities\Proprietaire();
	
		$form = $app['form.factory']->createBuilder(new ProprietaireType(), $proprietaire)
				->add('save','submit')
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$proprietaire = $form->getData();
			$app['orm.em']->persist($proprietaire);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Le propriétaire a été ajouté');
			return $app->redirect($app['url_generator']->generate('stock_proprietaire_index'));
		}
	
		return $app['twig']->render('stock/proprietaire/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un proprietaire
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Proprietaire');
		$proprietaire = $repo->find($id);
	
		$form = $app['form.factory']->createBuilder(new ProprietaireType(), $proprietaire)
				->add('update','submit')
				->add('delete','submit')
				->getForm();
			
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$proprietaire = $form->getData();
					
			if ($form->get('update')->isClicked()) 
			{
				$app['orm.em']->persist($proprietaire);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Le propriétaire a été mis à jour');
			}
			else if ($form->get('delete')->isClicked()) 
			{
				$app['orm.em']->remove($proprietaire);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Le proprietaire a été supprimé');
			}

			return $app->redirect($app['url_generator']->generate('stock_proprietaire_index'));				
		}
	
		return $app['twig']->render('stock/proprietaire/update.twig', array('proprietaire' => $proprietaire,'form' => $form->createView()));
	}
}