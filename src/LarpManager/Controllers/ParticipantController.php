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

use LarpManager\Form\JoueurForm;
use LarpManager\Form\FindJoueurForm;
use LarpManager\Form\RestaurationForm;
use LarpManager\Form\JoueurXpForm;

/**
 * LarpManager\Controllers\ParticipantController
 *
 * @author kevin
 *
 */
class ParticipantController
{
	
	/**
	 * liste des participants
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Participant');
		$participants = $repo->findAllOrderedByUsernameGroupe();
		return $app['twig']->render('admin/participant.twig', array('participants' => $participants));
	}
	
	/**
	 * liste des non participants
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function listNonAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Participant');
		$participants = $repo->findAllOrderedByUsernameNonGroupe();
		return $app['twig']->render('admin/participantNon.twig', array('participants' => $participants));
	}
	
	/**
	 * liste des participants fédégn
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function listFedegnAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Participant');
		$participants = $repo->findAllOrderedByUsernameGroupeFedegn();
		return $app['twig']->render('admin/participantFedegn.twig', array('participants' => $participants));
	}

	/**
	 * liste des participants non fédégn
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function listFedegnNonAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Participant');
		$participants = $repo->findAllOrderedByUsernameGroupeNonFedegn();
		return $app['twig']->render('admin/participantFedegnNon.twig', array('participants' => $participants));
	}
	
	/**
	 * liste des participants non fédégn
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function listFedegnPnjNonAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Participant');
		$participants = $repo->findAllOrderedByUsernameGroupePNJNonFedegn();
		return $app['twig']->render('admin/participantFedegnPnjNon.twig', array('participants' => $participants));
	}
	
	/**
	 * Affiche le formulaire d'ajout d'un joueur
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$joueur = new \LarpManager\Entities\Joueur();
	
		$form = $app['form.factory']->createBuilder(new JoueurForm(), $joueur)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$joueur = $form->getData();
			$app['user']->setJoueur($joueur);
			
			$app['orm.em']->persist($app['user']);
			$app['orm.em']->persist($joueur);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Vos informations ont été enregistrés.');
	
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
	
		return $app['twig']->render('joueur/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Recherche d'un joueur
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function searchAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new FindJoueurForm(), array())
			->add('submit','submit', array('label' => 'Rechercher'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			$type = $data['type'];
			$search = $data['search'];

			$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Joueur');
			
			$joueurs = null;
			
			switch ($type)
			{
				case 'lastName' :
					$joueurs = $repo->findByLastName($search);
					break;
				case 'firstName' :
					$joueurs = $repo->findByFirstName($search);
					break;
				case 'numero' :
					// TODO
					break;
			}
			
			if ( $joueurs != null )
			{
				if ( $joueurs->count() == 0 )
				{
					$app['session']->getFlashBag()->add('error', 'Le joueur n\'a pas été trouvé.');
					return $app->redirect($app['url_generator']->generate('homepage'), 301);
				}
				else if ( $joueurs->count() == 1 )
				{
					$app['session']->getFlashBag()->add('success', 'Le joueur a été trouvé.');
					return $app->redirect($app['url_generator']->generate('joueur.detail.orga', array('index'=> $joueurs->first()->getId())),301);
				}
				else
				{
					$app['session']->getFlashBag()->add('success', 'Il y a plusieurs résultats à votre recherche.');
					return $app['twig']->render('joueur/search_result.twig', array(
						'joueurs' => $joueurs,
					));
				}
			}
			
			$app['session']->getFlashBag()->add('error', 'Désolé, le joueur n\'a pas été trouvé.');
		}
		
		return $app['twig']->render('joueur/search.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Detail d'un joueur
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDetailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$joueur = $app['orm.em']->find('\LarpManager\Entities\Joueur',$id);
	
		if ( $joueur )
		{
			return $app['twig']->render('joueur/admin/detail.twig', array('joueur' => $joueur));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le joueur n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('homepage'));
		}	
	}
	
	/**
	 * Met a jours les points d'expérience des joueurs
	 *
	 * @param Application $app
	 * @param Request $request
	 */
	public function adminXpAction(Application $app, Request $request)
	{
		$id = $request->get('index');
	
		$joueur = $app['orm.em']->find('\LarpManager\Entities\Joueur',$id);
	
		$form = $app['form.factory']->createBuilder(new JoueurXpForm(), $joueur)
			->add('update','submit', array('label' => "Sauvegarder"))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $request->getMethod() == 'POST')
		{
			$newXps = $request->get('xp');
			$explanation = $request->get('explanation');
	
			$personnage = $joueur->getPersonnage();
			if ( $personnage->getXp() != $newXps)
			{
				$oldXp = $personnage->getXp();
				$gain = $newXps - $oldXp;
						
				$personnage->setXp($newXps);
				$app['orm.em']->persist($personnage);
						
				// historique
				$historique = new \LarpManager\Entities\ExperienceGain();
				$historique->setExplanation($explanation);
				$historique->setOperationDate(new \Datetime('NOW'));
				$historique->setPersonnage($personnage);
				$historique->setXpGain($gain);
				$app['orm.em']->persist($historique);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success','Les points d\'expérience ont été sauvegardés');
			}
			
		}
	
		return $app['twig']->render('joueur/admin/xp.twig', array(
				'joueur' => $joueur,
		));
	}
	
	/**
	 * Detail d'un joueur (pour les orgas)
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailOrgaAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$joueur = $app['orm.em']->find('\LarpManager\Entities\Joueur',$id);
	
		if ( $joueur )
		{
			return $app['twig']->render('joueur/admin/detail.twig', array('joueur' => $joueur));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le joueur n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('homepage'));
		}
	}
	
	/**
	 * Met à jour les informations d'un joueur
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$joueur = $app['orm.em']->find('\LarpManager\Entities\Joueur',$id);
	
		$form = $app['form.factory']->createBuilder(new JoueurForm(), $joueur)
			->add('update','submit', array('label' => "Sauvegarder"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$joueur = $form->getData();
	
			$app['orm.em']->persist($joueur);
			$app['orm.em']->flush();
			$app['session']->getFlashBag()->add('success', 'Le joueur a été mis à jour.');
	
			return $app->redirect($app['url_generator']->generate('joueur.detail', array('index'=> $id)));
		}
	
		return $app['twig']->render('joueur/update.twig', array(
				'joueur' => $joueur,
				'form' => $form->createView(),
		));
	}
}
