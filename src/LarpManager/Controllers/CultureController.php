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
use LarpManager\Entities\Culture;
use LarpManager\Form\Culture\CultureForm;
use LarpManager\Form\Culture\CultureDeleteForm;

class CultureController
{
	/**
	 * Liste des culture
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$cultures = $app['orm.em']->getRepository('LarpManager\Entities\Culture')->findAll();
		
		return $app['twig']->render('admin\culture\index.twig', array(
			'cultures' => $cultures,	
		));
	}
	
	/**
	 * Ajout d'une culture
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{				
		$form = $app['form.factory']->createBuilder(new CultureForm(),new Culture())->getForm();
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$culture = $form->getData();
			$app['orm.em']->persist($culture);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La culture a été ajoutée.');
			return $app->redirect($app['url_generator']->generate('culture'),301);
		}
		
		return $app['twig']->render('admin\culture\add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Détail d'une culture
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function detailAction(Request $request, Application $app, Culture $culture)
	{
		return $app['twig']->render('admin\culture\detail.twig', array(
				'culture' => $culture,
		));
	}
	
	/**
	 * Mise à jour d'une culture
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function updateAction(Request $request, Application $app, Culture $culture)
	{
		$form = $app['form.factory']->createBuilder(new CultureForm(), $culture)
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$culture = $form->getData();
			$app['orm.em']->persist($culture);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La culture a été mise à jour.');
			return $app->redirect($app['url_generator']->generate('culture'),301);
		}
		
		return $app['twig']->render('admin\culture\update.twig', array(
				'form' => $form->createView(),
				'culture' => $culture,
		));
	}
	
	/**
	 * Suppression d'une culture
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function deleteAction(Request $request, Application $app, Culture $culture)
	{
		$form = $app['form.factory']->createBuilder(new CultureDeleteForm(), $culture)
			->add('submit', 'submit', array('label' => 'Supprimer'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$culture = $form->getData();
			$app['orm.em']->remove($culture);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La culture a été supprimée.');
			return $app->redirect($app['url_generator']->generate('culture'),301);
		}
		
		return $app['twig']->render('admin\culture\delete.twig', array(
				'form' => $form->createView(),
				'culture' => $culture,
		));
	}
}