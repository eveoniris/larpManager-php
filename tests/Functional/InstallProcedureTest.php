<?php

namespace LarpManager\Tests;

use Silex\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;
use SqlFormatter;

//
class InstallProcedureTest extends WebTestCase
{
	protected $app;
	
	public function createApplication()
	{
		// app.php must return an Application instance
		$this->app = require __DIR__. '/../../app/bootstrap.php';
		unset($this->app['exception_handler']); //afficher les erreurs directement dans la console
		$this->app['db_install_path'] = __DIR__.'/'; //pas de fichier ici
		return $this->app;
		
	}
	
	public function tearDown()
	{
		$schemaManager = $this->app['orm.em']->getConnection()->getSchemaManager();
		$schemaManager->dropAndCreateDatabase($this->app['orm.em']->getConnection()->getDatabase());
		
		return parent::tearDown();
	}
	
	public function testInstallProcedureNoFile()
	{
		$client = static::createClient();
		$crawler = $client->request('GET','/install/');
		$this->assertTrue($client->getResponse()->isOk());
		$this->assertCount(1,$crawler->filter('p:contains("Installation de LarpManager")'));
		$this->assertCount(1,$crawler->filter('p:contains("Première étape, installation de la gestion des utilisateurs")'));
		
		$buttonCrawler = $crawler->selectButton('Installer');
		$form = $buttonCrawler->form();
		$client->submit($form);
		$this->assertTrue($client->getResponse()->isRedirect('/install/'));
		$crawler = $client->followRedirect();
		
		$this->assertCount(1,$crawler->filter('p:contains("Installation de LarpManager")'));
		$this->assertCount(1,$crawler->filter('p:contains("Aucun fichier de mise a jour n\'est présent.")'));
		
		$schemaManager = $this->app['orm.em']->getConnection()->getSchemaManager();
		$this->assertTrue($schemaManager->tablesExist(array('users')));
		$this->assertFalse($schemaManager->tablesExist(array('archetype')));
	}
	
	public function testInstallProcedureFileHere()
	{
		$this->app['db_install_path'] = __DIR__.'/../InputData/';
		$client = static::createClient();
		$crawler = $client->request('GET','/install/');
		$this->assertTrue($client->getResponse()->isOk());
		$this->assertCount(1,$crawler->filter('p:contains("Installation de LarpManager")'));
		$this->assertCount(1,$crawler->filter('p:contains("Première étape, installation de la gestion des utilisateurs")'));
		
		$buttonCrawler = $crawler->selectButton('Installer');
		$form = $buttonCrawler->form();
		$client->submit($form);
		$this->assertTrue($client->getResponse()->isRedirect('/install/'));
		$crawler = $client->followRedirect();
		
		$schemaManager = $this->app['orm.em']->getConnection()->getSchemaManager();
		$this->assertTrue($schemaManager->tablesExist(array('users')));
		$this->assertFalse($schemaManager->tablesExist(array('archetype')));
		
		$this->assertCount(1,$crawler->filter('p:contains("Installation de LarpManager")'));
		$this->assertCount(1,$crawler->filter('p:contains("Veuillez vérifier le contenu : ")'));
		
		$this->assertCount(1,$crawler->filter('div#sqlcontent:contains("CREATE")'));
		
		$buttonCrawler = $crawler->selectButton('Installer');
		$form = $buttonCrawler->form();
		$crawler = $client->submit($form);
		
		$this->assertTrue($client->getResponse()->isOk());
		
		$this->assertCount(1,$crawler->filter('p:contains("L\'installation s\'est déroulée avec succès")'));
		
		$this->assertTrue($schemaManager->tablesExist(array('users')));
		$this->assertTrue($schemaManager->tablesExist(array('archetype')));
		
	
	}
}

