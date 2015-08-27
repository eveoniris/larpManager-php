<?php

namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use LarpManager\Form\GroupeInscriptionForm;

class HomepageController
{
	/**
	 * affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app) 
	{	
		$form = $app['form.factory']->createBuilder(new GroupeInscriptionForm(), array())
			->add('subscribe','submit', array('label' => 'S\'inscrire'))
			->getForm();
		
		return $app['twig']->render('homepage/index.twig', array('form_groupe' => $form->createView()));
	}
	
	/**
	 * Affiche une carte du monde
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function worldAction(Request $request, Application $app)
	{	
		return $app['twig']->render('homepage/world.twig');		
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
				$app['user']->setGroupe($groupe);
												
				// si l'utilisateur n'a pas de lien avec un objet joueur, il faut le créé maintenant.
				$joueur = new \LarpManager\Entities\Joueur();
				$joueur->setUser($app['user']);
				
				$app['orm.em']->persist($joueur);
				$app['orm.em']->persist($app['user']);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Vous êtes maintenant inscrit au groupe.');
				return $app->redirect($app['url_generator']->generate('groupe.joueur',array('index' => $groupe->getId())),301);
			}
			else
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, le code fourni n\'est pas valide. Veuillez vous rapprocher de votre chef de groupe pour le vérifier.');
				return $app->redirect($app['url_generator']->generate('homepage'),301);
			}
		}
	}
}