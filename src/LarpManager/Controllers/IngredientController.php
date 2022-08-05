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
use LarpManager\Form\IngredientForm;
use LarpManager\Form\IngredientDeleteForm;

/**
 * LarpManager\Controllers\IngredientController
 *
 * @author kevin
 */
class IngredientController
{
	/**
	 * Liste des ingrédients
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminListAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Ingredient');
		$ingredients = $repo->findAllOrderedByLabel();
		return $app['twig']->render('admin/ingredient/list.twig', array('ingredients' => $ingredients));
	}
	
	/**
	 * Detail d'un ingredient
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDetailAction(Request $request, Application $app)
	{
		$ingredient = $request->get('ingredient');
	
		return $app['twig']->render('admin/ingredient/detail.twig', array(
				'ingredient' => $ingredient,
		));
	}
	
	/**
	 * Ajoute un ingredient
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddAction(Request $request, Application $app)
	{
		$ingredient = new \LarpManager\Entities\Ingredient();
	
		$form = $app['form.factory']->createBuilder(new IngredientForm(), $ingredient)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$titre = $form->getData();
	
			$app['orm.em']->persist($titre);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','L\'ingredient a été ajouté');
			return $app->redirect($app['url_generator']->generate('ingredient.admin.detail',array('ingredient'=>$ingredient->getId())),303);
		}
	
		return $app['twig']->render('admin/ingredient/add.twig', array(
				'ingredient' => $ingredient,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour un ingredient
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateAction(Request $request, Application $app)
	{
		$ingredient = $request->get('ingredient');
	
		$form = $app['form.factory']->createBuilder(new IngredientForm(), $ingredient)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$ingredient = $form->getData();
		
			$app['orm.em']->persist($ingredient);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','L\'ingredient a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('ingredient.admin.detail',array('ingredient'=>$ingredient->getId())),303);
		}
	
		return $app['twig']->render('admin/ingredient/update.twig', array(
				'ingredient' => $ingredient,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime un ingredient
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDeleteAction(Request $request, Application $app)
	{
		$ingredient = $request->get('ingredient');
	
		$form = $app['form.factory']->createBuilder(new IngredientDeleteForm(), $ingredient)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$titre = $form->getData();
	
			$app['orm.em']->remove($ingredient);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','L\'ingredient a été suprimé');
			return $app->redirect($app['url_generator']->generate('ingredient.admin.list'),303);
		}
	
		return $app['twig']->render('admin/ingredient/delete.twig', array(
				'ingredient' => $ingredient,
				'form' => $form->createView(),
		));
	}
}