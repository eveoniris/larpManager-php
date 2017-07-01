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

use LarpManager\Form\CompetenceForm;
use LarpManager\Entities\Competence;

/**
 * LarpManager\Controllers\CompetenceController
 *
 * @author kevin
 *
 */
class CompetenceController
{
	/**
	 * Liste des compétences
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Competence');
		$competences = $repo->findAllOrderedByLabel();
			
		return $app['twig']->render('admin/competence/index.twig', array('competences' => $competences));
	}

	/**
	 * Liste des perso ayant cette compétence
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function persoAction(Request $request, Application $app)
	{
		$competence = $request->get('competence');
			
		return $app['twig']->render('admin/competence/perso.twig', array('competence' => $competence));
	}
	
	/**
	 * Liste du matériel necessaire par compétence
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function materielAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Competence');
		$competences = $repo->findAllOrderedByLabel();
			
		return $app['twig']->render('admin/competence/materiel.twig', array('competences' => $competences));
	}
	
	/**
	 * Ajout d'une compétence
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$competence = new \LarpManager\Entities\Competence();
		
		// l'identifiant de la famille de competence peux avoir été passé en paramètre
		// pour initialiser le formulaire avec une valeur par défaut.
		// TODO : dans ce cas, il ne faut proposer que les niveaux pour lesquels une compétence
		// n'a pas été défini pour cette famille
		
		$competenceFamilyId = $request->get('competenceFamily');
		$levelIndex = $request->get('level');
		
		if ( $competenceFamilyId ) 
		{
			$competenceFamily = $app['orm.em']->find('\LarpManager\Entities\CompetenceFamily', $competenceFamilyId);
			if ( $competenceFamily )
			{
				$competence->setCompetenceFamily($competenceFamily);
			}
		}
		
		if ( $levelIndex )
		{
			$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Level');
			$level = $repo->findOneByIndex($levelIndex+1);
			if ( $level )
			{
				$competence->setLevel($level);
			}
		}
		
		$form = $app['form.factory']->createBuilder(new CompetenceForm(), $competence)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$competence = $form->getData();
			
			$files = $request->files->get($form->getName());
			
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
			
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('competence.family'),301);
				}
			
				$documentFilename = hash('md5',$competence->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$competence->setDocumentUrl($documentFilename);
			}
			
				
			$app['orm.em']->persist($competence);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'La compétence a été ajoutée.');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('competence.detail', array('competence'=> $competence->getId())));
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('competence.add'),301);
			}
		}
		
		return $app['twig']->render('admin/competence/add.twig', array(
				'form' => $form->createView(),
		));	
	}
	
	/**
	 * Detail d'une compétence
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$competence = $request->get('competence');
		
		return $app['twig']->render('admin/competence/detail.twig', array('competence' => $competence));
	}
	
	/**
	 * Met à jour une compétence
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$competence = $request->get('competence');
				
		$form = $app['form.factory']->createBuilder(new CompetenceForm(), $competence)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$competence = $form->getData();
			
			$files = $request->files->get($form->getName());
			
			// si un document est fourni, l'enregistré
			if ( $files['document'] != null )
			{
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
				
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('competence.family'),301);
				}
				
				$documentFilename = hash('md5',$competence->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$competence->setDocumentUrl($documentFilename);
			}
			
				
			if ($form->get('update')->isClicked())
			{	
				$app['orm.em']->persist($competence);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La compétence a été mise à jour.');
				
				return $app->redirect($app['url_generator']->generate('competence.detail', array('competence'=> $competence->getId())));
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($competence);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'La compétence a été supprimée.');
				
				return $app->redirect($app['url_generator']->generate('competence'));
			}
		
			
		}
		
		return $app['twig']->render('admin/competence/update.twig', array(
				'competence' => $competence,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Retire le document d'une competence
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Competence $competence
	 */
	public function removeDocumentAction(Request $request, Application $app, Competence $competence)
	{
		$competence->setDocumentUrl(null);
		
		$app['orm.em']->persist($competence);
		$app['orm.em']->flush();
		$app['session']->getFlashBag()->add('success', 'La compétence a été mise à jour.');
		
		return $app->redirect($app['url_generator']->generate('competence'));
	}
	
	/**
	 * Téléchargement du document lié à une compétence
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function getDocumentAction(Request $request, Application $app)
	{
		$document = $request->get('document');
		$competence = $request->get('competence');
		
		// on ne peux télécharger que les documents des compétences que l'on connait
		if  ( ! $app['security.authorization_checker']->isGranted('ROLE_REGLE') )
		{
			if ( $app['user']->getPersonnage() )
			{
				if ( ! $app['user']->getPersonnage()->getCompetences()->contains($competence) )
				{
					$app['session']->getFlashBag()->add('error', 'Vous n\'avez pas les droits necessaires');
				}
			}
		}
		
		$file = __DIR__.'/../../../private/doc/'.$document;
		
		$stream = function () use ($file) {
			readfile($file);
		};
		
		return $app->stream($stream, 200, array(
	        'Content-Type' => 'text/pdf',
	        'Content-length' => filesize($file),
	        'Content-Disposition' => 'attachment; filename="'.$competence->getLabel().'.pdf"' 
        ));
	}
}