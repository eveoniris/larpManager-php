<?php
namespace Tests\Controller;

//require_once(__DIR__ . "/../../BaseTestCase.php");
//require_once(__DIR__ . "/../../DataFixtures/UserFixture.php");
use Tests\BaseTestCase;
use Tests\DataFixtures\UserFixture;
use Doctrine\Common\DataFixtures\Loader;


class GnControllerTest extends BaseTestCase
{
	public function testIndex()
	{
	    //$this->app['monolog']->info("Begin GN test");
	    $fixture = new UserFixture();
	    $fixture->load($this->app["orm.em"]);
	    
	    $client = static::createClient();
		$this->LogIn($client, "testuser1");

		$crawler = $client->request('GET','/gn/');
		
		$this->assertTrue($client->getResponse()->isOk());
		$this->assertCount(1,$crawler->filter('li:contains("Liste des GNs")'));
		//$this->app['monolog']->info("End GN test");
	}
}