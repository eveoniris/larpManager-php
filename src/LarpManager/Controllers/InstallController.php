<?php
namespace LarpManager\Controllers;

use Silex\Application;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\RememberMeServiceProvider;
use LarpManager\User\UserServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SqlFormatter;
use Symfony\Component\Yaml\Dumper;

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
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder(new \LarpManager\Form\InstallUserAdminForm())
		->add('create','submit')
		->getForm();
		
		$form->handleRequest($request);
		
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$data = $form->getData();
			
			$name = $data['name'];
			$email = $data['email'];
			$password = $data['password'];

			$app->register(new SecurityServiceProvider());
			$app->register(new UserServiceProvider());
			
			// on ajoute des éléments de base
			$user = $app['user.manager']->createUser($email, $password, $name, array('ROLE_ADMIN'));
			$app['user.manager']->insert($user);
			
			// supprimer le fichier de cache pour lancer larpmanager en mode normal		
			unlink(__DIR__.'/../../../cache/maintenance.tag');
			
			$app->mount('/', new \LarpManager\HomepageControllerProvider());
			$app['session']->getFlashBag()->add('success', 'L\'installation c\'est déroulée avec succès.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
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
		// valeur par défaut
		$default = array(
			'database_host' => $app['config']['database']['host'],
			'database_name' => $app['config']['database']['dbname'],
			'database_user' => $app['config']['database']['user'],
		);
		
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder(new \LarpManager\Form\InstallDatabaseForm(), $default)
			->add('create','submit')
			->getForm();
		
		$form->handleRequest($request);
		
		// si la requête est valide
		if ( $form->isValid() )
		{
			$databaseConfig = $form->getData();
			
			$newConfig = $app['config'];
			
			$newConfig['database'] = array(
					'host' => $databaseConfig['database_host'],
					'dbname' => $databaseConfig['database_name'], 
					'user' =>  $databaseConfig['database_user'],
					'password' => $databaseConfig['database_password'],
			);
						
			// write the new config
			$dumper = new Dumper();
			$yaml = $dumper->dump($newConfig);
			file_put_contents(__DIR__ . '/../../../config/settings.yml', $yaml);
			
			// reload doctrine with the new configuration
			$app->register(new DoctrineServiceProvider(), array(
	   			'db.options' => $newConfig['database'] 
			));

			// load doctrine tools
			$tool = new \Doctrine\ORM\Tools\SchemaTool($app['orm.em']);
		
			// l'opération peut prendre du temps, il faut donc régler le temps maximum d'execution
			set_time_limit(120);
			
			// on récupére les méta-data de toutes les tables
			$classes = $app['orm.em']->getMetadataFactory()->getAllMetadata();
			
			// on créé la base de donnée
			$tool->createSchema($classes);
			
			// on ajoute des éléments de base
			$etat = new \LarpManager\Entities\Etat();
			$etat->setLabel("En stock");
			$app['orm.em']->persist($etat);
			$app['orm.em']->flush();
				
			$etat = new \LarpManager\Entities\Etat();
			$etat->setLabel("Hors stock");
			$app['orm.em']->persist($etat);
			$app['orm.em']->flush();
			
			$etat = new \LarpManager\Entities\Etat();
			$etat->setLabel("A fabriquer");
			$app['orm.em']->persist($etat);
			$app['orm.em']->flush();
			
			$etat = new \LarpManager\Entities\Etat();
			$etat->setLabel("A acheter");
			$app['orm.em']->persist($etat);
			$app['orm.em']->flush();
			
			// Création des niveaux de compétence
			$niveau = new \LarpManager\Entities\Niveau();
			$niveau->setLabel("Apprenti");
			$niveau->setNiveau("1");
			$app['orm.em']->persist($niveau);
			$app['orm.em']->flush();
			
			$niveau = new \LarpManager\Entities\Niveau();
			$niveau->setLabel("Initié");
			$niveau->setNiveau("2");
			$app['orm.em']->persist($niveau);
			$app['orm.em']->flush();
			
			$niveau = new \LarpManager\Entities\Niveau();
			$niveau->setLabel("Expert");
			$niveau->setNiveau("3");
			$app['orm.em']->persist($niveau);
			$app['orm.em']->flush();
			
			$niveau = new \LarpManager\Entities\Niveau();
			$niveau->setLabel("Maître");
			$niveau->setNiveau("4");
			$app['orm.em']->persist($niveau);
			$app['orm.em']->flush();
			
			$niveau = new \LarpManager\Entities\Niveau();
			$niveau->setLabel("Secret");
			$niveau->setNiveau("5");
			$app['orm.em']->persist($niveau);
			$app['orm.em']->flush();
			
			$rarete = new \LarpManager\Entities\Rarete();
			$rarete->setLabel("Commun");
			$rarete->setValue("1");
			$app['orm.em']->persist($rarete);
			$app['orm.em']->flush();
			
			$rarete = new \LarpManager\Entities\Rarete();
			$rarete->setLabel("Rare");
			$rarete->setValue("2");
			$app['orm.em']->persist($rarete);
			$app['orm.em']->flush();
			
			$genre = new \LarpManager\Entities\Genre();
			$genre->setLabel("Masculin");
			$app['orm.em']->persist($genre);
			$app['orm.em']->flush();
			
			$genre = new \LarpManager\Entities\Genre();
			$genre->setLabel("Feminin");
			$app['orm.em']->persist($genre);
			$app['orm.em']->flush();
			
			$age = new \LarpManager\Entities\Age();
			$age->setLabel("Jeune adulte");
			$app['orm.em']->persist($age);
			$app['orm.em']->flush();
			
			$age = new \LarpManager\Entities\Age();
			$age->setLabel("Adulte");
			$app['orm.em']->persist($age);
			$app['orm.em']->flush();
			
			$age = new \LarpManager\Entities\Age();
			$age->setLabel("Mur");
			$app['orm.em']->persist($age);
			$app['orm.em']->flush();
			
			$age = new \LarpManager\Entities\Age();
			$age->setLabel("Vieux");
			$app['orm.em']->persist($age);
			$app['orm.em']->flush();
			
			$age = new \LarpManager\Entities\Age();
			$age->setLabel("Ancien");
			$app['orm.em']->persist($age);
			$app['orm.em']->flush();
									
			// création de l'utilisateur admin
			return $app->redirect($app['url_generator']->generate('install_create_user'));
		}
		
		return $app['twig']->render('install/index.twig',array('form' => $form->createView()));
		
	}
}