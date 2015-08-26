<?php

namespace LarpManager\Tests;

use Silex\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;


class BaseTestCase extends WebTestCase
{
	protected $app;
	
	public function createApplication()
	{
		// app.php must return an Application instance
		$this->app = require __DIR__. '/../app/bootstrap.php';
		unset($this->app['exception_handler']); //afficher les erreurs directement dans la console
		$this->generateSchema($this->app);
		return $this->app;
		
	}
	
	public function tearDown()
	{
		$this->dropSchema($this->app);
		return parent::tearDown();
	}
	
	private function dropSchema($app)
	{
		$metadata = $this->getMetadata($app);
		if ( ! empty($metadata))
		{
			// Create SchemaTool
			$tool = new SchemaTool($app['orm.em']);
			$tool->dropSchema($metadata);
		} 
		else
		{
			throw new Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
		}
	}
	
	private function getMetadata($app)
	{
		return $app['orm.em']->getMetadataFactory()->getAllMetadata();
	
	}
	
	private function generateSchema($app)
	{
		$metadata = $this->getMetadata($app);
	 	if ( ! empty($metadata)) 
	 	{
            // Create SchemaTool
            $tool = new SchemaTool($app['orm.em']);
            $tool->createSchema($metadata);
        } 
        else 
        {
            throw new Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
		
	}

}

