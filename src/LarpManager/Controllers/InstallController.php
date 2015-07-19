<?php
namespace LarpManager\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SqlFormatter;

class InstallController
{
	/**
	 * @description Charge les tables necessaires à simpleuser dans la base de données
	 */
	private function loadUserTables($connection, $dir) 
	{
		$sql = file_get_contents($dir.'mysql.sql');
		$statement = $connection->prepare($sql);
		$statement->execute();
	}
	
	private function loadLarpManagerTables($connection, $dir)
	{
		$sql = file_get_contents($dir.'create_or_update.sql');
		$statement = $connection->prepare($sql);
		$statement->execute();
	}
	
	private function noUserInUserTable($app)
	{
		$usersCount = $app['user.manager']->findCount();
		
		return $usersCount == 0 ? true : false;
	}
	
	public function createOrUpdateAction(Request $request, Application $app)
	{
		//$app['security.access_rules'] dans le bootstrap definit deja ce comportement, ce check n'est la que
		//comme double securite
		if(!($app['security.authorization_checker']->isGranted('ROLE_ADMIN')))
		{
			return $app->redirect($app['url_generator']->generate('user.login'));
		}
		
		if ( $request->getMethod() === 'POST' )
		{
			$this->loadLarpManagerTables($app['orm.em']->getConnection(), $app['db_install_path']);
			return $app['twig']->render('install/installdone.twig');
		}
		return $app->redirect($app['url_generator']->generate('homepage'));
	}
	
	public function createUserAction(Request $request, Application $app)
	{
		if ( $request->getMethod() === 'POST' )
		{
			$this->loadUserTables($app['orm.em']->getConnection(), $app['db_user_install_path']);
			return $app->redirect($app['url_generator']->generate('install'));
		}
		return $app->redirect($app['url_generator']->generate('homepage'));
	}
	
	public function registerInstallUserAction(Request $request, Application $app)
	{
		$name = $request->get('name');
		$email = $request->get('email');
		$password = $request->get('password');
		
		$user = $app['user.manager']->createUser($email, $password, $name, array('ROLE_ADMIN'));
		$app['user.manager']->insert($user);
		
		return $app->redirect($app['url_generator']->generate('install'));
	}
	
	/**
	 * @description Affiche la page d'installation de LarpManager
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app) 
	{
		$schemaManager = $app['orm.em']->getConnection()->getSchemaManager();
		$is_to_create_or_update = false;
		$sql = "";
		//$error = true;
		//$error_description = "Unknown error.";
		if ($schemaManager->tablesExist(array('users')) == false) 
		{
			return $app['twig']->render('install/installsimpleuser.twig');
		}
		else if($this->noUserInUserTable($app))
		{
			return $app['twig']->render('install/installfirstuser.twig');
		}
		else if(!($app['security.authorization_checker']->isGranted('ROLE_ADMIN')))
		{
			return $app->redirect($app['url_generator']->generate('user.login'));
		}
		else if(file_exists($app['db_install_path'].'/create_or_update.sql')) //que si simple user est deja installe
		{
			//test droit admin
			$sql = file_get_contents($app['db_install_path'].'create_or_update.sql');
		}
		return $app['twig']->render('install/index.twig',array(
				'sql_content'=>SqlFormatter::format($sql)
		));
		
	}
}