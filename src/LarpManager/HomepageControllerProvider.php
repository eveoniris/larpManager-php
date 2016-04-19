<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


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
		
		/**
		 * Vérifie que l'utilisateur dispose du role SCENARISTE
		 */
		$mustBeScenariste = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
				throw new AccessDeniedException();
			}
		};
		
		$mustBeUser = function(Request $request) use ($app) {
			if ( ! $app['user'] ) {
				throw new AccessDeniedException();
			}
		};
		
		
		/** Affichage de la page d'acceuil */
		$controllers->match('/','LarpManager\Controllers\HomepageController::indexAction')
					->method('GET')
					->bind('homepage');
					
		/** Affichage de la page trombine */
		$controllers->match('/trombine','LarpManager\Controllers\HomepageController::trombineAction')
					->method('GET|POST')
					->bind('trombine')
					->before($mustBeUser);
			
		/** Récupére une trombine */
		$controllers->match('/trombine/{trombine}','LarpManager\Controllers\HomepageController::getTrombineAction')
					->method('GET')
					->bind('trombine.get')
					->before($mustBeUser);
		
		/** Page de gestion des règles */
		$controllers->match('/rules/admin','LarpManager\Controllers\HomepageController::rulesAdminAction')
					->method('GET|POST')
					->bind('rules.admin')
					->before($mustBeScenariste);
		
		/** Page de gestion des règles */
		$controllers->match('/rules/admin/{rule}/delete','LarpManager\Controllers\HomepageController::rulesAdminDeleteAction')
					->method('GET')
					->bind('rules.admin.delete')
					->before($mustBeScenariste);		
		
		/** Page de téléchargement des règles */
		$controllers->match('/rules','LarpManager\Controllers\HomepageController::rulesAction')
					->method('GET')
					->bind('rules')
					->before($mustBeUser);
		
		/** Récupére un fichier de règle */
		$controllers->match('/rules/{rule}','LarpManager\Controllers\HomepageController::getRuleAction')
					->method('GET')
					->bind('rules.get')
					->before($mustBeUser);
		
		/** Affichage de la cartographie du monde de conan */
		$controllers->match('/world','LarpManager\Controllers\HomepageController::worldAction')
					->method('GET')
					->bind('world');
		
		/** Affichage de la cartographie du monde de conan */
		$controllers->match('/world/countries.json','LarpManager\Controllers\HomepageController::countriesAction')
					->method('GET')
					->bind('world.countries.json');
		
		/** Affichage de la cartographie du monde de conan */
		$controllers->match('/world/regions.json','LarpManager\Controllers\HomepageController::regionsAction')
					->method('GET')
					->bind('world.regions.json');					
					
		/** Affichage de la cartographie du monde de conan */
		$controllers->match('/world/fiefs.json','LarpManager\Controllers\HomepageController::fiefsAction')
					->method('GET')
					->bind('world.fiefs.json');					
		
		/** Mise a jour d'une geographie */
		$controllers->match('/world/countries/{territoire}/update','LarpManager\Controllers\HomepageController::updateCountryGeomAction')
					->method('POST')
					->convert('territoire', 'converter.territoire:convert')
					->bind('world.country.update')
					->before($mustBeScenariste);				

		/** Page de récapitulatif des liens pour discuter */
		$controllers->match('/discuter','LarpManager\Controllers\HomepageController::discuterAction')
					->method('GET')
					->bind('discuter')
					->before($mustBeUser);
		
		/** Page de récapitulatif des liens pour discuter */
		$controllers->match('/evenement','LarpManager\Controllers\HomepageController::evenementAction')
					->method('GET')
					->bind('evenement')
					->before($mustBeUser);					
					
		/** Traitement du formulaire d'inscription d'un joueur dans un groupe */
		$controllers->match('/inscription','LarpManager\Controllers\HomepageController::inscriptionAction')
					->method('POST')
					->bind('homepage.inscription')
					->before($mustBeUser);
		
		/** Formulaire d'inscription d'un joueur dans un gn */
		$controllers->match('/inscriptionGn','LarpManager\Controllers\HomepageController::inscriptionGnFormAction')
					->method('GET')
					->bind('inscriptionGn.form')
					->before($mustBeUser);
		
		/** Traitement du formulaire d'inscription d'un joueur dans un gn */
		$controllers->match('/inscriptionGn','LarpManager\Controllers\HomepageController::inscriptionGnAction')
					->method('POST')
					->bind('inscriptionGn')
					->before($mustBeUser);
				
		/** détail des mentions légales */
		$controllers->match('/legal','LarpManager\Controllers\HomepageController::legalAction')
					->method('GET')
					->bind('legal');
		
		return $controllers;
	}
}