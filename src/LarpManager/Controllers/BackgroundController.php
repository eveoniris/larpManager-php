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
use JasonGrimes\Paginator;

use LarpManager\Entities\Background;
use LarpManager\Form\BackgroundForm;
use LarpManager\Form\BackgroundFindForm;
use LarpManager\Form\BackgroundDeleteForm;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * LarpManager\Controllers\BackgroundController
 *
 * @author kevin
 *
 */
class BackgroundController
{
	/**
	 * Liste des background pour le joueur
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function joueurAction(Request $request, Application $app)
	{
		// l'utilisateur doit avoir un personnage
		$personnage = $app['user']->getPersonnage();
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error','Désolé, pas de personnage, pas de background.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}	
		
		$backsGroupe = new ArrayCollection();
		$backsJoueur = new ArrayCollection();
		
		// recherche les backgrounds liés au personnage (visibilité == OWNER)
		$backsJoueur = $personnage->getBackgrounds('OWNER');
		
		// recherche les backgrounds liés au groupe (visibilité == PUBLIC)
		$backsGroupe = new ArrayCollection(array_merge(
			$personnage->getGroupe()->getBacks('PUBLIC')->toArray(),
			$backsGroupe->toArray()
		));
		
		// recherche les backgrounds liés au groupe (visibilité == GROUP_MEMBER)
		$backsGroupe = new ArrayCollection(array_merge(
			$personnage->getGroupe()->getBacks('GROUPE_MEMBER')->toArray(),
			$backsGroupe->toArray()
		));
		
		// recherche les backgrounds liés au groupe (visibilité == GROUP_OWNER)
		if ( $app['user'] == $personnage->getGroupe()->getUserRelatedByResponsableId() )
		{
			$backsGroupe = new ArrayCollection(array_merge(
				$personnage->getGroupe()->getBacks('GROUPE_OWNER')->toArray(),
				$backsGroupe->toArray()
			));
		}
		
		return $app['twig']->render('public/background.twig', array(
				'backsJoueur' => $backsJoueur,
				'backsGroupe' => $backsGroupe,
				'personnage' => $personnage,
		));	
	}
	
	/**
	 * Présentation des backgrounds
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$order_by = $request->get('order_by') ?: 'id';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		$criteria = array();
		
		$form = $app['form.factory']->createBuilder(new BackgroundFindForm())
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			// TODO
			/*$data = $form->getData();
			$type = $data['type'];
			$value = $data['value'];
			switch ($type){
				case 'Auteur':
					$criteria[] = "g.nom LIKE '%$value%'";
					break;
				case 'Groupe':
					$criteria[] = "u.name LIKE '%$value%'";
					break;
			}*/
		}
	
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Background');
		$backgrounds = $repo->findBy(
				$criteria,
				array( $order_by => $order_dir),
				$limit,
				$offset);
	
		$numResults = $repo->findCount($criteria);
	
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('background.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
	
		return $app['twig']->render('admin/background/list.twig', array(
				'backgrounds' => $backgrounds,
				'paginator' => $paginator,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Ajout d'un background
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$background = new \LarpManager\Entities\Background();
		$groupeId = $request->get('groupe');
		
		if ( $groupeId )
		{
			$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe', $groupeId);
			if ( $groupe ) $background->setGroupe($groupe);
		}
		
		$form = $app['form.factory']->createBuilder(new BackgroundForm(), $background)
			->add('visibility','choice', array(
					'required' => true,
					'label' =>  'Visibilité',
					'choices' => $app['larp.manager']->getVisibility(),
			))
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
			
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$background = $form->getData();
			$background->setUser($app['user']);
		
			$app['orm.em']->persist($background);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le background a été ajouté.');
			return $app->redirect($app['url_generator']->generate('groupe.detail', array('index' => $background->getGroupe()->getId())),301);
		}
		
		return $app['twig']->render('admin/background/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Suppression d'un background
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Background $background
	 */
	public function deleteAction(Request $request, Application $app, Background $background)
	{
		$form = $app['form.factory']->createBuilder(new BackgroundDeleteForm(), $background)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
			
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$background = $form->getData();
			$app['orm.em']->remove($background);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le background a été supprimé.');
			return $app->redirect($app['url_generator']->generate('groupe.detail', array('index' => $background->getGroupe()->getId())),301);
		}
		
		return $app['twig']->render('admin/background/delete.twig', array(
				'form' => $form->createView(),
				'background' => $background
		));
	}
	
	/**
	 * Mise à jour d'un background
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app, Background $background)
	{				
		$form = $app['form.factory']->createBuilder(new BackgroundForm(), $background)
			->add('visibility','choice', array(
				'required' => true,
				'label' =>  'Visibilité',
				'choices' => $app['larp.manager']->getVisibility(),
			))
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
			
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$background = $form->getData();
			$background->setUpdateDate(new \Datetime('NOW'));
		
			$app['orm.em']->persist($background);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'Le background a été ajouté.');
			return $app->redirect($app['url_generator']->generate('groupe.detail', array('index' => $background->getGroupe()->getId())),301);
		}
		
		return $app['twig']->render('admin/background/update.twig', array(
				'form' => $form->createView(),
				'background' => $background
		));
	}
	
	/**
	 * Détail d'un background
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app, Background $background)
	{		
		return $app['twig']->render('admin/background/detail.twig', array(
				'background' => $background
		));
	}
}