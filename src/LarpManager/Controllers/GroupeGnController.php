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
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

use LarpManager\Form\GroupeGn\GroupeGnForm;
use LarpManager\Form\GroupeGn\GroupeGnResponsableForm;
use LarpManager\Form\GroupeGn\GroupeGnPlaceAvailableForm;

use LarpManager\Repository\ParticipantRepository;

use LarpManager\Entities\Groupe;
use LarpManager\Entities\GroupeGn;
use LarpManager\Entities\Participant;


/**
 * LarpManager\Controllers\GroupeGnController
 *
 * @author kevin
 */
class GroupeGnController
{
	/**
	 * Liste des sessions de jeu pour un groupe
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Groupe $groupe
	 */
	public function listAction(Request $request, Application $app, Groupe $groupe)
	{
		return $app['twig']->render('admin/groupeGn/list.twig', array(
				'groupe' => $groupe,
		));
	}
	
	/**
	 * Ajout d'un groupe à un jeu
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Groupe $groupe
	 */
	public function addAction(Request $request, Application $app, Groupe $groupe)
	{
		$groupeGn = new GroupeGn();
		$groupeGn->setGroupe($groupe);
	
		// Si ce groupe a déjà une participation à un jeu, reprendre le code/jeuStrategique/jeuMaritime/placeAvailable
		if ( $groupe->getGroupeGns()->count() > 0 )
		{
			$jeu = $groupe->getGroupeGns()->last();
			$groupeGn->setCode($jeu->getCode());
			$groupeGn->setPlaceAvailable($jeu->getPlaceAvailable());
			$groupeGn->setJeuStrategique($jeu->getJeuStrategique());
			$groupeGn->setJeuMaritime($jeu->getJeuMaritime());
		}
	
		$form = $app['form.factory']->createBuilder(new GroupeGnForm(), $groupeGn)
			->add('submit','submit', array('label' => 'Enregistrer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$groupeGn = $form->getData();
			$app['orm.em']->persist($groupeGn);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'La participation au jeu a été enregistré.');
			return $app->redirect($app['url_generator']->generate('groupe.detail', array('index' => $groupe->getId())));
		}
	
		return $app['twig']->render('admin/groupeGn/add.twig', array(
				'groupe' => $groupe,
				'form' => $form->createView(),
		));
	}

	/**
	 * Modification de la participation à un jeu du groupe
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Groupe $groupe
	 * @param GroupeGn $groupeGn
	 */
	public function updateAction(Request $request, Application $app, Groupe $groupe, GroupeGn $groupeGn)
	{
		$form = $app['form.factory']->createBuilder(new GroupeGnForm(), $groupeGn)
			->add('submit','submit', array('label' => 'Enregistrer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$groupeGn = $form->getData();
			$app['orm.em']->persist($groupeGn);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'La participation au jeu a été enregistré.');
			return $app->redirect($app['url_generator']->generate('groupe.detail', array('index' => $groupe->getId())));
		}
	
		return $app['twig']->render('admin/groupeGn/update.twig', array(
				'groupe' => $groupe,
				'groupeGn' => $groupeGn,
				'form' => $form->createView(),
		));
	}

