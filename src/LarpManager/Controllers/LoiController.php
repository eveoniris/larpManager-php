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
use LarpManager\Entities\Loi;
use LarpManager\Form\Loi\LoiForm;
use LarpManager\Form\Loi\LoiDeleteForm;

class LoiController
{
	/**
	 * Liste des loi
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$lois = $app['orm.em']->getRepository('LarpManager\Entities\Loi')->findAll();
		
		return $app['twig']->render('admin\loi\index.twig', array(
			'lois' => $lois,	
		));
	}
	
	/**
	 * Ajout d'une loi
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{				
		$form = $app['form.factory']->createBuilder(new LoiForm(),new Loi())->getForm();
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$loi = $form->getData();
			
			$files = $request->files->get($form->getName());
			
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
					
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('loi'),301);
				}
					
				$documentFilename = hash('md5',$loi->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$loi->setDocumentUrl($documentFilename);
			}
			
			$app['orm.em']->persist($loi);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La loi a été ajoutée.');
			return $app->redirect($app['url_generator']->generate('loi'),301);
		}
		
		return $app['twig']->render('admin\loi\add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Détail d'une loi
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function detailAction(Request $request, Application $app, Loi $loi)
	{
		return $app['twig']->render('admin\loi\detail.twig', array(
				'loi' => $loi,
		));
	}
	
	/**
	 * Mise à jour d'une loi
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function updateAction(Request $request, Application $app, Loi $loi)
	{
		$form = $app['form.factory']->createBuilder(new LoiForm(), $loi)
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$loi = $form->getData();
			
			$files = $request->files->get($form->getName());
			
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
					
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('loi'),301);
				}
					
				$documentFilename = hash('md5',$loi->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$loi->setDocumentUrl($documentFilename);
			}
			
			$app['orm.em']->persist($loi);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La loi a été mise à jour.');
			return $app->redirect($app['url_generator']->generate('loi'),301);
		}
		
		return $app['twig']->render('admin\loi\update.twig', array(
				'form' => $form->createView(),
				'loi' => $loi,
		));
	}
	
	/**
	 * Suppression d'une loi
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function deleteAction(Request $request, Application $app, Loi $loi)
	{
		$form = $app['form.factory']->createBuilder(new LoiDeleteForm(), $loi)
			->add('submit', 'submit', array('label' => 'Supprimer'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$loi = $form->getData();
			
			$app['orm.em']->remove($loi);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La loi a été supprimée.');
			return $app->redirect($app['url_generator']->generate('loi'),301);
		}
		
		return $app['twig']->render('admin\loi\delete.twig', array(
				'form' => $form->createView(),
				'loi' => $loi,
		));
	}
	
	/**
	 * Retire le document d'une competence
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Competence $competence
	 */
	public function removeDocumentAction(Request $request, Application $app, Loi $loi)
	{
		$loi->setDocumentUrl(null);
	
		$app['orm.em']->persist($loi);
		$app['orm.em']->flush();
		$app['session']->getFlashBag()->add('success', 'La loi a été mise à jour.');
	
		return $app->redirect($app['url_generator']->generate('loi'));
	}
	
	/**
	 * Téléchargement du document lié à une compétence
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function getDocumentAction(Request $request, Application $app)
	{
		
		$loi = $request->get('loi');
		$document = $loi->getDocumentUrl();
		
		$file = __DIR__.'/../../../private/doc/'.$document;
	
		$stream = function () use ($file) {
			readfile($file);
		};
	
		return $app->stream($stream, 200, array(
				'Content-Type' => 'text/pdf',
				'Content-length' => filesize($file),
				'Content-Disposition' => 'attachment; filename="'.$loi->getLabel().'.pdf"'
		));
	}
}