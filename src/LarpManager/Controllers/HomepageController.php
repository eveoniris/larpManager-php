<?php

/**
 * LarpManager - A Live Action Role Playing Manager
 * Copyright (C) 2016 Kevin Polez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\GroupeInscriptionForm;
use LarpManager\Form\GnInscriptionForm;
use LarpManager\Form\TrombineForm;
use LarpManager\Form\RuleForm;
use LarpManager\Form\EtatCivilForm;
use LarpManager\Form\UserRestrictionForm;

use LarpManager\Entities\EtatCivil;

/**
 * LarpManager\Controllers\HomepageController
 *
 * @author kevin
 *
 */
class HomepageController
{

	/**
	 * Choix de la page d'acceuil en fonction de l'état de l'utilisateur
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app) 
	{	

		if ( ! $app['user'] )
		{
			return $this->notConnectedIndexAction($request, $app);
		}
		
		$app['user'] = $app['user.manager']->refreshUser($app['user']);
		
		if ( ! $app['user']->getEtatCivil() )
		{
			return $app->redirect($app['url_generator']->generate('newUser.step1'),301);
		}
		
		$repoAnnonce = $app['orm.em']->getRepository('LarpManager\Entities\Annonce');
		$annonces = $repoAnnonce->findBy(array('archive' => false, 'gn' => null),array('update_date' => 'DESC'));
				
		return $app['twig']->render('homepage/index.twig', array(
				'annonces' => $annonces,
				'user' => $app['user'],
		));
	}
	
	/**
	 * Première étape pour un nouvel utilisateur
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function newUserStep1Action(Request $request, Application $app)
	{
		if ( $app['user']->getEtatCivil() )
		{
			$repoAnnonce = $app['orm.em']->getRepository('LarpManager\Entities\Annonce');
			$annonces = $repoAnnonce->findBy(array('archive' => false, 'gn' => null),array('update_date' => 'DESC'));
			
			return $app['twig']->render('homepage/index.twig', array(
					'annonces' => $annonces,
					'user' => $app['user'],
			));
		}
		return $app['twig']->render('public/newUser/step1.twig', array());
	}
	
	/**
	 * Seconde étape pour un nouvel utilisateur : enregistrer les informations administratives
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function newUserStep2Action(Request $request, Application $app)
	{
		$etatCivil = $app['user']->getEtatCivil();
		if (  ! $etatCivil )
			$etatCivil = new EtatCivil();
	
		$form = $app['form.factory']->createBuilder(new EtatCivilForm(), $etatCivil)
			->getForm();

		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$etatCivil = $form->getData();
			$app['user']->setEtatCivil($etatCivil);
			
			$app['orm.em']->persist($app['user']);
			$app['orm.em']->flush();
			
			return $app->redirect($app['url_generator']->generate('newUser.step3'),301);
		}
		
		return $app['twig']->render('public/newUser/step2.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Troisième étape pour un nouvel utilisateur : les restrictions alimentaires
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function newUserStep3Action(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new UserRestrictionForm(),$app['user'])
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$user = $form->getData();
			$newRestriction = $form->get("new_restriction")->getData();
			if ( $newRestriction )
			{
				$restriction = new Restriction();
				$restriction->setUserRelatedByAuteurId($app['user']);
				$restriction->setLabel($newRestriction);
			
				$app['orm.em']->persist($restriction);
				$user->addRestriction($restriction);
			}
				
			$app['orm.em']->persist($user);
			$app['orm.em']->flush();
			
			return $app->redirect($app['url_generator']->generate('newUser.step4'),301);
				
		}
		return $app['twig']->render('public/newUser/step3.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Quatrième étape pour un nouvel utilisateur : choisir un GN
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function newUserStep4Action(Request $request, Application $app)
	{
		return $app['twig']->render('public/newUser/step4.twig');
	}
	
	/**
	 * Fourni le blason pour affichage
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function getBlasonAction(Request $request, Application $app)
	{
		$blason = $request->get('blason');
		$filename = __DIR__.'/../../../private/img/blasons/'.$blason;
		return $app->sendFile($filename);
	}
	
	/**
	 * Page d'acceuil pour les utilisateurs non connecté
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function notConnectedIndexAction(Request $request, Application $app)
	{
		return $app['twig']->render('homepage/not_connected.twig');
	}	
	
	/**
	 * Affiche une carte du monde
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function worldAction(Request $request, Application $app)
	{
		return $app['twig']->render('public/world.twig');
	}
	
	/**
	 * Fourni la liste des pays, leur geographie et leur description
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function countriesAction(Request $request, Application $app)
	{
		$repoTerritoire = $app['orm.em']->getRepository('LarpManager\Entities\Territoire');
		$territoires = $repoTerritoire->findRoot();
	
		$countries = array();
		foreach ( $territoires as $territoire)
		{
			$countries[] = array(
					'id' => $territoire->getId(),
					'geom' => $territoire->getGeojson(),
					'name' => $territoire->getNom(),
					'color' => $territoire->getColor(),
					'description' => strip_tags($territoire->getDescription()),
					'groupes' =>  array_values($territoire->getGroupesPj()),
			);
		}
	
		return $app->json($countries);
	}
	
	/**
	 * Fourni la liste des régions
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function regionsAction(Request $request, Application $app)
	{
		$repoTerritoire = $app['orm.em']->getRepository('LarpManager\Entities\Territoire');
		$territoires = $repoTerritoire->findRegions();
	
		$regions = array();
		foreach ( $territoires as $territoire)
		{
			$regions[] = array(
					'id' => $territoire->getId(),
					'geom' => $territoire->getGeojson(),
					'name' => $territoire->getNom(),
					'color' => $territoire->getColor(),
					'description' => strip_tags($territoire->getDescription()),
					'groupes' =>  array_values($territoire->getGroupesPj()),
			);
		}
	
		return $app->json($regions);
	}
	
	/**
	 * Fourni la liste des fiefs
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function fiefsAction(Request $request, Application $app)
	{
		$repoTerritoire = $app['orm.em']->getRepository('LarpManager\Entities\Territoire');
		$territoires = $repoTerritoire->findFiefs();
	
		$fiefs = array();
		foreach ( $territoires as $territoire)
		{
			$fiefs[] = array(
					'id' => $territoire->getId(),
					'geom' => $territoire->getGeojson(),
					'name' => $territoire->getNom(),
					'color' => $territoire->getColor(),
					'description' => strip_tags($territoire->getDescription()),
					'groupes' =>  array_values($territoire->getGroupesPj()),
			);
		}
	
		return $app->json($fiefs);
	}	

	/**
	 * Met à jour la geographie d'un pays
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateCountryGeomAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		$geom = $request->get('geom');
		$color = $request->get('color');
		
		$territoire->setGeojson($geom);
		$territoire->setColor($color);
		
		$app['orm.em']->persist($territoire);
		$app['orm.em']->flush();
		
		$country = array(
					'id' => $territoire->getId(),
					'geom' => $territoire->getGeojson(),
					'name' => $territoire->getNom(),
					'description' => strip_tags($territoire->getDescription()),
					'groupes' =>  array_values($territoire->getGroupesPj()),
				);
		return $app->json($country);
	}
	
	/**
	 * Affiche une page récapitulatif des liens pour discuter
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function discuterAction(Request $request, Application $app)
	{
		return $app['twig']->render('public/discuter.twig');
	}
	
	/**
	 * Affiche une page récapitulatif des événements
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function evenementAction(Request $request, Application $app)
	{
		return $app['twig']->render('public/evenement.twig');
	}
	
	/**
	 * Affiche les mentions légales
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function legalAction(Request $request, Application $app)
	{
		return $app['twig']->render('homepage/legal.twig');
	}
	
	/**
	 * Affiche les informations de dev
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function devAction(Request $request, Application $app)
	{
		return $app['twig']->render('homepage/dev.twig');
	}
	
	/**
	 * Statistiques du projet
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function metricsAction(Request $request, Application $app)
	{
		return $app['twig']->render('homepage/metrics/report.html');
	}
}