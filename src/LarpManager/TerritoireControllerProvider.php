<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\TerritoireControllerProvider
 * 
 * @author kevin
 *
 */
class TerritoireControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les territoires
	 * Routes :
	 * 	- territoire.admin.list
	 * 	- territoire.admin.detail
	 *  - territoire.admin.add
	 *  - territoire.admin.update
	 *  - territoire.admin.update.blason
	 *  - territoire.admin.updateStrategie
	 *  - territoire.admin.delete
	 *  - territoire.admin.topic.add
	 *  - territoire.admin.topic.delete
	 *  - territoire.admin.event.add
	 *  - territoire.admin.event.update
	 *  - territoire.admin.event.delete
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
		
		/**
		 * Vérifie que l'utilisateur est membre du groupe controllant le territoire
		 */
		$mustBeOnGroupe = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('TERRITOIRE_MEMBER',  $request->get('territoire'))) {
				throw new AccessDeniedException();
			}
		};
		
		/**
		 * Vérifie que l'utilisateur fait partie du groupe controlant ce territoire
		 */
		
		$controllers->match('/{territoire}/joueur','LarpManager\Controllers\TerritoireController::detailJoueurAction')
			->assert('territoire', '\d+')
			->bind("territoire.detail")
			->method('GET')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeOnGroupe);
		
		$controllers->match('/list','LarpManager\Controllers\TerritoireController::listAction')
			->bind("territoire.admin.list")
			->method('GET')
			->before($mustBeScenariste);
		
		$controllers->match('/add','LarpManager\Controllers\TerritoireController::addAction')
			->bind("territoire.admin.add")
			->method('GET|POST')
			->before($mustBeScenariste);
		
		$controllers->match('/{territoire}/update','LarpManager\Controllers\TerritoireController::updateAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.update")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);
		
		$controllers->match('/{territoire}/construction/add','LarpManager\Controllers\TerritoireController::constructionAddAction')
			->assert('territoire', '\d+')
			->bind("territoire.construction.add")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);
		
		$controllers->match('/{territoire}/construction/{construction}/remove','LarpManager\Controllers\TerritoireController::constructionRemoveAction')
			->assert('territoire', '\d+')
			->assert('construction', '\d+')
			->bind("territoire.construction.remove")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->convert('construction', 'converter.construction:convert')
			->before($mustBeScenariste);
		
		$controllers->match('/{territoire}/ingredients/update','LarpManager\Controllers\TerritoireController::updateIngredientsAction')
			->assert('territoire', '\d+')
			->bind("territoire.ingredients.update")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);
		
		$controllers->match('/{territoire}/blason/update','LarpManager\Controllers\TerritoireController::updateBlasonAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.update.blason")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);
		
		$controllers->match('/{territoire}/updateStrategie','LarpManager\Controllers\TerritoireController::updateStrategieAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.updateStrategie")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);		
		
		$controllers->match('/{territoire}/delete','LarpManager\Controllers\TerritoireController::deleteAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.delete")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);			

		$controllers->match('/{territoire}/topic/add','LarpManager\Controllers\TerritoireController::addTopicAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.topic.add")
			->method('GET')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);			
		
		$controllers->match('/{territoire}/topic/delete','LarpManager\Controllers\TerritoireController::deleteTopicAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.topic.delete")
			->method('GET')
			->convert('territoire', 'converter.territoire:convert')
			->before($mustBeScenariste);	
			
		$controllers->match('/{territoire}','LarpManager\Controllers\TerritoireController::detailAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.detail")
			->convert('territoire', 'converter.territoire:convert')
			->method('GET')
			->before($mustBeScenariste);
			
		$controllers->match('/{territoire}/event/add','LarpManager\Controllers\TerritoireController::addEventAction')
			->assert('territoire', '\d+')
			->bind("territoire.admin.event.add")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->convert('event', 'converter.event:convert')
			->before($mustBeScenariste);
		
		$controllers->match('/{territoire}/event/{event}/delete','LarpManager\Controllers\TerritoireController::deleteEventAction')
			->assert('territoire', '\d+')
			->assert('event', '\d+')
			->bind("territoire.admin.event.delete")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->convert('event', 'converter.event:convert')
			->before($mustBeScenariste);
		
		$controllers->match('/{territoire}/event/{event}/update','LarpManager\Controllers\TerritoireController::updateEventAction')
			->assert('territoire', '\d+')
			->assert('event', '\d+')
			->bind("territoire.admin.event.update")
			->method('GET|POST')
			->convert('territoire', 'converter.territoire:convert')
			->convert('event', 'converter.event:convert')
			->before($mustBeScenariste);			
		
		return $controllers;
	}
}