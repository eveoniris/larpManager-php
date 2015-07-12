<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class StockProprietaireControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\StockProprietaireController::indexAction')
			->bind("stock_proprietaire_index")
			->method('GET');
		
		$controllers->match('/{index}','LarpManager\Controllers\StockProprietaireController::detailAction')
			->assert('index', '\d+')
			->bind("stock_proprietaire_detail")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\StockProprietaireController::addAction')
			->bind("stock_proprietaire_add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\StockProprietaireController::updateAction')
			->assert('index', '\d+')
			->bind("stock_proprietaire_update")
			->method('GET|POST');
			
		$controllers->match('/{index}/delete','LarpManager\Controllers\StockProprietaireController::deleteAction')
			->assert('index', '\d+')
			->bind("stock_proprietaire_delete")
			->method('GET|POST');
		
		return $controllers;
	}
}

