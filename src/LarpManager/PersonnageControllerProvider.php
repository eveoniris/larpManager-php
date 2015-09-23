<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\PersonnageControllerProvider
 * 
 * @author kevin
 *
 */
class PersonnageControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les personnages
	 * Routes :
	 * 	- personnage.add
	 *  - personnage.detail
	 *  - personnage.competence.add
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 * @throws AccessDeniedException
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		/**
		 * Liste des personnages (orga)
		 */
		$controllers->match('/admin/list','LarpManager\Controllers\PersonnageController::adminListAction')
			->bind("personnage.admin.list")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Detail d'un personnage (orga)
		 */
		$controllers->match('/admin/{index}/detail','LarpManager\Controllers\PersonnageController::adminDetailAction')
			->bind("personnage.admin.detail")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
		
		/**
		 * Ajout d'un personnage (orga)
		 */
		$controllers->match('/admin/add','LarpManager\Controllers\PersonnageController::adminAddAction')
			->bind("personnage.admin.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
		/**
		 * Modification d'un personnage (orga)
		 */
		$controllers->match('/admin/{index}/update','LarpManager\Controllers\PersonnageController::adminUpdateAction')
			->bind("personnage.admin.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Création d'un nouveau personnage
		 */
		$controllers->match('/add','LarpManager\Controllers\PersonnageController::addAction')
			->bind("personnage.add")
			->method('GET|POST');
		
		/**
		 * Rechercher un joueur
		 */
		$controllers->match('/search','LarpManager\Controllers\PersonnageController::searchAction')
			->bind("personnage.search")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if ( !$app['security.authorization_checker']->isGranted('ROLE_ORGA') ) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Détail d'un personnage
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{index}/detail','LarpManager\Controllers\PersonnageController::detailAction')
			->assert('index', '\d+')
			->bind("personnage.detail")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('OWN_PERSONNAGE', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Détail d'un personnage
		 * Accessible uniquement aux orgas
		 */
		$controllers->match('/{index}/detail/orga','LarpManager\Controllers\PersonnageController::detailOrgaAction')
			->assert('index', '\d+')
			->bind("personnage.detail.orga")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if ( !$app['security.authorization_checker']->isGranted('ROLE_ORGA') ) {
					throw new AccessDeniedException();
				}
			});
		
		/**
		 * Ajout d'une compétence au personnage
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{index}/competence/add','LarpManager\Controllers\PersonnageController::addCompetenceAction')
			->assert('index', '\d+')
			->bind("personnage.competence.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('OWN_PERSONNAGE', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
			
		/**
		 * Choix d'une religion
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{index}/religion/add','LarpManager\Controllers\PersonnageController::addReligionAction')
			->assert('index', '\d+')
			->bind("personnage.religion.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('OWN_PERSONNAGE', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
					
		return $controllers;
	}
}
