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
use LarpManager\Form\TitreForm;
use LarpManager\Form\TitreDeleteForm;

/**
 * LarpManager\Controllers\TitreController
 *
 * @author kevin
 */
class TitreController
{
	/**
	 * Liste des titres
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminListAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Titre');
		$titres = $repo->findAll();
		

		return $app['twig']->render('admin/titre/list.twig', array('titres' => $titres));
	}
	
	
	/**
	 * Detail d'un titre
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDetailAction(Request $request, Application $app)
	{
		$titre = $request->get('titre');
	
		return $app['twig']->render('admin/titre/detail.twig', array(
				'titre' => $titre,
		));
	}
	
	/**
	 * Ajoute un titre
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddAction(Request $request, Application $app)
	{
		$titre = new \LarpManager\Entities\Titre();
	
		$form = $app['form.factory']->createBuilder(new TitreForm(), $titre)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$titre = $form->getData();
	
			$app['orm.em']->persist($titre);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Le titre a été ajouté');
			return $app->redirect($app['url_generator']->generate('titre.admin.detail',array('titre'=>$titre->getId())),303);
		}
	
		return $app['twig']->render('admin/titre/add.twig', array(
				'titre' => $titre,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour un titre
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateAction(Request $request, Application $app)
	{
		$titre = $request->get('titre');
	
		$form = $app['form.factory']->createBuilder(new TitreForm(), $titre)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$titre = $form->getData();
		
			$app['orm.em']->persist($titre);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Le titre a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('titre.admin.detail',array('titre'=>$titre->getId())),303);
		}
	
		return $app['twig']->render('admin/titre/update.twig', array(
				'titre' => $titre,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime un titre
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDeleteAction(Request $request, Application $app)
	{
		$titre = $request->get('titre');
	
		$form = $app['form.factory']->createBuilder(new TitreDeleteForm(), $titre)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$titre = $form->getData();
	
			$app['orm.em']->remove($titre);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Le titre a été suprimé');
			return $app->redirect($app['url_generator']->generate('titre.admin.list'),303);
		}
	
		return $app['twig']->render('admin/titre/delete.twig', array(
				'titre' => $titre,
				'form' => $form->createView(),
		));
	}
}