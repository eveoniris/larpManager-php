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
		
		if ( ! $app['user']->getEtatCivil() )
		{
			return $app->redirect($app['url_generator']->generate('newUser.step1'),301);
		}
		
		$repoAnnonce = $app['orm.em']->getRepository('LarpManager\Entities\Annonce');
		$annonces = $repoAnnonce->findBy(array('archive' => false, 'gn' => null),array('update_date' => 'DESC'));
		
		$personnage = $app['personnage.manager']->getCurrentPersonnage();
		
		return $app['twig']->render('homepage/index.twig', array(
				'annonces' => $annonces,
				'user' => $app['user'],
				'personnage' => $personnage,
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
	 * Page de gestion des règles
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function rulesAdminAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new RuleForm(), array())
			->add('envoyer','submit', array('label' => 'Envoyer'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$files = $request->files->get($form->getName());
			
			$path = __DIR__.'/../../../private/rules/';
			$filename = $files['rule']->getClientOriginalName();
			$extension = $files['rule']->guessExtension();
			
			if (!$extension || ! in_array($extension, array('pdf'))) {
				$app['session']->getFlashBag()->add('error','Désolé, votre fichier ne semble pas valide (vérifiez le format de votre fichier)');
				return $app->redirect($app['url_generator']->generate('rules.admin'),301);
			}
			
			$ruleFilename = hash('md5',$app['user']->getUsername().$filename . time()).'.'.$extension;
				
			
			$files['rule']->move($path, $filename);
			
			$rule = new \LarpManager\Entities\Rule();
			$rule->setLabel($data['label']);
			$rule->setDescription($data['description']);
			$rule->setUrl($filename);
			
			$app['orm.em']->persist($rule);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Votre fichier a été enregistrée');				
		}
		
		$regles = $app['orm.em']->getRepository('LarpManager\Entities\Rule')->findAll();
		
		return $app['twig']->render('admin/rules/index.twig', array(
				'form' => $form->createView(),
				'regles' => $regles,
		));
		
	}
	
	/**
	 * Supression d'un fichier de règle
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function rulesAdminDeleteAction(Request $request, Application $app)
	{
		$ruleId = $request->get('rule');
		$rule = $app['orm.em']->getRepository('LarpManager\Entities\Rule')->find($ruleId);
		
		if ( ! $rule )
		{
			$app['session']->getFlashBag()->add('error','impossible de supprimer le fichier ' . $filename);
		}
		else 
		{
			$app['orm.em']->remove($rule);
			$app['orm.em']->flush();
		}
		
		$filename = __DIR__.'/../../../private/rules/'.$rule->getUrl();
		
		if ( file_exists($filename)) 
		{
			unlink($filename);
		}
		else
		{
			$app['session']->getFlashBag()->add('error','impossible de supprimer le fichier ' . $filename);
		}

		return $app->redirect($app['url_generator']->generate('rules.admin'),301);
	}		
	/**
	 * Télécharger une règle
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function getRuleAction(Request $request, Application $app)
	{
		$rule = $request->get('rule');
		$filename = __DIR__.'/../../../private/rules/'.$rule;
		return $app->sendFile($filename);
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
					'description' => strip_tags($territoire->getDescription())
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
					'description' => strip_tags($territoire->getDescription())
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
					'description' => strip_tags($territoire->getDescription())
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
					'description' => strip_tags($territoire->getDescription())
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
	
	/**
	 * Formulaire d'inscription d'un joueur dans un gn
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function inscriptionGnFormAction(Request $request, Application $app)
	{
		$idGn = $request->get('idGn');
		
		$repo = $app['orm.em']->getRepository("LarpManager\Entities\Gn");
		$gn = $repo->findOneById($idGn);
		
		/** Erreur, le gn n'existe pas ou n'est pas actif */
		if ( ! $gn || $gn->getActif() == false )
		{
			throw new LarpManager\Exception\RequestInvalidException();
		}
		
		$form = $app['form.factory']->createBuilder(new GnInscriptionForm(), array('idGn' => $idGn))
			->add('subscribe','submit', array('label' => 'S\'inscrire'))
			->getForm();
		
		return $app['twig']->render('homepage/inscriptionGn.twig', array(
				'form' => $form->createView(),
				'gn' => $gn,
		));
	}
	
	/**
	 * Traitement du formulaire d'inscription d'un joueur dans un gn
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @throws LarpManager\Exception\RequestInvalidException
	 */
	public function inscriptionGnAction(Request $request, Application $app)
	{
		$user = $app['user'];
		
		/** Erreur, l'utilisateur n'est pas encore un joueur */
		if ( ! $user->getJoueur() )
		{
			throw new LarpManager\Exception\RequestInvalidException();
		}
		
		$form = $app['form.factory']->createBuilder(new GnInscriptionForm())
			->add('subscribe','submit', array('label' => 'S\'inscrire'))
			->getForm();
		
		$form->handleRequest($request);
		
		/** Erreur si la requête est invalide */
		if (  ! $form->isValid() )
		{
			throw new LarpManager\Exception\RequestInvalidException();
		}
		
		$data = $form->getData();

		$repo = $app['orm.em']->getRepository("LarpManager\Entities\Gn");
		$gn = $repo->findOneById($data['idGn']);
		
		/** Erreur si le gn n'est pas actif (l'utilisateur à bidouillé la requête */
		if ( ! $gn || ! $gn->getActif() )
		{
			throw new LarpManager\Exception\RequestInvalidException();
		}
		
		/** Enregistre en base de données la nouvelle relation entre le joueur et le gn */
		$joueurGn = new \LarpManager\Entities\JoueurGn();
		$joueurGn->setJoueur($app['user']->getJoueur());
		$joueurGn->setGn($gn);
		$joueurGn->setSubscriptionDate(new \Datetime('NOW'));
		
		$app['orm.em']->persist($joueurGn);
		$app['orm.em']->flush();
		
		/** redirige vers la page principale avec un message de succés */
		$app['session']->getFlashBag()->add('success', 'Vous êtes maintenant inscrit au gn '. $gn->getLabel());
		return $app->redirect($app['url_generator']->generate('homepage'),301);
		
	}
	
	/**
	 * Inscription dans un groupe
	 * 
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
			
			$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
			$groupe = $repo->findOneByCode($data['code']);
			
			if ( $groupe )
			{
				$participant = $app['user']->getParticipantByGn($app['larp.manager']->getGnActif());
				$participant->setGroupe($groupe);
				
				if ( $participant->getPersonnage() )
				{
					$personnage = $participant->getPersonnage();
					$personnage->setGroupe($groupe);
					
					// le personnage doit perdre les langues liées à son ancien groupe
					// et récupérer les langues correspondant à son nouveau groupe.
					foreach( $personnage->getPersonnageLangues() as $personnageLangue )
					{
						if ($personnageLangue->getSource() == 'GROUPE')
						{
							$personnage->removePersonnageLangues($personnageLangue);
							$app['orm.em']->remove($personnageLangue);
						}
					}
					
					foreach ( $groupe->getTerritoires() as $territoire )
					{
						if ( $territoire->getLangue() )
						{
							$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
							$personnageLangue->setPersonnage($personnage);
							$personnageLangue->setSource('GROUPE');
							$personnageLangue->setLangue($territoire->getLangue());
							
							$app['orm.em']->persist($personnageLangue);
							$personnage->addPersonnageLangues($personnageLangue);
						}
					}
					$app['orm.em']->persist($personnage);
				}
												
				$app['orm.em']->persist($participant);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Vous êtes maintenant inscrit au groupe.');
				return $app->redirect($app['url_generator']->generate('homepage'),301);
			}
			else
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, le code fourni n\'est pas valide. Veuillez vous rapprocher de votre chef de groupe pour le vérifier.');
				return $app->redirect($app['url_generator']->generate('homepage'),301);
			}
		}
	}
}