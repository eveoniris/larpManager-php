<?php

namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use LarpManager\Form\GroupeInscriptionForm;

class HomepageController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app) 
	{	
		$form = $app['form.factory']->createBuilder(new GroupeInscriptionForm(), array())
			->add('subscribe','submit', array('label' => 'S\'inscrire'))
			->getForm();
		
		return $app['twig']->render('homepage/index.twig', array('form_groupe' => $form->createView()));
	}
	
	/**
	 * Inscription dans un groupe
	 * @param Request $request
	 * @param Application $app
	 */
	public function inscriptionAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new GroupeInscriptionForm(), array())
			->add('subscribe','submit', array('label' => 'S\'inscrire'))
			->getForm();
		
	
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			$groupe = $app['groupe.manager']->findByCode($data['code']);
			
			if ( $groupe )
			{
				$app['groupe.manager']->addOnGroupe($app['user'],$groupe);
				
				$app['session']->getFlashBag()->add('success', 'Vous êtes maintenant inscrit au groupe.');
				return $app->redirect($app['url_generator']->generate('groupe.personnage',array('index' => $groupe->getId())),301);
			}
			else
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, le code fourni n\'est pas valide. Veuillez vous rapprocher de votre chef de groupe pour le vérifier.');
				return $app->redirect($app['url_generator']->generate('homepage'),301);
			}
		}
	}
}