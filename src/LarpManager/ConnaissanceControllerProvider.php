<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * LarpManager\ConnaissanceControllerProvider
 * 
 * @author Kevin F.
 */
class ConnaissanceControllerProvider implements ControllerProviderInterface
{
	
	/**
	 * Initialise les routes pour les connaissances
	 * Routes :
     *  - connaissance.list
     *  - connaissance.detail
	 *  - connaissance.personnages
     *  - connaissance.add
     *  - connaissance.update
     *  - connaissance.delete
     *  - connaissance.document
	 * 
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$mustBeScenariste = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_REGLE')) {
				throw new AccessDeniedException();
			}
		};
		
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des connaissances
		 */
		$controllers->match('/','LarpManager\Controllers\ConnaissanceController::listAction')
			->bind("connaissance.list")
			->before($mustBeScenariste)
			->method('GET');
		
		/**
		 * Détail d'une connaissance
		 */
		$controllers->match('/{connaissance}','LarpManager\Controllers\ConnaissanceController::detailAction')
			->assert('connaissance', '\d+')
			->bind("connaissance.detail")
			->convert('connaissance', 'converter.connaissance:convert')
			->before($mustBeScenariste)
			->method('GET');

		/**
		 * Obtenir les personnages possédant cette connaissance
		 */
		$controllers->match('/{connaissance}/personnages','LarpManager\Controllers\ConnaissanceController::personnagesAction')
			->assert('connaissance', '\d+')
			->bind("connaissance.personnages")
			->convert('connaissance', 'converter.connaissance:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
			
		/**
		 * Ajouter une connaissance
		 */
		$controllers->match('/add','LarpManager\Controllers\ConnaissanceController::addAction')
			->bind("connaissance.add")
			->before($mustBeScenariste)
			->method('GET|POST');
		
		/**
		 * Modifier une connaissance
		 */
		$controllers->match('/{connaissance}/update','LarpManager\Controllers\ConnaissanceController::updateAction')
			->assert('connaissance', '\d+')
			->bind("connaissance.update")
			->convert('connaissance', 'converter.connaissance:convert')
			->before($mustBeScenariste)
			->method('GET|POST');
		
		/**
		 * Supprimer une connaissance
		 */
		$controllers->match('/{connaissance}/delete','LarpManager\Controllers\ConnaissanceController::deleteAction')
			->assert('connaissance', '\d+')
			->bind("connaissance.delete")
			->convert('connaissance', 'converter.connaissance:convert')
			->before($mustBeScenariste)
			->method('GET|POST');			
		
		/**
		 * Obtenir un document lié à une connaissance
		 */
		$controllers->match('/{connaissance}/document/{document}','LarpManager\Controllers\ConnaissanceController::getDocumentAction')
			->bind("connaissance.document")
			->convert('connaissance', 'converter.connaissance:convert')
			->before($mustBeScenariste)
			->method('GET');

		return $controllers;
	}
}