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

use LarpManager\Form\Monnaie\MonnaieForm;
use LarpManager\Form\Monnaie\MonnaieDeleteForm;

use LarpManager\Entities\Monnaie;

/**
 * LarpManager\Controllers\MonnaieController
 *
 * @author kevin
 *
 */
class MonnaieController
{
	
	/**
	 * Liste les monnaies
	 * 
	 * @param Application $app
	 * @param Request $request
	 */
	public function listAction(Application $app, Request $request)
	{
		$monnaies = $app['orm.em']->getRepository('\LarpManager\Entities\Monnaie')->findAll();
		
		return $app['twig']->render('admin/monnaie/list.twig', array(
				'monnaies' => $monnaies,
		));
	}
	
	/**
	 * Ajoute une monnaie
	 * 
	 * @param Application $app
	 * @param Request $request
	 */
	public function addAction(Application $app, Request $request)
	{
		$form = $app['form.factory']->createBuilder(new MonnaieForm(), new Monnaie())
			->add('submit','submit', array('label' => "Enregistrer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$monnaie = $form->getData();
			$app['orm.em']->persist($monnaie);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La monnaie a été enregistrée.');
			return $app->redirect($app['url_generator']->generate('monnaie'),301);
		}
		
		return $app['twig']->render('admin/monnaie/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour une monnaie
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @param Monnaie $monnaie
	 */
	public function updateAction(Application $app, Request $request, Monnaie $monnaie)
	{
		$form = $app['form.factory']->createBuilder(new MonnaieForm(), $monnaie)
			->add('submit','submit', array('label' => "Enregistrer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$monnaie = $form->getData();
			$app['orm.em']->persist($monnaie);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La monnaie a été enregistrée.');
			return $app->redirect($app['url_generator']->generate('monnaie'),301);
		}
			
		return $app['twig']->render('admin/monnaie/update.twig', array(
				'monnaie' => $monnaie,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime une monnaie
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @param Monnaie $monnaie
	 */
	public function deleteAction(Application $app, Request $request, Monnaie $monnaie)
	{
		$form = $app['form.factory']->createBuilder(new MonnaieDeleteForm(), $monnaie)
			->add('submit','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$monnaie = $form->getData();
			$app['orm.em']->remove($monnaie);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La monnaie a été supprimée.');
			return $app->redirect($app['url_generator']->generate('monnaie'),301);
		}
			
		return $app['twig']->render('admin/monnaie/delete.twig', array(
				'monnaie' => $monnaie,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Fourni le détail d'une monnaie
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @param Monnaie $monnaie
	 */
	public function detailAction(Application $app, Request $request, Monnaie $monnaie)
	{
		return $app['twig']->render('admin/monnaie/detail.twig', array(
				'monnaie' => $monnaie,
		));
	}
}