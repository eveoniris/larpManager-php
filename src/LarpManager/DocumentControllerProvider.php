<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\DocumentControllerProvider
 * 
 * @author kevin
 *
 */
class DocumentControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les documents
	 * Routes :
	 * 	- document
	 * 	- document.add
	 *  - document.update
	 *  - document.detail
	 *  - document.delete
	 *  
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des documents
		 */
		$controllers->match('/','LarpManager\Controllers\DocumentController::indexAction')
			->bind("document")
			->method('GET');
			
		/**
		 * Imprimer la liste des documents
		 */
		$controllers->match('/','LarpManager\Controllers\DocumentController::printAction')
			->bind("document.print")
			->method('GET');
			
		/**
		 * Télécharger la liste des documents
		 */
		$controllers->match('/','LarpManager\Controllers\DocumentController::downloadAction')
			->bind("document.download")
			->method('GET');
		
		/**
		 * Ajoute un document
		 */
		$controllers->match('/add','LarpManager\Controllers\DocumentController::addAction')
			->bind("document.add")
			->method('GET|POST');

		/**
		 * Mise à jour d'un document
		 */
		$controllers->match('/{document}/update','LarpManager\Controllers\DocumentController::updateAction')
			->assert('document', '\d+')
			->convert('document', 'converter.document:convert')
			->bind("document.update")
			->method('GET|POST');

		/**
		 * Detail d'un document
		 */
		$controllers->match('/{document}','LarpManager\Controllers\DocumentController::detailAction')
			->assert('document', '\d+')
			->convert('document', 'converter.document:convert')
			->bind("document.detail")
			->method('GET');

		/**
		 * Supression d'un document
		 */
		$controllers->match('/{document}/delete','LarpManager\Controllers\DocumentController::deleteAction')
			->assert('document', '\d+')
			->convert('document', 'converter.document:convert')
			->bind("document.delete")
			->method('GET|POST');

		$controllers->match('/get/{document}', 'LarpManager\Controllers\DocumentController::getAction')
			->bind("document.get")
			->method('GET');
			
		return $controllers;
	}
}