<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\ReligionControllerProvider
 * 
 * @author kevin
 *
 */
class PnjControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/pnj/inscription','LarpManager\Controllers\PnjController::inscriptionAction')
			->bind("pnj.inscription")
			->method('GET|POST');
		
		return $controllers;
	}
}