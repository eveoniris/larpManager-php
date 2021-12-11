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
use LarpManager\Form\PersonnageSecondaireForm;
use LarpManager\Form\PersonnageSecondaireDeleteForm;
use LarpManager\Entities\PersonnageSecondaire;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Controllers\PersonnageSecondaireController
 * 
 * @author kevin
 *
 */
class PersonnageSecondaireController
{
	
	/**
	 * affiche la liste des personnages secondaires
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\PersonnageSecondaire');
		$personnageSecondaires = $repo->findAll();
	
		return $app['twig']->render('admin/personnageSecondaire/index.twig', array('personnageSecondaires' => $personnageSecondaires));
	}
	
	/**
	 * Detail d'un personnage secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app, PersonnageSecondaire $personnageSecondaire)
	{
		return $app['twig']->render('admin/personnageSecondaire/detail.twig', array('personnageSecondaire' => $personnageSecondaire));
	}
	
	/**
	 * Ajout d'un personnage secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new PersonnageSecondaireForm(), new PersonnageSecondaire())
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnageSecondaire = $form->getData();

			/**
			 * Pour toutes les compétences de la classe
			 */
			foreach ( $personnageSecondaire->getPersonnageSecondaireCompetences() as $personnageSecondaireCompetence)
			{
				$personnageSecondaireCompetence->setPersonnageSecondaire($personnageSecondaire);
			}
			
			$app['orm.em']->persist($personnageSecondaire);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage secondaire été sauvegardé');
			return $app->redirect($app['url_generator']->generate('personnageSecondaire.list'),303);
		}
			
		return $app['twig']->render('admin/personnageSecondaire/add.twig', array(
				'form' => $form->createView(),
				));
	}
	
	/**
	 * Mise à jour d'un personnage secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app, PersonnageSecondaire $personnageSecondaire)
	{		
		/**
		 *  Crée un tableau contenant les objets personnageSecondaireCompetences courants de la base de données
		 */
		$originalPersonnageSecondaireComptences = new ArrayCollection();
		foreach ($personnageSecondaire->getPersonnageSecondaireCompetences() as $personnageSecondaireCompetence)
		{
			$originalPersonnageSecondaireComptences->add($personnageSecondaireCompetence);
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageSecondaireForm(), $personnageSecondaire)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnageSecondaire = $form->getData();
			
			/**
			 * Pour toutes les compétences de la classe
			 */
			foreach ( $personnageSecondaire->getPersonnageSecondaireCompetences() as $personnageSecondaireCompetence)
			{
				$personnageSecondaireCompetence->setPersonnageSecondaire($personnageSecondaire);
			}
			
			/**
			 *  supprime la relation entre le groupeClasse et le groupe
			 */
			foreach ($originalPersonnageSecondaireComptences as $personnageSecondaireCompetence) {
				if ($personnageSecondaire->getPersonnageSecondaireCompetences()->contains($personnageSecondaireCompetence) == false) 
				{
					$app['orm.em']->remove($personnageSecondaireCompetence);
				}
			}
			
			$app['orm.em']->persist($personnageSecondaire);
			$app['orm.em']->flush();
			$app['session']->getFlashBag()->add('success', 'Le personnage secondaire a été mis à jour.');
			return $app->redirect($app['url_generator']->generate('personnageSecondaire.list'));
		}
		
		return $app['twig']->render('admin/personnageSecondaire/update.twig', array(
				'personnageSecondaire' => $personnageSecondaire,
				'form' => $form->createView(),
				));
	}
	
	/**
	 * Suppression d'un personnage secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function deleteAction(Request $request, Application $app, PersonnageSecondaire $personnageSecondaire)
	{		
		$form = $app['form.factory']->createBuilder(new PersonnageSecondaireDeleteForm(), $personnageSecondaire)
			->add('delete','submit', array('label' => 'Supprimer'))
			->getForm();
	
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnageSecondaire = $form->getData();
		
			foreach ( $personnageSecondaire->getPersonnageSecondaireCompetences() as $personnageSecondaireCompetence)
			{
				$app['orm.em']->remove($personnageSecondaireCompetence);
			}
			
			$app['orm.em']->remove($personnageSecondaire);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le personnage secondaire a été supprimé.');
			return $app->redirect($app['url_generator']->generate('personnageSecondaire.list'),303);
		}
			
		return $app['twig']->render('admin/personnageSecondaire/delete.twig', array(
				'personnageSecondaire' => $personnageSecondaire,
				'form' => $form->createView(),
		));
	}
}