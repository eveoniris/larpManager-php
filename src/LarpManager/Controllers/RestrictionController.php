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

use LarpManager\Entities\Restriction;
use LarpManager\Form\RestrictionForm;
use LarpManager\Form\RestrictionDeleteForm;

/**
 * LarpManager\Controllers\RestrictionsController
 *
 * @author kevin
 *
 */
class RestrictionController
{
	/**
	 * Liste des restrictions
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$restrictions = $app['orm.em']->getRepository('\LarpManager\Entities\Restriction')->findAllOrderedByLabel();
		
		return $app['twig']->render('admin/restriction/list.twig', array('restrictions' => $restrictions));
	}

	/**
	 * Imprimer la liste des restrictions
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function printAction(Request $request, Application $app)
	{
		$restrictions = $app['orm.em']->getRepository('\LarpManager\Entities\Restriction')->findAllOrderedByLabel();
		
		return $app['twig']->render('admin/restriction/print.twig', array('restrictions' => $restrictions));
	}

	/**
	 * Télécharger la liste des restrictions alimentaires
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function downloadAction(Request $request, Application $app)
	{
		$restrictions = $app['orm.em']->getRepository('\LarpManager\Entities\Restriction')->findAllOrderedByLabel();
	
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=eveoniris_restrictions_".date("Ymd").".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
			
		$output = fopen("php://output", "w");
			
		// header
		fputcsv($output,
		array(
		'nom',
		'nombre'), ';');
			
		foreach ($restrictions as $restriction)
		{
			$line = array();
			$line[] = utf8_decode($restriction->getLabel());
			$line[] = utf8_decode($restriction->getUsers()->count());			
			fputcsv($output, $line, ';');
		}
			
		fclose($output);
		exit();
	}
	
	/**
	 * Ajouter une restriction alimentaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new RestrictionForm(), new Restriction())
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$restriction = $form->getData();
			$restriction->setAuteur($app['user']);
				
			$app['orm.em']->persist($restriction);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La restriction a été ajouté.');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('restriction.list'),303);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('restriction.add'),303);
			}
		}
		
		return $app['twig']->render('admin/restriction/add.twig', array(
				'form' => $form->createView()
		));
		
	}
	
	/**
	 * Détail d'une restriction alimentaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Restriction $restriction
	 */
	public function detailAction(Request $request, Application $app, Restriction $restriction)
	{		
		return $app['twig']->render('admin/restriction/detail.twig', array('restriction' => $restriction));
	}
	
	/**
	 * Mise à jour d'un lieu
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Restriction $restriction
	 */
	public function updateAction(Request $request, Application $app, Restriction $restriction)
	{		
		$form = $app['form.factory']->createBuilder(new RestrictionForm(), $restriction)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$restriction = $form->getData();	
			$app['orm.em']->persist($restriction);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La restriction alimentaire a été modifié.');
			return $app->redirect($app['url_generator']->generate('restriction.list'),303);
		}

		return $app['twig']->render('admin/restriction/update.twig', array(
				'restriction' => $restriction,
				'form' => $form->createView(),
		));	
	}
	
	/**
	 * Suppression d'une restriction alimentaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Restriction $restriction
	 */
	public function deleteAction(Request $request, Application $app, Restriction $restriction)
	{	
		$form = $app['form.factory']->createBuilder(new RestrictionDeleteForm(), $restriction)
			->add('save','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$restriction = $form->getData();
				
			$app['orm.em']->remove($restriction);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La restriction alimentaire a été supprimé.');
			return $app->redirect($app['url_generator']->generate('restriction.list'),303);
		}

		return $app['twig']->render('admin/restriction/delete.twig', array(
				'restriction' => $restriction,
				'form' => $form->createView(),
		));
	}

}