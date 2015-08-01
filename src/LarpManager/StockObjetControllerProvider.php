<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class StockObjetControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->match('/','LarpManager\Controllers\StockObjetController::indexAction')
			->bind("stock_objet_index")
			->method('GET|POST');
		
		$controllers->match('/list/{page}','LarpManager\Controllers\StockObjetController::listAction')
			->assert('page', '\d+')
			->bind("stock_objet_list")
			->method('GET|POST');
		
		$controllers->match('/listWithoutProprio/{page}','LarpManager\Controllers\StockObjetController::listWithoutProprioAction')
			->assert('page', '\d+')
			->bind("stock_objet_list_without_proprio")
			->method('GET');
		
		$controllers->match('/listWithoutResponsable/{page}','LarpManager\Controllers\StockObjetController::listWithoutResponsableAction')
			->assert('page', '\d+')
			->bind("stock_objet_list_without_responsable")
			->method('GET');
			
		$controllers->match('/listWithoutRangement/{page}','LarpManager\Controllers\StockObjetController::listWithoutRangementAction')
			->assert('page', '\d+')
			->bind("stock_objet_list_without_rangement")
			->method('GET');
		
		$controllers->match('/export','LarpManager\Controllers\StockObjetController::exportAction')
			->bind("stock_objet_export")
			->method('GET');
		
		$controllers->match('/{index}','LarpManager\Controllers\StockObjetController::detailAction')
			->assert('index', '\d+')
			->bind("stock_objet_detail")
			->method('GET');
		
		$controllers->match('/{index}/photo','LarpManager\Controllers\StockObjetController::photoAction')
			->assert('index', '\d+')
			->bind("stock_objet_photo")
			->method('GET');
		
		$controllers->match('/add','LarpManager\Controllers\StockObjetController::addAction')
			->bind("stock_objet_add")
			->method('GET|POST');
		
		$controllers->match('/{index}/update','LarpManager\Controllers\StockObjetController::updateAction')
			->assert('index', '\d+')
			->bind("stock_objet_update")
			->method('GET|POST');
		
		$controllers->match('/{index}/clone','LarpManager\Controllers\StockObjetController::cloneAction')
			->assert('index', '\d+')
			->bind("stock_objet_clone")
			->method('GET|POST');
		
		return $controllers;
	}
}

