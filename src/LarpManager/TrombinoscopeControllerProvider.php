<?php
namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\TrombinoscopeControllerProvider
 *
 * @author kevin
 */
class TrombinoscopeControllerProvider implements ControllerProviderInterface
{

	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Page d'accueil de l'interface d'administration
		 */		
		$controllers->match('/','LarpManager\Controllers\TrombinoscopeController::indexAction')
			->bind("trombinoscope")
			->method('GET');
		
		return $controllers;
	}
}