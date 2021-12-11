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

use LarpManager\Form\AppelationForm;
use LarpManager\Entities\Appelation;

/**
 * LarpManager\Controllers\AppelationController
 *
 * @author kevin
 *
 */
class AppelationController
{
	/**
	 * affiche le tableau de bord de gestion des appelations
	 */
	public function indexAction(Request $request, Application $app)
	{
		$appelations = $app['orm.em']->getRepository('\LarpManager\Entities\Appelation')->findAll();
		$appelations = $app['larp.manager']->sortAppelation($appelations);
		
		return $app['twig']->render('admin/appelation/index.twig', array('appelations' => $appelations));
	}
	
	/**
	 * Detail d'une appelation
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app, Appelation $appelation)
	{		
		return $app['twig']->render('admin/appelation/detail.twig', array('appelation' => $appelation));
	}
	
	/**
	 * Ajoute une appelation
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{		
		$form = $app['form.factory']->createBuilder(new AppelationForm(), new Appelation())
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$appelation = $form->getData();
			$app['orm.em']->persist($appelation);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'L\'appelation a été ajoutée.');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('appelation'),303);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('appelation.add'),303);
			}
		}
		
		return $app['twig']->render('admin/appelation/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modifie une appelation
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app, Appelation $appelation)
	{		
		$form = $app['form.factory']->createBuilder(new AppelationForm(), $appelation)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$appelation = $form->getData();
		
			if ( $form->get('update')->isClicked())
			{
				$app['orm.em']->persist($appelation);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'L\'appelation a été mise à jour.');
				
				return $app->redirect($app['url_generator']->generate('appelation.detail',array('appelation' => $id)),303);
			}
			else if ( $form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($appelation);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'L\'appelation a été supprimée.');
				return $app->redirect($app['url_generator']->generate('appelation'),303);
			}
		}

		return $app['twig']->render('admin/appelation/update.twig', array(
				'appelation' => $appelation,
				'form' => $form->createView(),
		));
	}
}