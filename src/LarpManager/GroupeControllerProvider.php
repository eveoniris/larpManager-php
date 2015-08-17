<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GroupeControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\GroupeController::indexAction')
			->bind("groupe")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/{index}','LarpManager\Controllers\GroupeController::detailAction')
			->assert('index', '\d+')
			->bind("groupe.detail")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/{index}/gestion','LarpManager\Controllers\GroupeController::gestionAction')
			->assert('index', '\d+')
			->bind("groupe.gestion")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('GROUPE_RESPONSABLE', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
			
		$controllers->match('/{index}/joueur','LarpManager\Controllers\GroupeController::joueurAction')
			->assert('index', '\d+')
			->bind("groupe.joueur")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('GROUPE_MEMBER', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
			
		$controllers->match('/{index}/personnage/add','LarpManager\Controllers\GroupeController::personnageAddAction')
			->assert('index', '\d+')
			->bind("groupe.personnage.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('GROUPE_MEMBER', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/add','LarpManager\Controllers\GroupeController::addAction')
			->bind("groupe.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/{index}/update','LarpManager\Controllers\GroupeController::updateAction')
			->assert('index', '\d+')
			->bind("groupe.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/{index}/export','LarpManager\Controllers\GroupeController::detailExportAction')
			->assert('index', '\d+')
			->bind("groupe.detail.export")
			->method('GET')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});
		
		$controllers->match('/export','LarpManager\Controllers\GroupeController::exportAction')
			->bind("groupe.export")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
					throw new AccessDeniedException();
				}
			});

		return $controllers;
	}
}
