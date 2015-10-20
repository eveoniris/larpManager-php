<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\BackgroundControllerProvider
 * 
 * @author kevin
 *
 */
class BackgroundControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour le background
	 * 
	 * @param Application $app
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste tous les background
		 */
		$controllers->match('/list','LarpManager\Controllers\BackgroundController::listAction')
			->bind("background.list")
			->method('GET|POST');
			
		/**
		 * Ajoute un background
		 */
		$controllers->match('/add','LarpManager\Controllers\BackgroundController::addAction')
			->bind("background.add")
			->method('GET|POST');
			
		/**
		 * Ajoute un background
		 */
		$controllers->match('/{background}/update','LarpManager\Controllers\BackgroundController::updateAction')
			->bind("background.update")
			->convert('background', 'converter.background:convert')
			->method('GET|POST');

		/**
		 * Détail d'un background
		 */
		$controllers->match('/{background}/detail','LarpManager\Controllers\BackgroundController::detailAction')
			->bind("background")
			->convert('background', 'converter.background:convert')
			->method('GET');
			
		return $controllers;
	}
}