<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

class StockControllerProvider implements ControllerProviderInterface
{
	public function connect(Application $app)
	{
		// creates a new controller based on the default route
		$controllers = $app['controllers_factory'];

		$controllers->get('/','LarpManager\Controllers\StockController::indexAction')->bind("stock_homepage");
		$controllers->get('/objet','LarpManager\Controllers\StockController::objetListAction')->bind("stock_objet_list");
		$controllers->get('/possesseur','LarpManager\Controllers\StockController::possesseurListAction')->bind("stock_possesseur_list");
		$controllers->get('/tag','LarpManager\Controllers\StockController::tagListAction')->bind("stock_tag_list");
		$controllers->get('/etat','LarpManager\Controllers\StockController::etatListAction')->bind("stock_etat_list");
		$controllers->get('/localisation','LarpManager\Controllers\StockController::localisationListAction')->bind("stock_localisation_list");
		
		$controllers->get('/objet/add','LarpManager\Controllers\StockController::objetAddAction')->bind("stock_objet_add");
		$controllers->post('/objet/add','LarpManager\Controllers\StockController::objetAddAction');
		$controllers->get('/objet/{index}','LarpManager\Controllers\StockController::objetDetailAction')->bind("stock_objet_detail");
		$controllers->get('/objet/{index}/update','LarpManager\Controllers\StockController::objetUpdateAction')->bind("stock_objet_update");
		$controllers->post('/objet/{index}/update','LarpManager\Controllers\StockController::objetUpdateAction');
		$controllers->get('/objet/{index}/delete','LarpManager\Controllers\StockController::objetDeleteAction')->bind("stock_objet_delete");
		$controllers->post('/objet/{index}/delete','LarpManager\Controllers\StockController::objetDeleteAction');
		
		$controllers->get('/possesseur/add','LarpManager\Controllers\StockController::possesseurAddAction')->bind("stock_possesseur_add");
		$controllers->post('/possesseur/add','LarpManager\Controllers\StockController::possesseurAddAction');
		$controllers->get('/possesseur/{index}','LarpManager\Controllers\StockController::possesseurDetailAction');
		$controllers->get('/possesseur/{index}/update','LarpManager\Controllers\StockController::possesseurUpdateAction');
		$controllers->post('/possesseur/{index}/update','LarpManager\Controllers\StockController::possesseurUpdateAction');
		$controllers->get('/possesseur/{index}/delete','LarpManager\Controllers\StockController::possesseurDeleteAction');
		$controllers->post('/possesseur/{index}/delete','LarpManager\Controllers\StockController::possesseurDeleteAction');
		
		$controllers->get('/tag/add','LarpManager\Controllers\StockController::tagAddAction')->bind("stock_tag_add");
		$controllers->post('/tag/add','LarpManager\Controllers\StockController::tagAddAction');
		$controllers->get('/tag/{index}','LarpManager\Controllers\StockController::tagDetailAction');
		$controllers->get('/tag/{index}/update','LarpManager\Controllers\StockController::tagUpdateAction');
		$controllers->post('/tag/{index}/update','LarpManager\Controllers\StockController::tagUpdateAction');
		$controllers->get('/tag/{index}/delete','LarpManager\Controllers\StockController::tagDeleteAction');
		$controllers->post('/tag/{index}/delete','LarpManager\Controllers\StockController::tagDeleteAction');
		
		$controllers->get('/etat/add','LarpManager\Controllers\StockController::etatAddAction')->bind("stock_etat_add");
		$controllers->post('/etat/add','LarpManager\Controllers\StockController::etatAddAction');
		$controllers->get('/etat/{index}','LarpManager\Controllers\StockController::etatDetailAction');
		$controllers->get('/etat/{index}/update','LarpManager\Controllers\StockController::etatUpdateAction');
		$controllers->post('/etat/{index}/update','LarpManager\Controllers\StockController::etatUpdateAction');
		$controllers->get('/etat/{index}/delete','LarpManager\Controllers\StockController::etatDeleteAction');
		$controllers->post('/etat/{index}/delete','LarpManager\Controllers\StockController::etatDeleteAction');
		
		$controllers->get('/localisation/add','LarpManager\Controllers\StockController::localisationAddAction')->bind("stock_localisation_add");
		$controllers->post('/localisation/add','LarpManager\Controllers\StockController::localisationAddAction');
		$controllers->get('/localisation/{index}','LarpManager\Controllers\StockController::localisationDetailAction');
		$controllers->get('/localisation/{index}/update','LarpManager\Controllers\StockController::localisationUpdateAction');
		$controllers->post('/localisation/{index}/update','LarpManager\Controllers\StockController::localisationUpdateAction');
		$controllers->get('/localisation/{index}/delete','LarpManager\Controllers\StockController::localisationDeleteAction');
		$controllers->post('/localisation/{index}/delete','LarpManager\Controllers\StockController::localisationDeleteAction');
		
		return $controllers;
	}
}
