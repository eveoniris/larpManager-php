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

use JasonGrimes\Paginator;
use LarpManager\Form\DocumentFindForm;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

use LarpManager\Entities\Document;
use LarpManager\Form\DocumentForm;
use LarpManager\Form\DocumentDeleteForm;

/**
 * LarpManager\Controllers\DocumentController
 *
 * @author kevin
 *
 */
class DocumentController
{
	/**
	 * Liste des documents
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
        $order_by = $request->get('order_by', 'titre');
        $order_dir = $request->get('order_dir', 'ASC') === 'DESC' ? 'DESC' : 'ASC';
        $limit = (int) ($request->get('limit', 50));
        $page = (int) ($request->get('page', 1));
        $offset = (int) (($page - 1) * $limit);
        $criteria = [];
        $type= null;
        $value = null;

        $form = $app['form.factory']->createBuilder(new DocumentFindForm())->getForm();

        $form->handleRequest($request);

        if ( $form->isValid() )
        {
            $data = $form->getData();
            $type = $data['type'];
            $value = $data['value'];
        }

        $repo = $app['orm.em']->getRepository('\LarpManager\Entities\Document');
        $documents = $repo->findList(
            $type,
            $value,
            ['by' =>  $order_by, 'dir' => $order_dir],
            $limit,
            $offset
        );

        $numResults = $repo->findCount($criteria);

        $paginator = new Paginator($numResults, $limit, $page,
            $app['url_generator']->generate('document') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
        );

        return $app['twig']->render(
            'admin/document/index.twig',
            [
                'documents' => $documents,
                'paginator' => $paginator,
                'form' => $form->createView(),
            ]
        );
    }
	
	/**
	 * Imprimer la liste des documents
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function printAction(Request $request, Application $app)
	{
		$documents = $app['orm.em']->getRepository('\LarpManager\Entities\Document')->findAllOrderedByCode();
	
		return $app['twig']->render('admin/document/print.twig', array('documents' => $documents));
	}
	
	/**
	 * Télécharger la liste des documents
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function downloadAction(Request $request, Application $app)
	{
		$documents = $app['orm.em']->getRepository('\LarpManager\Entities\Document')->findAllOrderedByCode();
		
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=eveoniris_documents_".date("Ymd").".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
			
		$output = fopen("php://output", "w");
			
		// header
		fputcsv($output,
		array(
		'code',
		'titre',
		'impression',
		'description',
		'langues',
		'lieux',
		'groupes',
		'personnages',
		'createur',
		'date de création',
		'date de mise à jour'), ';');
			
		foreach ($documents as $document)
		{
			$line = array();
			$line[] = utf8_decode($document->getCode());
			$line[] = utf8_decode($document->getTitre());
			if ( $document->getImpression())
			{
				$line[] = utf8_decode("Imprimé");
			}
			else
			{
				$line[] = utf8_decode('Non imprimé');
			}
			$line[] = utf8_decode($document->getDescriptionRaw());
		
			$langues = '';
			foreach ( $document->getLangues() as $langue)
			{
				$langues .= utf8_decode($langue->getLabel()). ', ';
			}
			$line[] = $langues;
			
			$lieux = '';
			foreach ( $document->getLieus() as $lieu)
			{
				$lieux .= utf8_decode($lieu->getNom()). ', ';
			}
			$line[] = $lieux;
			
			$groupes = '';
			foreach ( $document->getGroupes() as $groupe)
			{
				$groupes .= utf8_decode($groupe->getNom()). ', ';
			}
			$line[] = $groupes;
			
			$personnages = '';
			foreach ( $document->getPersonnages() as $personnage)
			{
				$personnages .= utf8_decode($personnage->getNom()). ', ';
			}
			$line[] = $personnages;

			$line[] = $document->getUser();
			$line[] = $document->getCreationDate()->format('Y-m-d H:i:s');
			$line[] = $document->getUpdateDate()->format('Y-m-d H:i:s');
			
			fputcsv($output, $line, ';');
		}
			
		fclose($output);
		exit();
	}
	
	/**
	 * Téléchargement du fichier lié au document
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param unknown $document
	 */
	public function getAction(Request $request, Application $app, $document)
	{
		$filename = __DIR__.'/../../../private/documents/'.$document;
		return $app->sendFile($filename);
	}
	
	/**
	 * Ajouter un document
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{	
		$form = $app['form.factory']->createBuilder(new DocumentForm(), new Document())
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$document = $form->getData();
			$document->setUser($app['user']);
			
			$files = $request->files->get($form->getName());
				
			$path = __DIR__.'/../../../private/documents/';
			$filename = $files['document']->getClientOriginalName();
			$extension = $files['document']->guessExtension();
				
			if (!$extension || ! in_array($extension, array('pdf'))) {
				$app['session']->getFlashBag()->add('error','Désolé, votre fichier ne semble pas valide (vérifiez le format de votre fichier)');
				return $app->redirect($app['url_generator']->generate('document.add'),303);
			}
				
			$documentFilename = hash('md5',$app['user']->getUsername().$filename . time()).'.'.$extension;
			
				
			$files['document']->move($path, $documentFilename);
			
			$document->setDocumentUrl($documentFilename);
			
			$app['orm.em']->persist($document);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'Le document a été ajouté.');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('document'),303);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('document.add'),303);
			}
		}

		return $app['twig']->render('admin/document/add.twig', array(
				'form' => $form->createView()
		));
	}
	
	/**
	 * Détail d'un document
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app, Document $document)
	{
		return $app['twig']->render('admin/document/detail.twig', array('document' => $document));
	}
	
	/**
	 * Mise à jour d'un document
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app, Document $document)
	{		
		$form = $app['form.factory']->createBuilder(new DocumentForm(), $document)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$document = $form->getData();
			$document->setUpdateDate(new \Datetime('NOW'));
			
			$files = $request->files->get($form->getName());
			if ( $files['document'] )
			{
			
				$path = __DIR__.'/../../../private/documents/';
				$filename = $files['document']->getClientOriginalName();
				$extension = $files['document']->guessExtension();
				
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre fichier ne semble pas valide (vérifiez le format de votre fichier)');
					return $app->redirect($app['url_generator']->generate('document.add'),303);
				}
				
				$documentFilename = hash('md5',$app['user']->getUsername().$filename . time()).'.'.$extension;
					
				
				$files['document']->move($path, $documentFilename);
					
				$document->setDocumentUrl($documentFilename);
			}
				
			$app['orm.em']->persist($document);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le document a été modifié.');
			return $app->redirect($app['url_generator']->generate('document'),303);
		}
		
		return $app['twig']->render('admin/document/update.twig', array(
				'document' => $document,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Suppression d'un document
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function deleteAction(Request $request, Application $app, Document $document)
	{		
		$form = $app['form.factory']->createBuilder(new DocumentDeleteForm(), $document)
			->add('save','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$document = $form->getData();
			
			$app['orm.em']->remove($document);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le document a été supprimé.');
			return $app->redirect($app['url_generator']->generate('document'),303);
		}
		
		return $app['twig']->render('admin/document/delete.twig', array(
				'document' => $document,
				'form' => $form->createView(),
		));
	}
}