<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\StatistiqueControllerProvider
 *  
 * @author kevin
 */
class StatistiqueControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les stats
	 * Routes :
	 * 	- statistique
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
	
		/**
		 * Page d'accueil pour les statisitques
		 */
		$controllers->match('/','LarpManager\Controllers\StatistiqueController::indexAction')
			->bind("statistique")
			->method('GET');
	
		return $controllers;
	}	
}