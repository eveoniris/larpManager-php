<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\GenreControllerProvider
 * 
 * @author kevin
 *
 */
class GenreControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les genres
	 * Routes :
	 * 	- genre
	 * 	- genre.add
	 *  - genre.update
	 *  - genre.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\GenreController::indexAction')
			->bind("genre")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\GenreController::addAction')
			->bind("genre.add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\GenreController::updateAction')
			->assert('index', '\d+')
			->bind("genre.update")
			->method('GET|POST');
		
		$controllers->match('/{index}','LarpManager\Controllers\GenreController::detailAction')
			->assert('index', '\d+')
			->bind("genre.detail")
			->method('GET');
		
		return $controllers;
	}
}