	/**
	 * Choisir le responsable
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Groupe $groupe
	 * @param GroupeGn $groupeGn
	 */
	public function responsableAction(Request $request, Application $app, Groupe $groupe, GroupeGn $groupeGn)
	{
		$form = $app['form.factory']->createBuilder(new GroupeGnResponsableForm(), $groupeGn)
			->add('responsable','entity', array(
				'label' => 'Responsable',
				'required' => false,
				'class' => 'LarpManager\Entities\Participant',
				'property' => 'user.etatCivil',
				'query_builder' => function(ParticipantRepository $er) use ($groupeGn) {
					$qb = $er->createQueryBuilder('p');
					$qb->join('p.user', 'u');
					$qb->join('p.groupeGn', 'gg');
					$qb->join('u.etatCivil', 'ec');
					$qb->orderBy('ec.nom', 'ASC');
					$qb->where('gg.id = :groupeGnId');
					$qb->setParameter('groupeGnId',$groupeGn->getId());
					return $qb;
				},
				'attr' => array(
						'class'	=> 'selectpicker',
						'data-live-search' => 'true',
						'placeholder' => 'Responsable',
				),
			))
			->add('submit','submit', array('label' => 'Enregistrer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$groupeGn = $form->getData();
			$app['orm.em']->persist($groupeGn);
			$app['orm.em']->flush();
			
			$app['notify']->newResponsable($groupeGn->getResponsable()->getUser(), $groupeGn);
				
			$app['session']->getFlashBag()->add('success', 'Le responsable du groupe a été enregistré.');
			return $app->redirect($app['url_generator']->generate('groupeGn.list', array('groupe' => $groupeGn->getGroupe()->getId())));
		}

		return $app['twig']->render('admin/groupeGn/responsable.twig', array(
				'groupe' => $groupe,
				'groupeGn' => $groupeGn,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Ajoute un participant à un groupe
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param GroupeGn $groupeGn
	 */
	public function participantAddAction(Request $request, Application $app, GroupeGn $groupeGn)
	{
		$form = $app['form.factory']->createBuilder()
			->add('participant','entity', array(
				'label' => 'Nouveau participant',
				'required' => true,
				'class' => 'LarpManager\Entities\Participant',
				'property' => 'user.etatCivil',
				'query_builder' => function(ParticipantRepository $er) use ($groupeGn) {
					$qb = $er->createQueryBuilder('p');
					$qb->join('p.user', 'u');
					$qb->join('p.gn', 'gn');
					$qb->join('u.etatCivil', 'ec');
					$qb->where($qb->expr()->isNull('p.groupeGn'));
					$qb->andWhere('gn.id = :gnId');
					$qb->setParameter('gnId',$groupeGn->getGn()->getId());
					$qb->orderBy('ec.nom', 'ASC');
					return $qb;
				},
				'attr' => array(
						'class'	=> 'selectpicker',
						'data-live-search' => 'true',
						'placeholder' => 'Participant',
				),
				))
				->add('submit','submit', array('label' => 'Enregistrer'))
				->getForm();
			
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$data['participant']->setGroupeGn($groupeGn);
			$app['orm.em']->persist($data['participant']);
			$app['orm.em']->flush();
			
			$app['notify']->newMembre($data['participant']->getUser(), $groupeGn);
			
			$app['session']->getFlashBag()->add('success', 'Le joueur a été ajouté à cette session.');
			return $app->redirect($app['url_generator']->generate('groupeGn.list', array('groupe' => $groupeGn->getGroupe()->getId())));
		}
		
		return $app['twig']->render('admin/groupeGn/participantAdd.twig', array(
				'groupeGn' => $groupeGn,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Retire un participant d'un groupe
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param GroupeGn $groupeGn
	 * @param Participant $participant
	 */
	public function participantRemoveAction(Request $request, Application $app, GroupeGn $groupeGn, Participant $participant)
	{		
		$form = $app['form.factory']->createBuilder()
			->add('submit','submit', array('label' => 'Retirer'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			// si le participant est le chef de groupe
			if ( $groupeGn->getResponsable() == $participant)
			{
				$groupeGn->setResponsableNull();
			}
			
			$participant->setGroupeGnNull();
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'Le joueur a été retiré de cette session.');
			return $app->redirect($app['url_generator']->generate('groupeGn.list', array('groupe' => $groupeGn->getGroupe()->getId())));
		}
		
		return $app['twig']->render('admin/groupeGn/participantRemove.twig', array(
				'groupeGn' => $groupeGn,
				'participant' => $participant,
				'form' => $form->createView(),
		));
	}

	/**
	 * Permet au chef de groupe de modifier le nombre de place disponible
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Groupe $groupe
	 */
	public function placeAvailableAction(Request $request, Application $app, GroupeGn $groupeGn)
	{
		$participant = $app['user']->getParticipant($groupeGn->getGn());
		
		$form = $app['form.factory']->createBuilder(new GroupeGnPlaceAvailableForm(), $groupeGn)
			->add('submit','submit', array('label' => 'Enregistrer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$groupeGn = $form->getData();
			$app['orm.em']->persist($groupeGn);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Vos modifications ont été enregistré.');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		return $app['twig']->render('public/groupeGn/placeAvailable.twig', array(
				'form' => $form->createView(),
				'groupe' => $groupeGn->getGroupe(),
				'participant' => $participant,
				'groupeGn' => $groupeGn,
		));
	}
	
	/**
	 * Détail d'un groupe
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 * @param Groupe $groupe
	 */
	public function groupeAction(Request $request, Application $app, GroupeGn $groupeGn)
	{
		$participant = $app['user']->getParticipant($groupeGn->getGn());
		
		return $app['twig']->render('public/groupe/detail.twig', array(
				'groupe' => $groupeGn->getGroupe(),
				'participant' => $participant,
				'groupeGn' => $groupeGn, 
		));
	}
	
	
}