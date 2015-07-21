<?php
namespace LarpManager\Controllers;

use Silex\Application;
use Silex\Provider\SecurityServiceProvider;
use SimpleUser\UserServiceProvider;
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
	
	/**
	 * Fin de l'installation
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function doneAction(Request $request, Application $app) {
		return $app['twig']->render('install/installdone.twig');
	}
	
	
	/**
	 * Création de l'utilisateur admin
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function createUserAction(Request $request, Application $app)
	{
		// valeur par défaut lorsque le formulaire est chargé pour la premiere fois
		$defaultData = array();
	
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder('form', $defaultData)
		->add('email','email')
		->add('password','password')
		->add('create','submit')
		->getForm();
		
		$form->handleRequest($request);
		
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$data = $form->getData();
			
			$name = 'admin';
			$email = $data['email'];
			$password = $data['password'];
	
			// ajoute les services necessaire pour créer l'utilisateur
			$app->register(new SecurityServiceProvider());
			$userServiceProvider = new UserServiceProvider();
			$app->register($userServiceProvider);
			
			$user = $app['user.manager']->createUser($email, $password, $name, array('ROLE_ADMIN'));
			$app['user.manager']->insert($user);
			
			// supprimer le fichier de cache pour lancer larpmanager en mode normal		
				
			return $app->redirect($app['url_generator']->generate('install_done'));
		}
		
		return $app['twig']->render('install/installfirstuser.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Affiche la page d'installation de LarpManager
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app) 
	{
		// valeur par défaut lorsque le formulaire est chargé pour la premiere fois
		$defaultData = array();
		
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder('form', $defaultData)
			->add('create','submit')
			->getForm();
		
		$form->handleRequest($request);
		
		// si la requête est valide
		if ( $form->isValid() )
		{
			$tool = new \Doctrine\ORM\Tools\SchemaTool($app['orm.em']);
		
			// l'opération peut prendre du temps, il faut donc régler le temps maximum d'execution
			set_time_limit(120);
			
			// on récupére les méta-data de toutes les tables
			$classes = $app['orm.em']->getMetadataFactory()->getAllMetadata();
			
			// on créé la base de donnée
			$tool->createSchema($classes);
			
			// création de l'utilisateur admin
			return $app->redirect($app['url_generator']->generate('install_create_user'));
		}
		
		return $app['twig']->render('install/index.twig',array('form' => $form->createView()));
		
	}
}