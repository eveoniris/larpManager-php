<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\IngredientControllerProvider
 * 
 * @author kevin
 *
 */
class IngredientControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les genres
	 * Routes :
	 * 	- ingredient.admin.list
	 * 	- ingredient.admin.add
	 *  - ingredient.admin.update
	 *  - ingredient.admin.detail
	 *
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		$controllers->match('/','LarpManager\Controllers\IngredientController::adminListAction')
			->bind("ingredient.admin.list")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\IngredientController::adminAddAction')
			->bind("ingredient.admin.add")
			->method('GET|POST');
		
		$controllers->match('/{ingredient}/update','LarpManager\Controllers\IngredientController::adminUpdateAction')
			->assert('ingredient', '\d+')
			->convert('ingredient', 'converter.ingredient:convert')
			->bind("ingredient.admin.update")
			->method('GET|POST');
		
		$controllers->match('/{ingredient}/delete','LarpManager\Controllers\IngredientController::adminDeleteAction')
			->assert('ingredient', '\d+')
			->convert('ingredient', 'converter.ingredient:convert')
			->bind("ingredient.admin.delete")
			->method('GET|POST');
		
		$controllers->match('/{ingredient}','LarpManager\Controllers\IngredientController::adminDetailAction')
			->assert('ingredient', '\d+')
			->convert('ingredient', 'converter.ingredient:convert')
			->bind("ingredient.admin.detail")
			->method('GET');
		
		return $controllers;
	}
}