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
use LarpManager\Form\DomaineForm;
use LarpManager\Form\DomaineDeleteForm;
use LarpManager\Form\SortForm;
use LarpManager\Form\SortDeleteForm;
use LarpManager\Form\Potion\PotionForm;
use LarpManager\Form\Potion\PotionDeleteForm;
use LarpManager\Form\PriereForm;
use LarpManager\Form\PriereDeleteForm;
use LarpManager\Form\SphereForm;
use LarpManager\Form\SphereDeleteForm;
use LarpManager\Entities\Potion;
use LarpManager\Entities\Priere;
use LarpManager\Entities\Sort;

/**
 * LarpManager\Controllers\MagieController
 *
 * @author kevin
 *
 */
class MagieController
{
		
	/**
	 * Liste des sphere
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sphereListAction(Request $request, Application $app)
	{
		$spheres = $app['orm.em']->getRepository('\LarpManager\Entities\Sphere')->findAll();
	
		return $app['twig']->render('admin/sphere/list.twig', array(
				'spheres' => $spheres,
		));
	}
	
	/**
	 * Detail d'une sphere
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sphereDetailAction(Request $request, Application $app)
	{
		$sphere = $request->get('sphere');
	
		return $app['twig']->render('admin/sphere/detail.twig', array(
				'sphere' => $sphere,
		));
	}
	
	/**
	 * Ajoute une sphere
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sphereAddAction(Request $request, Application $app)
	{
		$sphere = new \LarpManager\Entities\Sphere();
	
		$form = $app['form.factory']->createBuilder(new SphereForm(), $sphere)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$sphere = $form->getData();
				
			$app['orm.em']->persist($sphere);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','La sphere a été ajouté');
			return $app->redirect($app['url_generator']->generate('magie.sphere.detail',array('sphere'=>$sphere->getId())),301);
		}
	
		return $app['twig']->render('admin/sphere/add.twig', array(
				'sphere' => $sphere,
				'form' => $form->createView(),
		));
	
	}
	
	/**
	 * Met à jour une sphere
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sphereUpdateAction(Request $request, Application $app)
	{
		$sphere = $request->get('sphere');
	
		$form = $app['form.factory']->createBuilder(new SphereForm(), $sphere)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$sphere = $form->getData();
	
			$app['orm.em']->persist($sphere);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','La sphere a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('magie.sphere.detail',array('sphere'=>$sphere->getId())),301);
		}
	
		return $app['twig']->render('admin/sphere/update.twig', array(
				'sphere' => $sphere,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime une sphère
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sphereDeleteAction(Request $request, Application $app)
	{
		$sphere = $request->get('sphere');
	
		$form = $app['form.factory']->createBuilder(new SphereDeleteForm(), $sphere)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$sphere = $form->getData();
	
			$app['orm.em']->remove($sphere);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','La sphere a été suprimé');
			return $app->redirect($app['url_generator']->generate('magie.sphere.list'),301);
		}
	
		return $app['twig']->render('admin/sphere/delete.twig', array(
				'sphere' => $sphere,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Liste des prieres
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function priereListAction(Request $request, Application $app)
	{
		$prieres = $app['orm.em']->getRepository('\LarpManager\Entities\Priere')->findAll();
	
		return $app['twig']->render('admin/priere/list.twig', array(
				'prieres' => $prieres,
		));
	}
	
	/**
	 * Detail d'une priere
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function priereDetailAction(Request $request, Application $app)
	{
		$priere = $request->get('priere');
	
		return $app['twig']->render('admin/priere/detail.twig', array(
				'priere' => $priere,
		));
	}
	
	/**
	 * Ajoute une priere
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function priereAddAction(Request $request, Application $app)
	{
		$priere = new \LarpManager\Entities\Priere();
	
		$form = $app['form.factory']->createBuilder(new PriereForm(), $priere)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$priere = $form->getData();
	
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
					
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('magie.priere.list'),301);
				}
					
				$documentFilename = hash('md5',$priere->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$priere->setDocumentUrl($documentFilename);
			}
	
			$app['orm.em']->persist($priere);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','La priere a été ajouté');
			return $app->redirect($app['url_generator']->generate('magie.priere.detail',array('priere'=>$priere->getId())),301);
		}
	
		return $app['twig']->render('admin/priere/add.twig', array(
				'priere' => $priere,
				'form' => $form->createView(),
		));
	
	}
	
	/**
	 * Met à jour une priere
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function priereUpdateAction(Request $request, Application $app)
	{
		$priere = $request->get('priere');
	
		$form = $app['form.factory']->createBuilder(new PriereForm(), $priere)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$priere = $form->getData();
	
			$files = $request->files->get($form->getName());
	
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
	
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
					
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('magie.priere.list'),301);
				}
					
				$documentFilename = hash('md5',$priere->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$priere->setDocumentUrl($documentFilename);
			}
	
			$app['orm.em']->persist($priere);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','La priere a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('magie.priere.detail',array('priere'=>$priere->getId())),301);
		}
	
		return $app['twig']->render('admin/priere/update.twig', array(
				'priere' => $priere,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime une priere
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function priereDeleteAction(Request $request, Application $app)
	{
		$priere = $request->get('priere');
	
		$form = $app['form.factory']->createBuilder(new PriereDeleteForm(), $priere)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$priere = $form->getData();
	
			$app['orm.em']->remove($priere);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','La priere a été suprimé');
			return $app->redirect($app['url_generator']->generate('magie.priere.list'),301);
		}
	
		return $app['twig']->render('admin/priere/delete.twig', array(
				'priere' => $priere,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Obtenir le document lié a une priere
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function getPriereDocumentAction(Request $request, Application $app)
	{
		$document = $request->get('document');
		$priere = $request->get('priere');
	
		// on ne peux télécharger que les documents des compétences que l'on connait
		/*if  ( ! $app['security.authorization_checker']->isGranted('ROLE_REGLE') )
		{
		if ( $app['user']->getPersonnage() )
		{
		if ( ! $app['user']->getPersonnage()->getCompetences()->contains($competence) )
		{
		$app['session']->getFlashBag()->add('error', 'Vous n\'avez pas les droits necessaires');
		}
		}
		}*/
	
