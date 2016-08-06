<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\DebriefingControllerProvider
 * 
 * @author kevin
 *
 */
class DebriefingControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des debriefing
		 */
		$controllers->match('/','LarpManager\Controllers\DebriefingController::listAction')
			->bind("debriefing.list")
			->method('GET');

		/**
		 * Liste des debriefing
		 */
		$controllers->match('/print','LarpManager\Controllers\DebriefingController::printAction')
			->bind("debriefing.print")
			->method('GET');
			
		/**
		 * Liste des debriefing
		 */
		$controllers->match('/download','LarpManager\Controllers\DebriefingController::downloadAction')
			->bind("debriefing.download")
			->method('GET');
		
		/**
		 * Ajout d'un debriefing
		 */
		$controllers->match('/add','LarpManager\Controllers\DebriefingController::addAction')
			->bind("debriefing.add")
			->method('GET|POST');
		
		/**
		 * Mise à jour d'un debriefing
		 */
		$controllers->match('/{debriefing}/update','LarpManager\Controllers\DebriefingController::updateAction')
			->assert('debriefing', '\d+')
			->convert('debriefing', 'converter.debriefing:convert')
			->bind("debriefing.update")
			->method('GET|POST');
		
		/**
		 * Détail d'un debriefing
		 */
		$controllers->match('/{debriefing}','LarpManager\Controllers\DebriefingController::detailAction')
			->assert('debriefing', '\d+')
			->convert('debriefing', 'converter.debriefing:convert')
			->bind("debriefing.detail")
			->method('GET');
	
		/**
		 * Suppression d'un debriefing
		 */
		$controllers->match('/{debriefing}/delete','LarpManager\Controllers\DebriefingController::deleteAction')
			->assert('debriefing', '\d+')
			->convert('debriefing', 'converter.debriefing:convert')
			->bind("debriefing.delete")
			->method('GET|POST');
			
		return $controllers;
	}
}