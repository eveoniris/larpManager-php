<?php

namespace LarpManager\Modules\Example;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\Example\ExampleControllerProvider
 *
 * @author kevin
 */
class ExampleControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
	
		/**
		 * Liste des annnonces
		 */
		$controllers->match('/','LarpManager\Modules\Example\Controllers\ExampleController::indexAction')
			->bind("example.index")
			->method('GET');
			
		return $controllers;
	}
}