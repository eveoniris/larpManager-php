<?php

namespace LarpManager\Tests;

use Silex\WebTestCase;
class BaseTestCase extends WebTestCase
{

	public function createApplication()
	{
		// app.php must return an Application instance
		return require __DIR__. '/../app/bootstrap.php';
		
	}

}

