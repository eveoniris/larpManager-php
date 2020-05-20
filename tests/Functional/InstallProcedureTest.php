<?php

//Uniquement pour reference

// namespace LarpManager\Tests;

// use Silex\WebTestCase;
// use Doctrine\ORM\Tools\SchemaTool;
// use SqlFormatter;

// //
// class InstallProcedureTest extends WebTestCase
// {
// 	protected $app;
	
// 	public function createApplication()
// 	{
// 		//cf. le fichier de settings
// 		copy(__DIR__. '/../InputData/create_or_update.sql',__DIR__. '/../Temporary/create_or_update.sql');
// 		$this->app = require __DIR__. '/../../app/bootstrap.php';
// 		unset($this->app['exception_handler']); //afficher les erreurs directement dans la console
// 		$this->app['session.test'] = true; //important pour tester le login
// 		return $this->app;
		
// 	}
	
// 	public function tearDown()
// 	{
// 		$schemaManager = $this->app['orm.em']->getConnection()->getSchemaManager();
// 		$schemaManager->dropAndCreateDatabase($this->app['orm.em']->getConnection()->getDatabase());
// 		unlink(__DIR__. '/../Temporary/create_or_update.sql');
// 		return parent::tearDown();
// 	}
	
// 	public function testInstallProcedureMaintenanceFileDisabled()
// 	{
// 		$client = static::createClient();
// 		$this->assertTrue($this->app['maintenance']);
		
// 		$crawler = $client->request('GET','/');
// 		//var_dump( $client->getResponse()->getStatusCode());
// 		//var_dump( $client->getResponse()->getContent());
// 		$this->assertTrue($client->getResponse()->isNotFound());
// 		$this->assertCount(1,$crawler->filter('p:contains("Le site est en maintenance.")'));
		
// 		$crawler = $client->request('GET','/toto');
// 		$this->assertTrue($client->getResponse()->isNotFound());
// 		$this->assertCount(1,$crawler->filter('p:contains("Le site est en maintenance.")'));
		
// 		//$crawler = $client->request('GET','/install/');
// 		//$this->assertTrue($client->getResponse()->isNotFound());
// 		//$this->assertCount(0,$crawler->filter('p:contains("Le site est en maintenance.")'));
		
// 	}
	
// 	public function testFullInstallProcedureNotLogged()
// 	{
// 		$client = static::createClient();
		
// 		//Il n'y a pas encore de base user (premiere installation uniquement)
// 		$this->assertTrue($this->app['maintenance']);
// 		$crawler = $client->request('GET','/install/');
		
// 		$this->assertTrue($client->getResponse()->isOk());
// 		$this->assertCount(1,$crawler->filter('p:contains("Installation de LarpManager, première étape")'));
		
// 		{
// 			$buttonCrawler = $crawler->selectButton('Installer');
// 			$form = $buttonCrawler->form();
// 			$client->submit($form);
// 		}
		
// 		$schemaManager = $this->app['orm.em']->getConnection()->getSchemaManager();
// 		$this->assertTrue($schemaManager->tablesExist(array('users')));
		
// 		$this->assertTrue($client->getResponse()->isRedirect('/install/'));
// 		$crawler = $client->followRedirect();
		
// 		$this->assertTrue($client->getResponse()->isOk());
		
// 		//Il y a une base user, il faut creer le premier user (premiere installation uniquement)
// 		$this->assertCount(1,$crawler->filter('p:contains("Installation de LarpManager, deuxième étape")'));
		
// 		{
// 			$buttonCrawler = $crawler->selectButton('Register');
// 			$form = $buttonCrawler->form();
// 			$formData = array(
// 					'name'=>'Toto admin',
// 					'email'=>'toto@toto.com',
// 					'password' => 'toto'
// 			);
				
// 			$client->submit($form, $formData);
// 		}
		
// 		{
// 			$this->assertTrue($this->app['user.manager']->findCount() == 1);
// 			$user = $this->app['user.manager']->findOneBy(array(
// 					'email' => 'toto@toto.com',
// 			));
		
// 			$this->assertTrue($user!=null);
// 			//Ne marche que sur un utilisateur logge selon l'api
// 			//$this->assertTrue($this->app['security.authorization_checker']->isGranted('ROLE_ADMIN', $user));
// 			$this->assertTrue("Toto admin" == $user->getName());
// 			$this->assertTrue("toto@toto.com" == $user->getEmail());
// 		}
		
// 		$this->assertTrue($client->getResponse()->isRedirect('/install/'));
// 		$crawler = $client->followRedirect(); //on est dans le controlleur d'install qui verifie qu'on est logge
// 		$crawler = $client->followRedirect(); //vers la page login
		
		
// 		//Il faut maintenant se connecter avec le compte que l'on vient de creer, mais on va tester
// 		//la securite avec un autre user auparavant
// 		$this->assertCount(1,$crawler->filter('h1:contains("Connexion, mode maintenance")'));
		
// 		$user = $this->app['user.manager']->createUser("guy@brush.com", "leChuck", "Guybrush Threepwood", array('ROLE_USER'));
// 		$this->app['user.manager']->insert($user);
// 		$user = $this->app['user.manager']->createUser("marcel@toto.com", "Borknagar", "Marcel");
// 		$this->app['user.manager']->insert($user);
		
