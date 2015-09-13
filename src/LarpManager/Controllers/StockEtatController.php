<?php

namespace LarpManager\Controllers;

use LarpManager\Form\Type\EtatType;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * LarpManager\Controllers\StockEtatController
 *
 * @author kevin
 *
 */
class StockEtatController
{

	/**
	 * @description affiche la liste des etats
	 */
	public function indexAction(Request $request, Application $app)
	{		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etats = $repo->findAll();
	
		return $app['twig']->render('stock/etat/index.twig', array('etats' => $etats));
	}
		
	/**
	 * @description ajoute un etat
	 */
	public function addAction(Request $request, Application $app)
	{
		$etat = new \LarpManager\Entities\Etat();
	
		$form = $app['form.factory']->createBuilder(new EtatType(), $etat)
				->add('save','submit')
				->getForm();
	
		// on passe la requête de l'utilisateur au formulaire
		$form->handleRequest($request);
	
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$etat = $form->getData();
			$app['orm.em']->persist($etat);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'L\'état a été ajouté.');
	
			return $app->redirect($app['url_generator']->generate('stock_etat_index'));
		}
	
		return $app['twig']->render('stock/etat/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un etat
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etat = $repo->find($id);
	
		$form = $app['form.factory']->createBuilder(new EtatType(), $etat)
				->add('update','submit')
				->add('delete','submit')
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$etat = $form->getData();
	
			if ($form->get('update')->isClicked()) 
			{
				$app['orm.em']->persist($etat);
				$app['orm.em']->flush();			
				$app['session']->getFlashBag()->add('success', 'L\'état a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked()) 
			{
				$app['orm.em']->remove($etat);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'L\'état a été supprimé.');
			}
			
			return $app->redirect($app['url_generator']->generate('stock_etat_index'));
			
		}
		return $app['twig']->render('stock/etat/update.twig', array(
				'etat' => $etat,
				'form' => $form->createView()));
	}	
}