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
	 * 
	 * Routes :
	 * 	- personnage.admin.list
	 *  - personnage.admin.detail
	 *  - personnage.admin.add
	 *  - personnage.admin.update
	 *  - personnage.detail
	 *  - personnage.update
	 *  - personnage.delete
	 *  - personnage.competence.add
	 *  - personnage.competence.remove
	 *  - personnage.religion.add
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 * @throws AccessDeniedException
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Vérifie que l'utilisateur dispose du role ORGA
		 */
		$mustBeOrga = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA')) {
				throw new AccessDeniedException();
			}
		};
				
		/**
		 * Vérifie que l'utilisateur posséde la ressource demandée
		 */
		$mustOwn = function(Request $request) use ($app) {
			$personnage = $request->get('personnage');
			if ( ! $app['security.authorization_checker']->isGranted('OWN_PERSONNAGE', $personnage)) {
				throw new AccessDeniedException();
			}
		};

		/**
		 * Liste des personnages (orga)
		 */
		$controllers->match('/admin/list','LarpManager\Controllers\PersonnageController::adminListAction')
			->bind("personnage.admin.list")
			->method('GET|POST')
			->before($mustBeOrga);
			
		/**
		 * Detail d'un personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/detail','LarpManager\Controllers\PersonnageController::adminDetailAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.detail")
			->method('GET')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Gestion des points d'expériences (orga)
		 */
		$controllers->match('/admin/{personnage}/xp','LarpManager\Controllers\PersonnageController::adminXpAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.xp")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Ajout d'un personnage (orga)
		 */
		$controllers->match('/admin/add','LarpManager\Controllers\PersonnageController::adminAddAction')
			->bind("personnage.admin.add")
			->method('GET|POST')
			->before($mustBeOrga);
			
		/**
		 * Modification d'un personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/update','LarpManager\Controllers\PersonnageController::adminUpdateAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.update")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
		
		/**
		 * Suppression d'un personnage (orga)
		 */
		$controllers->match('/admin/{personnage}/delete','LarpManager\Controllers\PersonnageController::adminDeleteAction')
			->assert('personnage', '\d+')
			->bind("personnage.admin.delete")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);

		/**
		 * Détail d'un personnage
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{personnage}/detail','LarpManager\Controllers\PersonnageController::detailAction')
			->assert('personnage', '\d+')
			->bind("personnage.detail")
			->method('GET')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustOwn);
		
		/**
		 * Export du personnage
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{personnage}/export','LarpManager\Controllers\PersonnageController::exportAction')
			->assert('personnage', '\d+')
			->bind("personnage.export")
			->method('GET')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustOwn);			
		
		/**
		 * Ajout d'une compétence au personnage
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{personnage}/competence/add','LarpManager\Controllers\PersonnageController::addCompetenceAction')
			->assert('personnage', '\d+')
			->bind("personnage.competence.add")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustOwn);
		
		/**
		 * Retire la dernière compétence acquise par un personnage
		 * Accessible uniquement aux orgas
		 */
		$controllers->match('/{personnage}/competence/remove','LarpManager\Controllers\PersonnageController::removeCompetenceAction')
			->assert('personnage', '\d+')
			->bind("personnage.competence.remove")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustBeOrga);
			
		/**
		 * Choix d'une religion
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{personnage}/religion/add','LarpManager\Controllers\PersonnageController::addReligionAction')
			->assert('personnage', '\d+')
			->bind("personnage.religion.add")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustOwn);
		
		/**
		 * Choix d'une origine
		 * Accessible uniquement au proprietaire du personnage, s'il n'a pas déjà choisi d'origine
		 */
		$controllers->match('/{personnage}/origin/add','LarpManager\Controllers\PersonnageController::updateOriginAction')
			->assert('personnage', '\d+')
			->bind("personnage.origin.add")
			->method('GET|POST')
			->convert('personnage', 'converter.personnage:convert')
			->before($mustOwn);
					
		return $controllers;
	}
}
