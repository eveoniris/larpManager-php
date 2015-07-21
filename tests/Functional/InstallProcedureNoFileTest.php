<?php

namespace LarpManager\Tests;

use Silex\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;
use SqlFormatter;

//
class InstallProcedureNoFileTest extends WebTestCase
{
	protected $app;
	
	public function createApplication()
	{
		// app.php must return an Application instance
		$this->app = require __DIR__. '/../../app/bootstrap.php';
		unset($this->app['exception_handler']); //afficher les erreurs directement dans la console
		return $this->app;
		
	}
	
	public function tearDown()
	{
		$schemaManager = $this->app['orm.em']->getConnection()->getSchemaManager();
		$schemaManager->dropAndCreateDatabase($this->app['orm.em']->getConnection()->getDatabase());
		
		return parent::tearDown();
	}
	
	public function testInstallProcedureNoMaintenanceNoFile()
	{
		$client = static::createClient();
		$this->assertFalse($this->app['maintenance']);
		$crawler = $client->request('GET','/install/');
		$this->assertFalse($client->getResponse()->isOk());
		$this->assertTrue($client->getResponse()->isRedirect('/'));
	}

}

