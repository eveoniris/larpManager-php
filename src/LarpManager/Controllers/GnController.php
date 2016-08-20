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

use LarpManager\Form\GnForm;
use LarpManager\Entities\Gn;
use JasonGrimes\Paginator;

/**
 * LarpManager\Controllers\GnController
 *
 * @author kevin
 *
 */
class GnController
{
	/**
	 * @description affiche la liste des gns
	 */
	public function listAction(Request $request, Application $app) 
	{
		$order_by = $request->get('order_by') ?: 'id';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		
		$criteria = array();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Gn');
		$gns = $repo->findBy(
				$criteria,
				array( $order_by => $order_dir),
				$limit,
				$offset);
		
		$numResults = $repo->findCount($criteria);
		
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('gn.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
		
		return $app['twig']->render('admin/gn/list.twig', array(
				'gns' => $gns,
				'paginator' => $paginator,
		));
	}
	
	/**
	 * affiche le formulaire d'ajout d'un gn
	 * Lorsqu'un GN est créé, son forum associé doit lui aussi être créé
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{	
		$form = $app['form.factory']->createBuilder(new GnForm(), new Gn())
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$gn = $form->getData();
			
			/**
			 * Création du topic associé à ce gn
			 * @var \LarpManager\Entities\Topic $topic
			 */
			$topic = new \LarpManager\Entities\Topic();
			$topic->setTitle($gn->getLabel());
			$topic->setDescription($gn->getDescription());
			$topic->setUser($app['user']);
			// défini les droits d'accés à ce forum
			// (les participants au GN ont le droits d'accéder à ce forum)
			$topic->setRight('GN_PARTICIPANT');
			
			$gn->setTopic($topic);
			$app['orm.em']->persist($gn);
			$app['orm.em']->flush();			

			$app['orm.em']->persist($topic);
			$topic->setObjectId($gn->getId());
			$app['orm.em']->persist($gn);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Le gn a été ajouté.');
	
			return $app->redirect($app['url_generator']->generate('gn.list'),301);
		}
	
		return $app['twig']->render('admin/gn/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Detail d'un gn
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app, Gn $gn)
	{	
		if ( $app['security.authorization_checker']->isGranted('ROLE_ADMIN') )
		{
			return $app['twig']->render('admin/gn/detail.twig', array('gn' => $gn));
		}
		else
		{
			return $app['twig']->render('public/gn/detail.twig', array('gn' => $gn));
		}	
	}
	
	/**
	 * Met à jour un gn
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app, Gn $gn)
	{	
		$form = $app['form.factory']->createBuilder(new GnForm(), $gn)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$gn = $form->getData();
	
			if ($form->get('update')->isClicked())
			{	
				$app['orm.em']->persist($gn);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le gn a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($gn);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le gn a été supprimé.');
			}
	
			return $app->redirect($app['url_generator']->generate('gn.list'));
		}
	
		return $app['twig']->render('admin/gn/update.twig', array(
				'gn' => $gn,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Détail des ventes et participation au Gn
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Gn $gn
	 */
	public function venteAction(Request $request, Application $app, Gn $gn)
	{
		return $app['twig']->render('admin/gn/vente.twig', array(
				'gn' => $gn,
		));
	}
}
