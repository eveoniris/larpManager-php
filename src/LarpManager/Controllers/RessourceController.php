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
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query;
use Silex\Application;
use LarpManager\Form\RessourceForm;

/**
 * LarpManager\Controllers\RessourceController
 *
 * @author kevin
 *
 */
class RessourceController
{
	
	/**
	 * API: fourni la liste des ressources
	 * GET /api/ressource
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function apiListAction(Request $request, Application $app)
	{
		$qb = $app['orm.em']->createQueryBuilder();
		$qb->select('Ressource')
			->from('\LarpManager\Entities\Ressource','Ressource');
	
		$query = $qb->getQuery();
	
		$ressources = $query->getResult(Query::HYDRATE_ARRAY);
		return new JsonResponse($ressources);
	}
	
	/**
	 * Liste des ressources
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Ressource');
		$ressources = $repo->findAll();
		return $app['twig']->render('ressource/index.twig', array('ressources' => $ressources));
	}
	
	/**
	 * Ajout d'une ressource
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$ressource = new \LarpManager\Entities\Ressource();
		
		$form = $app['form.factory']->createBuilder(new RessourceForm(), $ressource)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$ressource = $form->getData();
		
			$app['orm.em']->persist($ressource);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'La ressource a été ajoutée.');
		
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('ressource'),303);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('ressource.add'),303);
			}
		}
		
		return $app['twig']->render('ressource/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Mise à jour d'une ressource
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$ressource = $app['orm.em']->find('\LarpManager\Entities\Ressource',$id);
		
		$form = $app['form.factory']->createBuilder(new RessourceForm(), $ressource)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$ressource = $form->getData();
		
			if ($form->get('update')->isClicked())
			{
				$app['orm.em']->persist($ressource);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La ressource a été mise à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($ressource);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'La ressource a été supprimée.');
			}
		
			return $app->redirect($app['url_generator']->generate('ressource'));
		}
				
		return $app['twig']->render('ressource/update.twig', array(
				'ressource' => $ressource,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Affiche de détail d'une ressource
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$ressource = $app['orm.em']->find('\LarpManager\Entities\Ressource',$id);
		
		if ( $ressource )
		{
			return $app['twig']->render('ressource/detail.twig', array('ressource' => $ressource));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'La ressource n\'a pas été trouvée.');
			return $app->redirect($app['url_generator']->generate('ressource'));
		}
	}
	
	public function detailExportAction(Request $request, Application $app)
	{
	
	}
	
	public function exportAction(Request $request, Application $app)
	{
	
	}
}