<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\AgeControllerProvider
 * 
 * @author kevin
 */
class MagieControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour la magie
	 *   
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/domaine','LarpManager\Controllers\MagieController::domaineListAction')
			->bind("magie.domaine.list")
			->method('GET');

		$controllers->match('/domaine/{domaine}','LarpManager\Controllers\MagieController::domaineDetailAction')
			->assert('domaine', '\d+')
			->bind("magie.domaine.detail")
			->convert('domaine', 'converter.domaine:convert')
			->method('GET');			
		
		$controllers->match('/domaine/add','LarpManager\Controllers\MagieController::domaineAddAction')
			->bind("magie.domaine.add")
			->method('GET|POST');
		
		$controllers->match('/domaine/{domaine}/update','LarpManager\Controllers\MagieController::domaineUpdateAction')
			->assert('domaine', '\d+')
			->bind("magie.domaine.update")
			->convert('domaine', 'converter.domaine:convert')
			->method('GET|POST');
		
		$controllers->match('/domaine/{domaine}/delete','LarpManager\Controllers\MagieController::domaineDeleteAction')
			->assert('domaine', '\d+')
			->bind("magie.domaine.delete")
			->convert('domaine', 'converter.domaine:convert')
			->method('GET|POST');
		
		$controllers->match('/sort','LarpManager\Controllers\MagieController::sortListAction')
			->bind("magie.sort.list")
			->method('GET');
		
		$controllers->match('/sort/{sort}','LarpManager\Controllers\MagieController::sortDetailAction')
			->assert('sort', '\d+')
			->bind("magie.sort.detail")
			->convert('sort', 'converter.sort:convert')
			->method('GET');
		
		$controllers->match('/sort/add','LarpManager\Controllers\MagieController::sortAddAction')
			->bind("magie.sort.add")
			->method('GET|POST');
		
		$controllers->match('/sort/{sort}/update','LarpManager\Controllers\MagieController::sortUpdateAction')
			->assert('sort', '\d+')
			->bind("magie.sort.update")
			->convert('sort', 'converter.sort:convert')
			->method('GET|POST');
		
		$controllers->match('/sort/{sort}/delete','LarpManager\Controllers\MagieController::sortDeleteAction')
			->assert('sort', '\d+')
			->bind("magie.sort.delete")
			->convert('sort', 'converter.sort:convert')
			->method('GET|POST');			
		
		/**
		 * Obtenir un document lié à un sortilège
		 */
		$controllers->match('/sort/{sort}/document/{document}','LarpManager\Controllers\MagieController::getSortDocumentAction')
			->bind("magie.sort.document")
			->convert('sort', 'converter.sort:convert')
			->method('GET');
		
		return $controllers;
	}
}