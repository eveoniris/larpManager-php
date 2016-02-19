<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\HomepageControllerProvider
 * 
 * @author kevin
 *
 */
class HomepageControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise des routes non lié à un objet en particulier 
	 * Routes :
	 * 	- homepage
	 * 	- world
	 *  - inscription
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/** Affichage de la page d'acceuil */
		$controllers->match('/','LarpManager\Controllers\HomepageController::indexAction')
					->method('GET')
					->bind('homepage');
		
		/** Affichage de la cartographie du monde de conan */
		$controllers->match('/world','LarpManager\Controllers\HomepageController::worldAction')
					->method('GET')
					->bind('world');

		/** Page de récapitulatif des liens pour discuter */
		$controllers->match('/discuter','LarpManager\Controllers\HomepageController::discuterAction')
					->method('GET')
					->bind('discuter');
		
		/** Page de récapitulatif des liens pour discuter */
		$controllers->match('/evenement','LarpManager\Controllers\HomepageController::evenementAction')
					->method('GET')
					->bind('evenement');					
					
		/** Traitement du formulaire d'inscription d'un joueur dans un groupe */
		$controllers->match('/inscription','LarpManager\Controllers\HomepageController::inscriptionAction')
					->method('POST')
					->bind('homepage.inscription');
		
		/** Formulaire d'inscription d'un joueur dans un gn */
		$controllers->match('/inscriptionGn','LarpManager\Controllers\HomepageController::inscriptionGnFormAction')
					->method('GET')
					->bind('inscriptionGn.form');
		
		/** Traitement du formulaire d'inscription d'un joueur dans un gn */
		$controllers->match('/inscriptionGn','LarpManager\Controllers\HomepageController::inscriptionGnAction')
					->method('POST')
					->bind('inscriptionGn');
				
		/** détail des mentions légales */
		$controllers->match('/legal','LarpManager\Controllers\HomepageController::legalAction')
					->method('GET')
					->bind('legal');
		
		return $controllers;
	}
}