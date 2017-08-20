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

use LarpManager\Entities\Restauration;
use LarpManager\Form\RestaurationForm;
use LarpManager\Form\RestaurationDeleteForm;

/**
 * LarpManager\Controllers\RestaurationsController
 *
 * @author kevin
 *
 */
class RestaurationController
{
	/**
	 * Liste des restaurations
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$restaurations = $app['orm.em']->getRepository('\LarpManager\Entities\Restauration')->findAllOrderedByLabel();
		
		return $app['twig']->render('admin/restauration/list.twig', array('restaurations' => $restaurations));
	}

	/**
	 * Imprimer la liste des restaurations
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function printAction(Request $request, Application $app)
	{
		$restaurations = $app['orm.em']->getRepository('\LarpManager\Entities\Restauration')->findAllOrderedByLabel();
		
		return $app['twig']->render('admin/restauration/print.twig', array('restaurations' => $restaurations));
	}

	/**
	 * Télécharger la liste des restaurations alimentaires
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function downloadAction(Request $request, Application $app)
	{
		$restaurations = $app['orm.em']->getRepository('\LarpManager\Entities\Restauration')->findAllOrderedByLabel();
	
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=eveoniris_restaurations_".date("Ymd").".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
			
		$output = fopen("php://output", "w");
			
		// header
		fputcsv($output,
		array(
		'nom',
		'nombre'), ';');
			
		foreach ($restaurations as $restauration)
		{
			$line = array();
			$line[] = utf8_decode($restauration->getLabel());
			$line[] = utf8_decode($restauration->getUsers()->count());			
			fputcsv($output, $line, ';');
		}
			
		fclose($output);
		exit();
	}
	
	/**
	 * Ajouter une restauration
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new RestaurationForm(), new Restauration())
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$restauration = $form->getData();
				
			$app['orm.em']->persist($restauration);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La restauration a été ajouté.');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('restauration.list'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('restauration.add'),301);
			}
		}
		
		return $app['twig']->render('admin/restauration/add.twig', array(
				'form' => $form->createView()
		));
		
	}
	
	/**
	 * Détail d'un lieu de restauration
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Restauration $restauration
	 */
	public function detailAction(Request $request, Application $app, Restauration $restauration)
	{		
		return $app['twig']->render('admin/restauration/detail.twig', array('restauration' => $restauration));
	}
	
	/**
	 * Liste des utilisateurs ayant ce lieu de restauration
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Restauration $restauration
	 */
	public function usersAction(Request $request, Application $app, Restauration $restauration)
	{
		return $app['twig']->render('admin/restauration/users.twig', array('restauration' => $restauration));
	}
	
	/**
	 * Liste des utilisateurs ayant ce lieu de restauration
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Restauration $restauration
	 */
	public function usersExportAction(Request $request, Application $app, Restauration $restauration)
	{
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=eveoniris_restaurations_".$restauration->getId()."_".date("Ymd").".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
			
		$output = fopen("php://output", "w");
			
		// header
		fputcsv($output,
		array(
		'nom',
		'prenom',
		'restriction'), ';');
			
		foreach ($restauration->getUserByGn() as $userByGn)
		{
			foreach ( $userByGn['users'] as $user)
			{
				$restriction = '';
				foreach ( $user->getRestrictions() as $r)
				{
					$restriction .= $r->getLabel() . ' - ';
				}
				$line = array();
				$line[] = utf8_decode($user->getEtatCivil()->getNom());
				$line[] = utf8_decode($user->getEtatCivil()->getPrenom());
				$line[] = utf8_decode($restriction);			
				fputcsv($output, $line, ';');
			}
		}
			
		fclose($output);
		exit();
	}
	
	/**
	 * Liste des restrictions alimentaires
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Restauration $restauration
	 */
	public function restrictionsAction(Request $request, Application $app, Restauration $restauration)
	{		
		return $app['twig']->render('admin/restauration/restrictions.twig', array(
			'restauration' => $restauration,
		));
	}
	
	/**
	 * Mise à jour d'un lieu de restauration
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Restauration $restauration
	 */
	public function updateAction(Request $request, Application $app, Restauration $restauration)
	{		
		$form = $app['form.factory']->createBuilder(new RestaurationForm(), $restauration)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$restauration = $form->getData();	
			$app['orm.em']->persist($restauration);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La restauration alimentaire a été modifié.');
			return $app->redirect($app['url_generator']->generate('restauration.list'),301);
		}

		return $app['twig']->render('admin/restauration/update.twig', array(
				'restauration' => $restauration,
				'form' => $form->createView(),
		));	
	}
	
	/**
	 * Suppression d'un lieu de restauration
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Restauration $restauration
	 */
	public function deleteAction(Request $request, Application $app, Restauration $restauration)
	{	
		$form = $app['form.factory']->createBuilder(new RestaurationDeleteForm(), $restauration)
			->add('save','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$restauration = $form->getData();
				
			$app['orm.em']->remove($restauration);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'Le lieu de restauration a été supprimé.');
			return $app->redirect($app['url_generator']->generate('restauration.list'),301);
		}

		return $app['twig']->render('admin/restauration/delete.twig', array(
				'restauration' => $restauration,
				'form' => $form->createView(),
		));
	}

}