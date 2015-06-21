<?php

namespace LarpManager\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InstallController
{
	/**
	 * @description Charge les tables necessaires à simpleuser dans la base de données
	 */
	private function loadUserTables($connection) 
	{
		$sql = file_get_contents('../vendor/jasongrimes/silex-simpleuser/sql/mysql.sql');
		$statement = $connection->prepare($sql);
		$statement->execute();
	}
	
	/**
	 * @description Affiche la page d'installation de LarpManager
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app) 
	{
		if ( $request->getMethod() === 'POST' ) {
			$this->loadUserTables($app['orm.em']->getConnection());
			return $app->redirect($app['url_generator']->generate('homepage'));
		}
		else {
			return $app['twig']->render('install/index.twig');
		}
		
		
	}
}