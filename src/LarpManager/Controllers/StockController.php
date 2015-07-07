<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

/**
 * Gestion du stock
 * @author kevin
 */
class StockController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/index.twig');
	}
	
	/**
	 * @description affiche la liste des objets
	 */
	public function objetListAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/objet/list.twig');
	}
	
	
	/**
	 * @description affiche la liste des possesseurs
	 */
	public function possesseurListAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/possesseur/list.twig');
	}
	
	/**
	 * @description affiche la liste des tags
	 */
	public function tagListAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/tag/list.twig');
	}
	
	/**
	 * @description affiche la liste des etats
	 */
	public function etatListAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/etat/list.twig');
	}
	
	/**
	 * @description affiche la liste des localisation
	 */
	public function localisationListAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/localisation/list.twig');
	}
	
	
	
	
	/**
	 * @description affiche la détail d'un objet
	 */
	public function objetDetailAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/objet/detail.twig');
	}
	
	/**
	 * @description ajoute un objet
	 */
	public function objetAddAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/objet/add.twig');
	}
	
	/**
	 * @description Met à jour un objet
	 */
	public function objetUpdateAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/objet/update.twig');
	}
	
	/**
	 * @description Supprime un objet
	 */
	public function objetDeleteAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/objet/delete.twig');
	}
	
		
	
	
	/**
	 * @description affiche le détail d'un possesseur
	 */
	public function possesseurDetailAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/possesseur/detail.twig');
	}
	
	/**
	 * @description Ajoute un possesseur
	 */
	public function possesseurAddAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/possesseur/add.twig');
	}
	
	/**
	 * @description Met à jour un possesseur
	 */
	public function possesseurUpdateAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/possesseur/update.twig');
	}
	
	/**
	 * @description Supprime un possesseur
	 */
	public function possesseurDeleteAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/possesseur/delete.twig');
	}
	
	
	
	
	/**
	 * @description affiche la détail d'un tag
	 */
	public function tagDetailAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/tag/detail.twig');
	}
	
	/**
	 * @description ajoute un tag
	 */
	public function tagAddAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/tag/add.twig');
	}
	
	/**
	 * @description Met à jour un tag
	 */
	public function tagUpdateAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/tag/update.twig');
	}
	
	/**
	 * @description Supprime un tag
	 */
	public function tagDeleteAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/tag/delete.twig');
	}
	
	

	/**
	 * @description affiche la détail d'un etat
	 */
	public function etatDetailAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/etat/detail.twig');
	}
	
	/**
	 * @description ajoute un etat
	 */
	public function etatAddAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/etat/add.twig');
	}
	
	/**
	 * @description Met à jour un etat
	 */
	public function etatUpdateAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/etat/update.twig');
	}
	
	/**
	 * @description Supprime un etat
	 */
	public function etatDeleteAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/etat/delete.twig');
	}
	
	
	/**
	 * @description affiche la détail d'une localisation
	 */
	public function localisationDetailAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/localisation/detail.twig');
	}
	
	/**
	 * @description ajoute une localisation
	 */
	public function localisationAddAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/localisation/add.twig');
	}
	
	/**
	 * @description Met à jour une localisation
	 */
	public function localisationUpdateAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/localisation/update.twig');
	}
	
	/**
	 * @description Supprime une localisation
	 */
	public function localisationDeleteAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/localisation/delete.twig');
	}
	
}