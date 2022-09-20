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

use LarpManager\Form\AttributeTypeForm;

/**
 * LarpManager\Controllers\AttributeTypeController
 *
 * @author kevin
 *
 */
class AttributeTypeController
{
	/**
	 * Liste des types d'attribut
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\AttributeType');
		$attributes = $repo->findAllOrderedByLabel();
		return $app['twig']->render('admin/attributeType/index.twig', array('attributes' => $attributes));
	}
	
	/**
	 * Ajoute d'un attribut
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$attributeType = new \LarpManager\Entities\AttributeType();
	
		$form = $app['form.factory']->createBuilder(new AttributeTypeForm(), $attributeType)
								->add('save','submit', array('label' => "Sauvegarder"))
								->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
								->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
		    $attributeType = $form->getData();
	
			$app['orm.em']->persist($attributeType);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Le type d\'attribut a été ajoutée.');
	
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('attribute.type'),303);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('attribute.type.add'),303);
			}
		}
	
		return $app['twig']->render('admin/attributeType/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour un attribut
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$attributeType = $app['orm.em']->find('\LarpManager\Entities\AttributeType',$id);
	
		$form = $app['form.factory']->createBuilder(new AttributeTypeForm(), $attributeType)
				->add('update','submit', array('label' => "Sauvegarder"))
				->add('delete','submit', array('label' => "Supprimer"))
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
		    $attributeType = $form->getData();
	
			if ($form->get('update')->isClicked())
			{
			    $app['orm.em']->persist($attributeType);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La type d\'attribut a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
			    $app['orm.em']->remove($attributeType);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le type d\'attribut a été supprimé.');
			}
	
			return $app->redirect($app['url_generator']->generate('attribute.type'));
		}
	
		return $app['twig']->render('admin/attributeType/update.twig', array(
		    'attributeType' => $attributeType,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Detail d'un attribut
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$attributeType = $app['orm.em']->find('\LarpManager\Entities\AttributeType',$id);
	
		if ( $attributeType )
		{
		    return $app['twig']->render('admin/attributeType/detail.twig', array('attributeType' => $attributeType));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'La attribute type n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('attribute.type'));
		}
	}
}