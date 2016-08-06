<?php
namespace LarpManager\Controllers;

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
		$documents = $app['orm.em']->getRepository('\LarpManager\Entities\Document')->findAllOrderedByCode();
		
		return $app['twig']->render('admin/document/index.twig', array('documents' => $documents));
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
	 * @param Request $request
	 * @param Application $app
	 */
	public function downloadAction(Request $request, Application $app)
	{
		$documents = $app['orm.em']->getRepository('\LarpManager\Entities\Document')->findAllOrderedByCode();
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
				return $app->redirect($app['url_generator']->generate('document.add'),301);
			}
				
			$documentFilename = hash('md5',$app['user']->getUsername().$filename . time()).'.'.$extension;
			
				
			$files['document']->move($path, $documentFilename);
			
			$document->setDocumentUrl($documentFilename);
			
			$app['orm.em']->persist($document);
			$app['orm.em']->flush($document);
				
			$app['session']->getFlashBag()->add('success', 'Le document a été ajouté.');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('document'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('document.add'),301);
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
		$document = $request->get('document');
		
		$form = $app['form.factory']->createBuilder(new DocumentForm(), $document)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
	}
	
	/**
	 * Suppression d'un document
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function deleteAction(Request $request, Application $app, Document $document)
	{
		$document = $request->get('document');
	}
}