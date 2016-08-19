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

use LarpManager\Form\Type\TagType;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

/**
 * LarpManager\Controllers\StockTagController
 *
 * @author kevin
 *
 */
class StockTagController
{

	/**
	 * Liste des tags
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Tag');
		$tags = $repo->findAll();
	
		return $app['twig']->render('stock/tag/index.twig', array('tags' => $tags));
	}
	
	/**
	 * @description ajoute un tag
	 */
	public function addAction(Request $request, Application $app)
	{
		$tag = new \LarpManager\Entities\Tag();
	
		$form = $app['form.factory']->createBuilder(new TagType(), $tag)
				->add('save','submit')
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$tag = $form->getData();				
			$app['orm.em']->persist($tag);
			$app['orm.em']->flush();

			$app['session']->getFlashBag()->add('success', 'Le tag a été ajouté.');
			return $app->redirect($app['url_generator']->generate('stock_tag_index'));
		}
	
		return $app['twig']->render('stock/tag/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un tag
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Tag');
		$tag = $repo->find($id);
	
		$form = $app['form.factory']->createBuilder(new TagType(), $tag)
			->add('update','submit')
			->add('delete','submit')
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$tag = $form->getData();
	
			if ($form->get('update')->isClicked()) 
			{
				$app['orm.em']->persist($tag);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Le tag a été modifié.');
			}
			else if ($form->get('delete')->isClicked()) 
			{
				$app['orm.em']->remove($tag);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Le tag a été supprimé.');
			}
	
			return $app->redirect($app['url_generator']->generate('stock_tag_index'));
		}
		
		return $app['twig']->render('stock/tag/update.twig', array(
				'tag' => $tag,
				'form' => $form->createView()));
	}
}