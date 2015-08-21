<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;


class GnControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\GnController::indexAction')
		->bind("gn")
		->method('GET');

		$controllers->match('/add','LarpManager\Controllers\GnController::addAction')
		->bind("gn.add")
		->method('GET|POST');

		$controllers->match('/{index}/update','LarpManager\Controllers\GnController::updateAction')
		->assert('index', '\d+')
		->bind("gn.update")
		->method('GET|POST');

		$controllers->match('/{index}','LarpManager\Controllers\GnController::detailAction')
		->assert('index', '\d+')
		->bind("gn.detail")
		->method('GET');

		return $controllers;
	}
}