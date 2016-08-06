<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\TokenControllerProvider
 * 
 * @author kevin
 *
 */
class TokenControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Liste des token
		 */
		$controllers->match('/','LarpManager\Controllers\TokenController::listAction')
			->bind("token.list")
			->method('GET');

		/**
		 * Liste des token
		 */
		$controllers->match('/print','LarpManager\Controllers\TokenController::printAction')
			->bind("token.print")
			->method('GET');
			
		/**
		 * Liste des token
		 */
		$controllers->match('/download','LarpManager\Controllers\TokenController::downloadAction')
			->bind("token.download")
			->method('GET');
		
		/**
		 * Ajout d'un token
		 */
		$controllers->match('/add','LarpManager\Controllers\TokenController::addAction')
			->bind("token.add")
			->method('GET|POST');
		
		/**
		 * Mise à jour d'un token
		 */
		$controllers->match('/{token}/update','LarpManager\Controllers\TokenController::updateAction')
			->assert('token', '\d+')
			->convert('token', 'converter.token:convert')
			->bind("token.update")
			->method('GET|POST');
		
		/**
		 * Détail d'un token
		 */
		$controllers->match('/{token}','LarpManager\Controllers\TokenController::detailAction')
			->assert('token', '\d+')
			->convert('token', 'converter.token:convert')
			->bind("token.detail")
			->method('GET');
	
		/**
		 * Suppression d'un token
		 */
		$controllers->match('/{token}/delete','LarpManager\Controllers\TokenController::deleteAction')
			->assert('token', '\d+')
			->convert('token', 'converter.token:convert')
			->bind("token.delete")
			->method('GET|POST');
			
		return $controllers;
	}
}