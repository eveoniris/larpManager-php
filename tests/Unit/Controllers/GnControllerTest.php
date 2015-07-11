<?php
namespace LarpManager\Tests\Controller;

require_once(__DIR__ . "\..\..\BaseTestCase.php");
use LarpManager\Tests\BaseTestCase;


class GnControllerTest extends BaseTestCase
{
	public function testIndex()
	{
		$client = static::createClient();
		$crawler = $client->request('GET','/gn/');
		$this->assertTrue($client->getResponse()->isOk());
		$this->assertCount(1,$crawler->filter('caption:contains("Liste des GN")'));
	}
}