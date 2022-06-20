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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\Query;
use Silex\Application;
use LarpManager\Form\LangueForm;
use LarpManager\Form\GroupeLangueForm;
use Symfony\Component\Form\Form;
use LarpManager\Entities\Langue;

/**
 * LarpManager\Controllers\LangueController
 *
 * @author kevin
 *
 */
class LangueController
{
	const DOC_PATH = __DIR__.'/../../../private/doc/';
		
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
			if (self::handleDocument($request, $app, $form, $langue))
			{
				$app['orm.em']->persist($langue);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'La langue a été ajoutée.');
					
				// l'utilisateur est redirigé soit vers la liste des langues, soit vers de nouveau
				// vers le formulaire d'ajout d'une langue
				if ( $form->get('save')->isClicked())
				{
					return $app->redirect($app['url_generator']->generate('langue'),303);
				}
				else if ( $form->get('save_continue')->isClicked())
				{
					return $app->redirect($app['url_generator']->generate('langue.add'),303);
				}
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
		$hasDocumentUrl = !empty($langue->getDocumentUrl());
		$canBeDeleted = $langue->getPersonnageLangues()->isEmpty()
			&& $langue->getTerritoires()->isEmpty()
			&& $langue->getDocuments()->isEmpty();
			
		$deleteTooltip = $canBeDeleted ? '' : 'Cette langue est référencée par '.$langue->getPersonnageLangues()->count().' personnages, '.$langue->getTerritoires()->count().' territoires et '.$langue->getDocuments()->count().' documents et ne peut pas être supprimée';
		
		$formBuilder = $app['form.factory']->createBuilder(new LangueForm(), $langue, ['hasDocumentUrl'=>$hasDocumentUrl])
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer", 'disabled' => !$canBeDeleted, 'attr' => ['title' => $deleteTooltip]));
		
		$form = $formBuilder->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$langue = $form->getData();
		
			if ( $form->get('update')->isClicked())
			{
				if (self::handleDocument($request, $app, $form, $langue))
				{		
					$app['orm.em']->persist($langue);
					$app['orm.em']->flush();
					$app['session']->getFlashBag()->add('success', 'La langue a été mise à jour.');

					return $app->redirect($app['url_generator']->generate('langue.detail',array('index' => $id)),303);
				}
			}
			else if ( $form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($langue);
				$app['orm.em']->flush();				
				// delete language document if it exists
				self::tryDeleteDocument($langue);
				$app['session']->getFlashBag()->add('success', 'La langue a été supprimée.');
				return $app->redirect($app['url_generator']->generate('langue'),303);
			}
		}		

		return $app['twig']->render('langue/update.twig', array(
				'langue' => $langue,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Gère le document uploadé et renvoie true si il est valide, false sinon
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Form $form
	 * @param Langue $langue
	 * @return bool
	 */
	private function handleDocument(Request $request, Application $app, Form $form, Langue $langue) : bool
	{
		$files = $request->files->get($form->getName());
		$documentFile = $files['document'];
		// Si un document est fourni, l'enregistrer
		if ($documentFile != null )
		{
			$filename = $documentFile->getClientOriginalName();
			$extension = pathinfo($filename, PATHINFO_EXTENSION);

			if (!$extension || ! in_array($extension, array('pdf'))) {
				$app['session']->getFlashBag()->add('error','Désolé, votre document n\'est pas valide. Vérifiez le format de votre document ('.$extension.'), seuls les .pdf sont acceptés.');
				return false;
			}
				
			$documentFilename = hash('md5',$langue->getLabel().$filename . time()).'.'.$extension;

			$documentFile->move(self::DOC_PATH,$documentFilename);
			
			// delete previous language document if it exists
			self::tryDeleteDocument($langue);
			
			$langue->setDocumentUrl($documentFilename);
		}
		return true;
	}
	
	/**
	 * Supprime le document spécifié, en cas d'erreur, ne fait rien pour le moment
	 *
	 * @param Langue $langue
	 */
	private function tryDeleteDocument(string $langue)
	{
		try 
		{
            if (!empty($langue->getDocumentUrl()))
			{
				$docFilePath = self::DOC_PATH.$langue->getDocumentUrl();
				unlink($docFilePath);
			}
		} 
		catch (FileException $e) 
		{
			// for now, simply ignore
		}
	}

	/**
	 * Detail d'un groupe de langue
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
	 * Ajoute un groupe de langue
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
			$groupeLangue = $form->getData();
			$app['orm.em']->persist($groupeLangue);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le groupe de langue a été ajouté.');
				
			// l'utilisateur est redirigé soit vers la liste des langues, soit vers de nouveau
			// vers le formulaire d'ajout d'une langue
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('langue'),303);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('langue.addGroup'),303);
			}
		}
		
		return $app['twig']->render('langue/addGroup.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modifie un groupe de langue. Si l'utilisateur clique sur "sauvegarder", le groupe de langue est sauvegardé.
	 * Si l'utilisateur clique sur "supprimer", le groupe de langue est supprimé et l'utilisateur est
	 * redirigé vers la liste des langues. 
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateGroupAction(Request $request, Application $app)
	{	
		$id = $request->get('index');
		
		$groupeLangue = $app['orm.em']->find('\LarpManager\Entities\GroupeLangue',$id);
		
		$canBeDeleted = $groupeLangue->getLangues()->isEmpty();
		$deleteTooltip = $canBeDeleted ? '' : 'Ce groupe est référencé par '.$groupeLangue->getLangues()->count().' langues et ne peut pas être supprimé';
			
		$formBuilder = $app['form.factory']->createBuilder(new GroupeLangueForm(), $groupeLangue)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer", 'disabled' => !$canBeDeleted, 'attr' => ['title' => $deleteTooltip]));
			
		$form =	$formBuilder->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$groupeLangue = $form->getData();
		
			if ( $form->get('update')->isClicked())
			{
				$app['orm.em']->persist($groupeLangue);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le groupe de langue a été mis à jour.');
		
				return $app->redirect($app['url_generator']->generate('langue.detailGroup',array('index' => $id)),303);
			}
			else if ( $form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($groupeLangue);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le groupe de langue a été supprimé.');
				return $app->redirect($app['url_generator']->generate('langue'),303);
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
		$langue = $request->get('langue');
		$document = $langue->getDocumentUrl();
		$file = self::DOC_PATH.$document;
	
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