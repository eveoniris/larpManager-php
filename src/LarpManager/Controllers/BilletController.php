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

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Entities\Billet;
use LarpManager\Form\BilletForm;
use LarpManager\Form\BilletDeleteForm;

class BilletController
{
	/**
	 * Liste des billets
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$billets = $app['orm.em']->getRepository('LarpManager\Entities\Billet')->findAll();
		
		return $app['twig']->render('admin\billet\list.twig', array(
			'billets' => $billets,	
		));
	}
	
	/**
	 * Ajout d'un billet
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new BilletForm(), new Billet())
			->add('submit', 'submit', array('label' => 'Valider'))
			->getForm();

		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$billet = $form->getData();
			$billet->setCreateur($app['user']);
			$app['orm.em']->persist($billet);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le billet a été ajouté.');
			return $app->redirect($app['url_generator']->generate('billet.list'),301);
		}
		
		return $app['twig']->render('admin\billet\add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Détail d'un billet
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function detailAction(Request $request, Application $app, Billet $billet)
	{
		return $app['twig']->render('admin\billet\detail.twig', array(
				'billet' => $billet,
		));
	}
	
	/**
	 * Mise à jour d'un billet
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function updateAction(Request $request, Application $app, Billet $billet)
	{
		$form = $app['form.factory']->createBuilder(new BilletForm(), $billet)
			->add('submit', 'submit', array('label' => 'Valider'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$billet = $form->getData();
			$app['orm.em']->persist($billet);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'Le billet a été mis à jour.');
			return $app->redirect($app['url_generator']->generate('billet.list'),301);
		}
		
		return $app['twig']->render('admin\billet\update.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Suppression d'un billet
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function deleteAction(Request $request, Application $app, Billet $billet)
	{
		$form = $app['form.factory']->createBuilder(new BilletDeleteForm(), $billet)
			->add('submit', 'submit', array('label' => 'Valider'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$billet = $form->getData();
			$app['orm.em']->remove($billet);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'Le billet a été supprimé.');
			return $app->redirect($app['url_generator']->generate('billet.list'),301);
		}
		
		return $app['twig']->render('admin\billet\delete.twig', array(
				'form' => $form->createView(),
				'billet' => $billet,
		));
	}
	
	/**
	 * Liste des utilisateurs ayant ce billet
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function userAction(Request $request, Application $app, Billet $billet)
	{
		return $app['twig']->render('admin\billet\user.twig', array(
				'billet' => $billet,
		));
	}
}