<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\PersonnageSecondaireControllerProvider
 * 
 * @author kevin
 *
 */
class PersonnageSecondaireControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/**
		 * Vérifie que l'utilisateur dispose du role ADMIN
		 * @var unknown $mustBeAdmin
		 */
		$mustBeAdmin = function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
				throw new AccessDeniedException();
			}
		};
		
		/** Liste des archétypes de personnage secondaire */
		$controllers->match('/','LarpManager\Controllers\PersonnageSecondaireController::indexAction')
			->bind("personnageSecondaire")
			->before($mustBeAdmin)
			->method('GET');

		/** Formulaire d'ajout d'un archétype de personnage secondaire */
		$controllers->match('/add','LarpManager\Controllers\PersonnageSecondaireController::addAction')
			->bind("personnageSecondaire.add")
			->before($mustBeAdmin)
			->method('GET|POST');
		
		/** Formulaire de modification d'un archétype de personnage secondaire */
		$controllers->match('/{personnageSecondaire}/update','LarpManager\Controllers\PersonnageSecondaireController::updateAction')
			->assert('personnageSecondaire', '\d+')
			->bind("personnageSecondaire.update")
			->method('GET|POST')
			->before($mustBeAdmin)
			->convert('personnageSecondaire', 'converter.personnageSecondaire:convert');
		
		/** Formulaire de supression d'un archétype de personnage secondaire */
		$controllers->match('/{personnageSecondaire}/delete','LarpManager\Controllers\PersonnageSecondaireController::deleteAction')
			->assert('personnageSecondaire', '\d+')
			->bind("personnageSecondaire.delete")
			->method('GET|POST')
			->before($mustBeAdmin)
			->convert('personnageSecondaire', 'converter.personnageSecondaire:convert');
		
		/** Formulaire de choix d'un personnage secondaire */
		$controllers->match('/choice','LarpManager\Controllers\PersonnageSecondaireController::choiceAction')
			->method('GET|POST')
			->bind('personnageSecondaire.choice');
		
		/** information sur les archétypes de personnage secondaire */
		$controllers->match('/list','LarpManager\Controllers\PersonnageSecondaireController::listAction')
			->method('GET')
			->bind('personnageSecondaire.list');
			
		return $controllers;
	}
}