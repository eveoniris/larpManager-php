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
use LarpManager\Form\LangueForm;
use LarpManager\Form\GroupeLangueForm;

/**
 * LarpManager\Controllers\LangueController
 *
 * @author kevin
 *
 */
class LangueController
{
		
	/**
	 * affiche la liste des langues
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$langues = $app['orm.em']->getRepository('\LarpManager\Entities\Langue')->findAllOrderedByLabel();
		$groupeLangues = $app['orm.em']->getRepository('\LarpManager\Entities\GroupeLangue')->findAllOrderedByLabel();
		return $app['twig']->render('langue/index.twig', array('langues' => $langues, 'groupeLangues' => $groupeLangues));
	}
	
	/**
	 * Detail d'une langue
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$langue = $app['orm.em']->find('\LarpManager\Entities\Langue',$id);
		
		return $app['twig']->render('langue/detail.twig', array('langue' => $langue));
	}
	
	/**
	 * Ajoute une langue
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$langue = new \LarpManager\Entities\Langue();
		
		$form = $app['form.factory']->createBuilder(new LangueForm(), $langue)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);

		// si l'utilisateur soumet une nouvelle langue
		if ( $form->isValid() )
		{
			$langue = $form->getData();
			$files = $request->files->get($form->getName());
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';

				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('langue.add'),301);
				}

				$documentFilename = hash('md5',$langue->getLabel().$filename . time()).'.'.$extension;

				$files['document']->move($path,$documentFilename);

				$langue->setDocumentUrl($documentFilename);
			}

			$app['orm.em']->persist($langue);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La langue a été ajoutée.');
				
			// l'utilisateur est redirigé soit vers la liste des langues, soit vers de nouveau
			// vers le formulaire d'ajout d'une langue
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('langue'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('langue.add'),301);
			}
		}
		
		return $app['twig']->render('langue/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modifie une langue. Si l'utilisateur clique sur "sauvegarder", la langue est sauvegardée et
	 * l'utilisateur est redirigé vers la liste des langues. 
	 * Si l'utilisateur clique sur "supprimer", la langue est supprimée et l'utilisateur est
	 * redirigé vers la liste des langues. 
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{	
		$id = $request->get('index');
		
		$langue = $app['orm.em']->find('\LarpManager\Entities\Langue',$id);
		
		$form = $app['form.factory']->createBuilder(new LangueForm(), $langue)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$langue = $form->getData();
		
			if ( $form->get('update')->isClicked())
			{
				$files = $request->files->get($form->getName());
				// Si un document est fourni, l'enregistrer
				if ( $files['document'] != null )
				{
					$path = __DIR__.'/../../../private/doc/';
					$filename = $files['document']->getClientOriginalName();
					$extension = 'pdf';

					if (!$extension || ! in_array($extension, array('pdf'))) {
						$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
						return $app->redirect($app['url_generator']->generate('langue.detail',array('index' => $id)),301);
					}
						
					$documentFilename = hash('md5',$langue->getLabel().$filename . time()).'.'.$extension;

					$files['document']->move($path,$documentFilename);
					$langue->setDocumentUrl($documentFilename);
				}
				$app['orm.em']->persist($langue);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La langue a été mise à jour.');

				return $app->redirect($app['url_generator']->generate('langue.detail',array('index' => $id)),301);
			}
			else if ( $form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($langue);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La langue a été supprimée.');
				return $app->redirect($app['url_generator']->generate('langue'),301);
			}
		}		

		return $app['twig']->render('langue/update.twig', array(
				'langue' => $langue,
				'form' => $form->createView(),
		));
	}

	/**
	 * Detail d'une langue
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailGroupAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupeLangue = $app['orm.em']->find('\LarpManager\Entities\GroupeLangue',$id);
		
		return $app['twig']->render('langue/detailGroup.twig', array('groupeLangue' => $groupeLangue));
	}
	
	/**
	 * Ajoute une langue
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addGroupAction(Request $request, Application $app)
	{
		$groupeLangue = new \LarpManager\Entities\GroupeLangue();
		
		$form = $app['form.factory']->createBuilder(new GroupeLangueForm(), $groupeLangue)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);

		// si l'utilisateur soumet une nouvelle langue
		if ( $form->isValid() )
		{
			$langue = $form->getData();
			$app['orm.em']->persist($groupeLangue);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le groupe de langue a été ajoutée.');
				
			// l'utilisateur est redirigé soit vers la liste des langues, soit vers de nouveau
			// vers le formulaire d'ajout d'une langue
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('langue'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('langue.addGroup'),301);
			}
		}
		
		return $app['twig']->render('langue/addGroup.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modifie une langue. Si l'utilisateur clique sur "sauvegarder", la langue est sauvegardée et
	 * l'utilisateur est redirigé vers la liste des langues. 
	 * Si l'utilisateur clique sur "supprimer", la langue est supprimée et l'utilisateur est
	 * redirigé vers la liste des langues. 
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateGroupAction(Request $request, Application $app)
	{	
		$id = $request->get('index');
		
		$groupeLangue = $app['orm.em']->find('\LarpManager\Entities\GroupeLangue',$id);
		
		$form = $app['form.factory']->createBuilder(new GroupeLangueForm(), $groupeLangue)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$langue = $form->getData();
		
			if ( $form->get('update')->isClicked())
			{
				$app['orm.em']->persist($groupeLangue);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le groupe de langue a été mise à jour.');
		
				return $app->redirect($app['url_generator']->generate('langue.detailGroup',array('index' => $id)),301);
			}
			else if ( $form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($langue);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le groupe de langue a été supprimée.');
				return $app->redirect($app['url_generator']->generate('langue'),301);
			}
		}		

		return $app['twig']->render('langue/updateGroup.twig', array(
				'groupeLangue' => $groupeLangue,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Obtenir le document lié a une langue
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDocumentAction(Request $request, Application $app)
	{
		$document = $request->get('document');
		$langue = $request->get('langue');
	
		$file = __DIR__.'/../../../private/doc/'.$document;
	
		$stream = function () use ($file) {
			readfile($file);
		};
	
		return $app->stream($stream, 200, array(
				'Content-Type' => 'text/pdf',
				'Content-length' => filesize($file),
				'Content-Disposition' => 'attachment; filename="'.$langue->getPrintLabel().'.pdf"'
		));
	}	
}