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
use LarpManager\Form\Classe\ClasseForm;
use LarpManager\Entities\Classe;

/**
 * LarpManager\Controllers\ClasseController
 *
 * @author kevin
 *
 */
class ClasseController
{
	/**
	 * Présentation des classes
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Classe');
		$classes = $repo->findAllOrderedByLabel();
		return $app['twig']->render('classe/list.twig', array('classes' => $classes));
	}
	
	/**
	 * Ajout d'une classe
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$classe = new \LarpManager\Entities\Classe();
		
		$form = $app['form.factory']->createBuilder(new ClasseForm(), $classe)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$classe = $form->getData();
				
			$app['orm.em']->persist($classe);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'La classe a été ajoutée.');
		
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('classe'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('classe.add'),301);
			}
		}
		
		return $app['twig']->render('classe/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Mise à jour d'une classe
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app, Classe $classe)
	{	
		$form = $app['form.factory']->createBuilder(new ClasseForm(), $classe)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$classe = $form->getData();

			if ($form->get('update')->isClicked())
			{			
				$app['orm.em']->persist($classe);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La classe a été mise à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($classe);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'La classe a été supprimée.');
			}
		
			return $app->redirect($app['url_generator']->generate('classe'));
		}
			
		return $app['twig']->render('classe/update.twig', array(
				'classe' => $classe,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Détail d'une classe
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app, Classe $classe)
	{
		return $app['twig']->render('admin/classe/detail.twig', array('classe' => $classe));
	}

}