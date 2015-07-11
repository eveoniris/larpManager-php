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
		$controllers->get('/objet/','LarpManager\Controllers\StockController::objetListAction');
		$controllers->get('/possesseur','LarpManager\Controllers\StockController::possesseurListAction')->bind("stock_proprietaire_list");
		$controllers->get('/possesseur/','LarpManager\Controllers\StockController::possesseurListAction');
		$controllers->get('/tag','LarpManager\Controllers\StockController::tagListAction')->bind("stock_tag_list");
		$controllers->get('/tag/','LarpManager\Controllers\StockController::tagListAction');
		$controllers->get('/etat','LarpManager\Controllers\StockController::etatListAction')->bind("stock_etat_list");
		$controllers->get('/etat/','LarpManager\Controllers\StockController::etatListAction');
		$controllers->get('/localisation','LarpManager\Controllers\StockController::localisationListAction')->bind("stock_localisation_list");
		
		$controllers->get('/objet/add','LarpManager\Controllers\StockController::objetAddAction')->bind("stock_objet_add");
		$controllers->post('/objet/add','LarpManager\Controllers\StockController::objetAddAction');
		$controllers->get('/objet/{index}','LarpManager\Controllers\StockController::objetDetailAction')->bind("stock_objet_detail");
		$controllers->get('/objet/{index}/update','LarpManager\Controllers\StockController::objetUpdateAction')->bind("stock_objet_update");
		$controllers->post('/objet/{index}/update','LarpManager\Controllers\StockController::objetUpdateAction');
		$controllers->get('/objet/{index}/delete','LarpManager\Controllers\StockController::objetDeleteAction')->bind("stock_objet_delete");
		$controllers->post('/objet/{index}/delete','LarpManager\Controllers\StockController::objetDeleteAction');
		
		$controllers->get('/proprietaire/add','LarpManager\Controllers\StockController::proprietaireAddAction')->bind("stock_proprietaire_add");
		$controllers->post('/proprietaire/add','LarpManager\Controllers\StockController::proprietaireAddAction');
		$controllers->get('/proprietaire/{index}','LarpManager\Controllers\StockController::proprietaireDetailAction')->bind("stock_proprietaire_detail");
		$controllers->get('/proprietaire/{index}/update','LarpManager\Controllers\StockController::proprietaireUpdateAction')->bind("stock_proprietaire_update");
		$controllers->post('/proprietaire/{index}/update','LarpManager\Controllers\StockController::proprietaireUpdateAction');
		$controllers->get('/proprietaire/{index}/delete','LarpManager\Controllers\StockController::proprietaireDeleteAction')->bind("stock_proprietaire_delete");
		$controllers->post('/proprietaire/{index}/delete','LarpManager\Controllers\StockController::proprietaireDeleteAction');
		
		$controllers->get('/tag/add','LarpManager\Controllers\StockController::tagAddAction')->bind("stock_tag_add");
		$controllers->post('/tag/add','LarpManager\Controllers\StockController::tagAddAction');
		$controllers->get('/tag/{index}','LarpManager\Controllers\StockController::tagDetailAction');
		$controllers->get('/tag/{index}/update','LarpManager\Controllers\StockController::tagUpdateAction');
		$controllers->post('/tag/{index}/update','LarpManager\Controllers\StockController::tagUpdateAction');
		$controllers->get('/tag/{index}/delete','LarpManager\Controllers\StockController::tagDeleteAction');
		$controllers->post('/tag/{index}/delete','LarpManager\Controllers\StockController::tagDeleteAction');
		
		$controllers->get('/etat/add','LarpManager\Controllers\StockController::etatAddAction')->bind("stock_etat_add");
		$controllers->post('/etat/add','LarpManager\Controllers\StockController::etatAddAction');
		$controllers->get('/etat/{index}','LarpManager\Controllers\StockController::etatDetailAction')->bind("stock_etat_detail");
		$controllers->get('/etat/{index}/update','LarpManager\Controllers\StockController::etatUpdateAction')->bind("stock_etat_update");
		$controllers->post('/etat/{index}/update','LarpManager\Controllers\StockController::etatUpdateAction');
		$controllers->get('/etat/{index}/delete','LarpManager\Controllers\StockController::etatDeleteAction')->bind("stock_etat_delete");
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
