<?php
namespace LarpManager\Tests\Controller;

require_once(__DIR__ . "\..\..\BaseTestCase.php");
use LarpManager\Tests\BaseTestCase;


class GuildeControllerTest extends BaseTestCase
{
	public function testIndex()
	{
		$client = static::createClient();
		$crawler = $client->request('GET','/guilde/');
		$this->assertTrue($client->getResponse()->isOk());
		$this->assertCount(1,$crawler->filter('caption:contains("Liste des guildes")'));
	}
	
	public function testAddForm()
	{
		$client = static::createClient();
		$crawler = $client->request('GET','/guilde/add');
		$this->assertTrue($client->getResponse()->isOk());
		$this->assertCount(1,$crawler->filter('label:contains("Nom de la guilde")'));
	}
	
	public function testAddPost()
	{
		$client = static::createClient();
		$data = array(
				'label' => "test label",
				'description' => "super description"
		);
		$crawler = $client->request('POST','/guilde/add',$data);
		$this->assertTrue($client->getResponse()->isOk());
	}
}