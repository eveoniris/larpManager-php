<?php

namespace LarpManager\Controllers;

use LarpManager\Form\Type\ProprietaireType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

/**
 * Gestion du stock
 * @author kevin
 */
class StockProprietaireController
{
	/**
	 * @description affiche la liste des proprietaire
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Proprietaire');
		$proprietaires = $repo->findAll();
	
		return $app['twig']->render('stock/proprietaire/index.twig', array('proprietaires' => $proprietaires));
	}
	
	/**
	 * @description Ajoute un proprietaire
	 */
	public function addAction(Request $request, Application $app)
	{
		$proprietaire = new \LarpManager\Entities\Proprietaire();
	
		$form = $app['form.factory']->createBuilder(new ProprietaireType(), $proprietaire)
				->add('save','submit')
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$proprietaire = $form->getData();
			$app['orm.em']->persist($proprietaire);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Le propriétaire a été ajouté');
			return $app->redirect($app['url_generator']->generate('stock_proprietaire_index'));
		}
	
		return $app['twig']->render('stock/proprietaire/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un proprietaire
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Proprietaire');
		$proprietaire = $repo->find($id);
	
		$form = $app['form.factory']->createBuilder(new ProprietaireType(), $proprietaire)
				->add('update','submit')
				->add('delete','submit')
				->getForm();
			
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$proprietaire = $form->getData();
					
			if ($form->get('update')->isClicked()) 
			{
				$app['orm.em']->persist($proprietaire);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Le propriétaire a été mis à jour');
			}
			else if ($form->get('delete')->isClicked()) 
			{
				$app['orm.em']->remove($proprietaire);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Le proprietaire a été supprimé');
			}

			return $app->redirect($app['url_generator']->generate('stock_proprietaire_index'));				
		}
	
		return $app['twig']->render('stock/proprietaire/update.twig', array('proprietaire' => $proprietaire,'form' => $form->createView()));
	}
}