<?php

namespace LarpManager\Controllers;

use LarpManager\Form\Type\LocalisationType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

/**
 * Gestion du stock
 * @author kevin
 */
class StockLocalisationController
{
	/**
	 * @description affiche la liste des localisation
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Localisation');
		$localisations = $repo->findAll();
	
		return $app['twig']->render('stock/localisation/index.twig', array('localisations' => $localisations));
	}
	
	/**
	 * @description ajoute une localisation
	 */
	public function addAction(Request $request, Application $app)
	{
		$localisation = new \LarpManager\Entities\Localisation();
	
		$form = $app['form.factory']->createBuilder(new LocalisationType(), $localisation)
				->add('save','submit')
				->getForm();
	
		// on passe la requête de l'utilisateur au formulaire
		$form->handleRequest($request);
	
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$localisation = $form->getData();
			$app['orm.em']->persist($localisation);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La localisation a été ajoutée.');
			return $app->redirect($app['url_generator']->generate('stock_localisation_index'));
		}
	
		return $app['twig']->render('stock/localisation/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour une localisation
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Localisation');
		$localisation = $repo->find($id);
	
		$form = $app['form.factory']->createBuilder(new LocalisationType(), $localisation)
			->add('update','submit')
			->add('delete','submit')
			->getForm();
				
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$localisation = $form->getData();
	
			if ($form->get('update')->isClicked()) 
			{
				$app['orm.em']->persist($localisation);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La localisation a été mise à jour');
			}
			else if ($form->get('delete')->isClicked()) 
			{
				$app['orm.em']->remove($localisation);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La localisation a été suprimée');
			}
			

			return $app->redirect($app['url_generator']->generate('stock_localisation_index'));
		}
		return $app['twig']->render('stock/localisation/update.twig', array(
				'localisation' => $localisation,
				'form' => $form->createView()));
	}	
}