		$file = __DIR__.'/../../../private/doc/'.$document;
	
		/*$stream = function () use ($file) {
			readfile($file);
		};
	
		return $app->stream($stream, 200, array(
				'Content-Type' => 'text/pdf',
				'Content-length' => filesize($file),
				'Content-Disposition' => 'attachment; filename="'.$priere->getLabel().'.pdf"'
		));*/
		
		return $app->sendFile($file);
		
	}
	
	/**
	 * Liste des personnages ayant cette prière
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function prierePersonnagesAction(Request $request, Application $app, Priere $priere)
	{
		return $app['twig']->render('admin/priere/personnages.twig', array(
				'priere' => $priere,
		));
	}
	
	/**
	 * Liste des potions
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionListAction(Request $request, Application $app)
	{
		$potions = $app['orm.em']->getRepository('\LarpManager\Entities\Potion')->findAll();
		
		return $app['twig']->render('admin/potion/list.twig', array(
			'potions' => $potions,	
		));
	}
	
	/**
	 * Detail d'une potion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionDetailAction(Request $request, Application $app)
	{
		$potion = $request->get('potion');
	
		return $app['twig']->render('admin/potion/detail.twig', array(
				'potion' => $potion,
		));
	}
	
	/**
	 * Liste des personnages ayant cette potion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionPersonnagesAction(Request $request, Application $app, Potion $potion)
	{
		return $app['twig']->render('admin/potion/personnages.twig', array(
				'potion' => $potion,
		));
	}
	
	/**
	 * Ajoute une potion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionAddAction(Request $request, Application $app)
	{
		$potion = new \LarpManager\Entities\Potion();
	
		$form = $app['form.factory']->createBuilder(new PotionForm(), $potion)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$potion = $form->getData();
	
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
					
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('magie.potion.list'),301);
				}
					
				$documentFilename = hash('md5',$potion->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$potion->setDocumentUrl($documentFilename);
			}
				
			$app['orm.em']->persist($potion);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','La potion a été ajouté');
			return $app->redirect($app['url_generator']->generate('magie.potion.detail',array('potion'=>$potion->getId())),301);
		}
	
		return $app['twig']->render('admin/potion/add.twig', array(
				'potion' => $potion,
				'form' => $form->createView(),
		));
	
	}
	
	/**
	 * Met à jour une potion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionUpdateAction(Request $request, Application $app)
	{
		$potion = $request->get('potion');
	
		$form = $app['form.factory']->createBuilder(new PotionForm(), $potion)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$potion = $form->getData();
	
			$files = $request->files->get($form->getName());
				
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
	
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
					
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('magie.potion.list'),301);
				}
					
				$documentFilename = hash('md5',$potion->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$potion->setDocumentUrl($documentFilename);
			}
				
			$app['orm.em']->persist($potion);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','La potion a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('magie.potion.detail',array('potion'=>$potion->getId())),301);
		}
	
		return $app['twig']->render('admin/potion/update.twig', array(
				'potion' => $potion,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime une potion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionDeleteAction(Request $request, Application $app)
	{
		$potion = $request->get('potion');
	
		$form = $app['form.factory']->createBuilder(new PotionDeleteForm(), $potion)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$potion = $form->getData();
	
			$app['orm.em']->remove($potion);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','La potion a été suprimé');
			return $app->redirect($app['url_generator']->generate('magie.potion.list'),301);
		}
	
		return $app['twig']->render('admin/potion/delete.twig', array(
				'potion' => $potion,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Obtenir le document lié a une potion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function getPotionDocumentAction(Request $request, Application $app)
	{
		$document = $request->get('document');
		$potion = $request->get('potion');
	
		// on ne peux télécharger que les documents des compétences que l'on connait
		/*if  ( ! $app['security.authorization_checker']->isGranted('ROLE_REGLE') )
		{
		if ( $app['user']->getPersonnage() )
		{
		if ( ! $app['user']->getPersonnage()->getCompetences()->contains($competence) )
		{
		$app['session']->getFlashBag()->add('error', 'Vous n\'avez pas les droits necessaires');
		}
		}
		}*/
	
		$file = __DIR__.'/../../../private/doc/'.$document;
	
		$stream = function () use ($file) {
			readfile($file);
		};
	
		return $app->stream($stream, 200, array(
				'Content-Type' => 'text/pdf',
				'Content-length' => filesize($file),
				'Content-Disposition' => 'attachment; filename="'.$potion->getLabel().'.pdf"'
		));
	}
	
	/**
	 * Liste des domaines de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineListAction(Request $request, Application $app)
	{
		$domaines = $app['orm.em']->getRepository('\LarpManager\Entities\Domaine')->findAll();
		
		return $app['twig']->render('admin/domaine/list.twig', array(
				'domaines' => $domaines,
		));
	}
	
	/**
	 * Detail d'un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineDetailAction(Request $request, Application $app)
	{
		$domaine = $request->get('domaine');
		
		return $app['twig']->render('admin/domaine/detail.twig', array(
				'domaine' => $domaine,
		));
	}
	
	/**
	 * Ajoute un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineAddAction(Request $request, Application $app)
	{
		$domaine = new \LarpManager\Entities\Domaine();
		
		$form = $app['form.factory']->createBuilder(new DomaineForm(), $domaine)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$domaine = $form->getData();
			
			$app['orm.em']->persist($domaine);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le domaine de magie a été ajouté');
			return $app->redirect($app['url_generator']->generate('magie.domaine.detail',array('domaine'=>$domaine->getId())),301);
		}
		
		return $app['twig']->render('admin/domaine/add.twig', array(
				'domaine' => $domaine,
				'form' => $form->createView(),
		));
		
	}
	
	/**
	 * Met à jour un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineUpdateAction(Request $request, Application $app)
	{
		$domaine = $request->get('domaine');
		
		$form = $app['form.factory']->createBuilder(new DomaineForm(), $domaine)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$domaine = $form->getData();
				
			$app['orm.em']->persist($domaine);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le domaine de magie a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('magie.domaine.detail',array('domaine'=>$domaine->getId())),301);
		}
		
		return $app['twig']->render('admin/domaine/update.twig', array(
				'domaine' => $domaine,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineDeleteAction(Request $request, Application $app)
	{
		$domaine = $request->get('domaine');
		
		$form = $app['form.factory']->createBuilder(new DomaineDeleteForm(), $domaine)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$domaine = $form->getData();
		
			$app['orm.em']->remove($domaine);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le domaine de magie a été suprimé');
			return $app->redirect($app['url_generator']->generate('magie.domaine.list'),301);
		}
		
		return $app['twig']->render('admin/domaine/delete.twig', array(
				'domaine' => $domaine,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Liste des sortilèges
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortListAction(Request $request, Application $app)
	{
		$sorts = $app['orm.em']->getRepository('\LarpManager\Entities\Sort')->findAll();
	
		return $app['twig']->render('admin/sort/list.twig', array(
				'sorts' => $sorts,
		));
	}
	
	/**
	 * Detail d'un sortilèges
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortDetailAction(Request $request, Application $app)
	{
		$sort = $request->get('sort');
	
		return $app['twig']->render('admin/sort/detail.twig', array(
				'sort' => $sort,
		));
	}
	
	/**
	 * Ajoute un sortilège
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortAddAction(Request $request, Application $app)
	{
		$sort = new \LarpManager\Entities\Sort();
	
		$form = $app['form.factory']->createBuilder(new SortForm(), $sort)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$sort = $form->getData();
				
			
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
					
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('magie.sort.list'),301);
				}
					
				$documentFilename = hash('md5',$sort->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$sort->setDocumentUrl($documentFilename);
			}
			
			$app['orm.em']->persist($sort);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le sortilége a été ajouté');
			return $app->redirect($app['url_generator']->generate('magie.sort.detail',array('sort'=>$sort->getId())),301);
		}
	
		return $app['twig']->render('admin/sort/add.twig', array(
				'sort' => $sort,
				'form' => $form->createView(),
		));
	
	}
	
	/**
	 * Met à jour un sortilège
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortUpdateAction(Request $request, Application $app)
	{
		$sort = $request->get('sort');
	
		$form = $app['form.factory']->createBuilder(new SortForm(), $sort)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$sort = $form->getData();
	
			$files = $request->files->get($form->getName());
			
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
				
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
					
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('magie.sort.list'),301);
				}
					
				$documentFilename = hash('md5',$sort->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$sort->setDocumentUrl($documentFilename);
			}
			
			$app['orm.em']->persist($sort);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le sort a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('magie.sort.detail',array('sort'=>$sort->getId())),301);
		}
	
		return $app['twig']->render('admin/sort/update.twig', array(
				'sort' => $sort,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime un sortilège
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortDeleteAction(Request $request, Application $app)
	{
		$sort = $request->get('sort');
	
		$form = $app['form.factory']->createBuilder(new SortDeleteForm(), $sort)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$sort = $form->getData();
	
			$app['orm.em']->remove($sort);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le sortilège a été suprimé');
			return $app->redirect($app['url_generator']->generate('magie.sort.list'),301);
		}
	
		return $app['twig']->render('admin/sort/delete.twig', array(
				'sort' => $sort,
				'form' => $form->createView(),
		));
	}	
	
	/**
	 * Obtenir le document lié a un sortilège
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function getSortDocumentAction(Request $request, Application $app)
	{
		$document = $request->get('document');
		$sort = $request->get('sort');
		
		// on ne peux télécharger que les documents des compétences que l'on connait
		/*if  ( ! $app['security.authorization_checker']->isGranted('ROLE_REGLE') )
		{
			if ( $app['user']->getPersonnage() )
			{
				if ( ! $app['user']->getPersonnage()->getCompetences()->contains($competence) )
				{
					$app['session']->getFlashBag()->add('error', 'Vous n\'avez pas les droits necessaires');
				}
			}
		}*/
		
		$file = __DIR__.'/../../../private/doc/'.$document;
		
		$stream = function () use ($file) {
			readfile($file);
		};
		
		return $app->stream($stream, 200, array(
				'Content-Type' => 'text/pdf',
				'Content-length' => filesize($file),
				'Content-Disposition' => 'attachment; filename="'.$sort->getLabel().'.pdf"'
		));
	}
	
	/**
	 * Liste des personnages ayant ce sort
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortPersonnagesAction(Request $request, Application $app, Sort $sort)
	{
		return $app['twig']->render('admin/sort/personnages.twig', array(
				'sort' => $sort,
		));
	}
	

}