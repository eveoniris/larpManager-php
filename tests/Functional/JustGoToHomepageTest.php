<?php
namespace Tests\Functional;

require_once(__DIR__ . "/../BaseTestCase.php");
use Tests\BaseTestCase;


class JustGoToHomepageTest extends BaseTestCase
{
	public function testIndex()
	{
		$client = static::createClient();
		$crawler = $client->request('GET','/');
		$this->assertTrue($client->getResponse()->isOk());
		$this->assertCount(1,$crawler->filter('a:contains("LarpManager")'));
	}
}