// 		//Se logger avec un user qui n'a pas les droits necessaires
// 		$this->assertTrue($this->app['user.manager']->findCount() == 3);
// 		{
// 			$buttonCrawler = $crawler->selectButton('Se connecter');
// 			$form = $buttonCrawler->form();
// 			$formData = array(
// 					'_username'=>'guy@brush.com',
// 					'_password' => 'leChuck'
// 			);
		
// 			$client->submit($form, $formData);
				
		
// 		}
		
// 		$crawler = $client->followRedirect(); //redirect login check -> install
// 		$crawler = $client->followRedirect(); //redirect install -> login
		
// 		$this->assertCount(1,$crawler->filter('a:contains("Sign out")'));
		
// 		//On ne doit pas pouvoir acceder a l'update
// 		$crawler = $client->request('POST','/install/larpupdate');
		
// 		$this->assertCount(1,$crawler->filter('p:contains("pas les droits pour cette action.")'));
// 	}
	
// 	//On teste la sequence d'installation de A a Z
// 	public function testFullInstallProcedure()
// 	{
// 		$client = static::createClient();
		
// 		//Il n'y a pas encore de base user (premiere installation uniquement)
// 		$this->assertTrue($this->app['maintenance']);
// 		$crawler = $client->request('GET','/install/');
		
// 		$this->assertTrue($client->getResponse()->isOk());
// 		$this->assertCount(1,$crawler->filter('p:contains("Installation de LarpManager, première étape")'));
		
// 		{
// 			$buttonCrawler = $crawler->selectButton('Installer');
// 			$form = $buttonCrawler->form();
// 			$client->submit($form);
// 		}
		
// 		$schemaManager = $this->app['orm.em']->getConnection()->getSchemaManager();
// 		$this->assertTrue($schemaManager->tablesExist(array('users')));
		
// 		$this->assertTrue($client->getResponse()->isRedirect('/install/'));
// 		$crawler = $client->followRedirect();
		
// 		$this->assertTrue($client->getResponse()->isOk());
		
// 		//Il y a une base user, il faut creer le premier user (premiere installation uniquement)
// 		$this->assertCount(1,$crawler->filter('p:contains("Installation de LarpManager, deuxième étape")'));
		
// 		{
// 			$buttonCrawler = $crawler->selectButton('Register');
// 			$form = $buttonCrawler->form();
// 			$formData = array(
// 				'name'=>'Toto admin',
// 				'email'=>'toto@toto.com',
// 				'password' => 'toto'
// 			);
			
// 			$client->submit($form, $formData);
// 		}
		
// 		{
// 			$this->assertTrue($this->app['user.manager']->findCount() == 1);
// 			$user = $this->app['user.manager']->findOneBy(array(
// 					'email' => 'toto@toto.com',
// 			));
		
// 			$this->assertTrue($user!=null);
// 			//Ne marche que sur un utilisateur logge selon l'api
// 			//$this->assertTrue($this->app['security.authorization_checker']->isGranted('ROLE_ADMIN', $user));
// 			$this->assertTrue("Toto admin" == $user->getName());
// 			$this->assertTrue("toto@toto.com" == $user->getEmail());
// 		}
		
// 		$this->assertTrue($client->getResponse()->isRedirect('/install/'));
// 		$crawler = $client->followRedirect(); //on est dans le controlleur d'install qui verifie qu'on est logge
// 		$crawler = $client->followRedirect(); //vers la page login
		
		
// 		//Il faut maintenant se connecter avec le compte que l'on vient de creer, mais on va tester
// 		//la securite avec un autre user auparavant
// 		$this->assertCount(1,$crawler->filter('h1:contains("Connexion, mode maintenance")'));
		
//  		{
// 			$buttonCrawler = $crawler->selectButton('Se connecter');
// 			$form = $buttonCrawler->form();
// 			$formData = array(
// 					'_username'=>'toto@toto.com',
// 					'_password' => 'toto'
// 			);
// 			$client->submit($form, $formData);
// 		}
		
// 		$crawler = $client->followRedirect(); //vers la page login
		
// 		//var_dump($client->getResponse()->getContent());
		
// 		$this->assertFalse($schemaManager->tablesExist(array('archetype')));
		
// 		$this->assertCount(1,$crawler->filter('p:contains("Installation de LarpManager")'));
// 		$this->assertCount(1,$crawler->filter('p:contains("Veuillez vérifier le contenu : ")'));
		
// 		$this->assertCount(1,$crawler->filter('div#sqlcontent:contains("CREATE")'));
		
// 		$buttonCrawler = $crawler->selectButton('Installer');
// 		$form = $buttonCrawler->form();
// 		$crawler = $client->submit($form);
		
// 		$this->assertTrue($client->getResponse()->isOk());
		
// 		$this->assertCount(1,$crawler->filter('p:contains("L\'installation s\'est déroulée avec succès")'));
		
// 		$this->assertTrue($schemaManager->tablesExist(array('users')));
// 		$this->assertTrue($schemaManager->tablesExist(array('archetype')));
// 	}
// }

