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
	 *  - personnage.competence.add
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
		 * vérifie que la ressource demandée existe
		 * La ressource est stockée dans la variable $request pour éviter son rechargement
		 * lors de chaque contrôle.
		 * De ce fait, cette fonction *doit* être appellé *avant* la fonction $mustOwn
		 */
		$mustExist = function(Request $request) use ($app) {
			$id = $request->get('index');
			$personnage = $app['orm.em']->find('\LarpManager\Entities\Personnage',$id);
			if ( ! $personnage )
			{
				$app['session']->getFlashBag()->add('error', 'Le personnage n\'a pas été trouvé.');
				return $app->redirect($app['url_generator']->generate('homepage'));
			}
			$request->attributes->set('personnage',$personnage);
		};
		
		/**
		 * Vérifie que l'utilisateur posséde la ressource demandée
		 * Cette fonction *doit* être appellé *après* la fonction $mustExist
		 */
		$mustOwn = function(Request $request) use ($app) {
			$personnage = $request->attributes->get('personnage');
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
		$controllers->match('/admin/{index}/detail','LarpManager\Controllers\PersonnageController::adminDetailAction')
			->bind("personnage.admin.detail")
			->method('GET')
			->before($mustBeOrga)
			->before($mustExist, Application::LATE_EVENT);
			
		/**
		 * Gestion des points d'expériences (orga)
		 */
		$controllers->match('/admin/{index}/xp','LarpManager\Controllers\PersonnageController::adminXpAction')
			->bind("personnage.admin.xp")
			->method('GET|POST')
			->before($mustBeOrga)
			->before($mustExist, Application::LATE_EVENT);
		
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
		$controllers->match('/admin/{index}/update','LarpManager\Controllers\PersonnageController::adminUpdateAction')
			->bind("personnage.admin.update")
			->method('GET|POST')
			->before($mustBeOrga)
			->before($mustExist, Application::LATE_EVENT);
		
		/**
		 * Détail d'un personnage
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{index}/detail','LarpManager\Controllers\PersonnageController::detailAction')
			->assert('index', '\d+')
			->bind("personnage.detail")
			->method('GET')
			->before($mustExist, Application::EARLY_EVENT)
			->before($mustOwn, Application::LATE_EVENT);
		
		/**
		 * Export du personnage
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{index}/export','LarpManager\Controllers\PersonnageController::exportAction')
			->assert('index', '\d+')
			->bind("personnage.export")
			->method('GET')
			->before($mustExist, Application::EARLY_EVENT)
			->before($mustOwn, Application::LATE_EVENT);			
		
		/**
		 * Ajout d'une compétence au personnage
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{index}/competence/add','LarpManager\Controllers\PersonnageController::addCompetenceAction')
			->assert('index', '\d+')
			->bind("personnage.competence.add")
			->method('GET|POST')
			->before($mustExist, Application::EARLY_EVENT)
			->before($mustOwn, Application::LATE_EVENT);
			
		/**
		 * Choix d'une religion
		 * Accessible uniquement au proprietaire du personnage
		 */
		$controllers->match('/{index}/religion/add','LarpManager\Controllers\PersonnageController::addReligionAction')
			->assert('index', '\d+')
			->bind("personnage.religion.add")
			->method('GET|POST')
			->before($mustExist, Application::EARLY_EVENT)
			->before($mustOwn, Application::LATE_EVENT);
					
		return $controllers;
	}
}
