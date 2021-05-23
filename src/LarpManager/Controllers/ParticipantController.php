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
use Doctrine\Common\Collections\ArrayCollection;

use LarpManager\Form\JoueurForm;
use LarpManager\Form\FindJoueurForm;
use LarpManager\Form\RestaurationForm;
use LarpManager\Form\JoueurXpForm;

use LarpManager\Form\RequestAllianceForm;
use LarpManager\Form\AcceptAllianceForm;
use LarpManager\Form\CancelRequestedAllianceForm;
use LarpManager\Form\RefuseAllianceForm;
use LarpManager\Form\BreakAllianceForm;
use LarpManager\Form\DeclareWarForm;
use LarpManager\Form\RequestPeaceForm;
use LarpManager\Form\AcceptPeaceForm;
use LarpManager\Form\RefusePeaceForm;
use LarpManager\Form\CancelRequestedPeaceForm;

use LarpManager\Form\ParticipantPersonnageSecondaireForm;
use LarpManager\Form\GroupeInscriptionForm;
use LarpManager\Form\GroupeSecondairePostulerForm;

use LarpManager\Form\ParticipantBilletForm;
use LarpManager\Form\ParticipantRestaurationForm;
use LarpManager\Form\Participant\ParticipantNewForm;
use LarpManager\Form\Participant\ParticipantGroupeForm;
use LarpManager\Form\Participant\ParticipantRemoveForm;

use LarpManager\Form\Personnage\PersonnageForm;
use LarpManager\Form\Personnage\PersonnageEditForm;
use LarpManager\Form\Personnage\PersonnageReligionForm;
use LarpManager\Form\Personnage\PersonnageOriginForm;

use LarpManager\Form\TrombineForm;
use LarpManager\Form\MessageForm;

use LarpManager\Entities\User;
use LarpManager\Entities\Participant;
use LarpManager\Entities\ParticipantHasRestauration;
use LarpManager\Entities\Personnage;
use LarpManager\Entities\Message;
use LarpManager\Entities\SecondaryGroup;
use LarpManager\Entities\Postulant;
use LarpManager\Entities\Priere;
use LarpManager\Entities\Potion;
use LarpManager\Entities\Sort;
use LarpManager\Entities\Competence;
use LarpManager\Entities\Groupe;
use LarpManager\Entities\Rule;
use LarpManager\Entities\Question;
use LarpManager\Entities\Reponse;


/**
 * LarpManager\Controllers\ParticipantController
 *
 * @author kevin
 *
 */
class ParticipantController
{
	/**
	 * Interface Joueur d'un jeu
	 *
	 * @param Application $app
	 * @param Request $request
	 * @param Gn $gn
	 */
	public function indexAction(Application $app, Request $request, Participant $participant)
	{
		$groupeGn = $participant->getSession();
		
		// liste des questions non répondu par le participant
		$repo = $app['orm.em']->getRepository('LarpManager\Entities\Question');
		$questions = $repo->findByParticipant($participant);
		
		return $app['twig']->render('public/participant/index.twig', array(
				'gn' => $participant->getGn(),
				'participant' => $participant,
				'groupeGn' => $groupeGn,
				'questions' => $questions,
		));
	}
	
	/**
	 * Apporter une réponse à une question
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @param Participant $participant
	 * @param Question $question
	 * @param unknown $reponse
	 */
	public function reponseAction(Application $app, Request $request, Participant $participant, Question $question, $reponse)
	{		
		$rep = new Reponse();
		$rep->setQuestion($question);
		$rep->setParticipant($participant);
		$rep->setReponse($reponse);
		
		$app['orm.em']->persist($rep);
		$app['orm.em']->flush();
		
		$app['session']->getFlashBag()->add('Success','Votre réponse a été prise en compte !');
		return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		
	}
	
	/**
	 * Supprimer une réponse à une question
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @param Participant $participant
	 * @param Reponse $reponse
	 */
	public function reponseDeleteAction(Application $app, Request $request, Participant $participant, Reponse $reponse)
	{		
		$app['orm.em']->remove($reponse);
		$app['orm.em']->flush();
		
		$app['session']->getFlashBag()->add('Success','Votre réponse a été supprimée, veuillez répondre de nouveau à la question !');
		return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
	}
	
	/**
	 * Création d'un nouveau participant
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @param User $user
	 */
	public function newAction(Application $app, Request $request, User $user)
	{
		$participant = new Participant();
		$participant->setUser($user);
		
		$form = $app['form.factory']->createBuilder(new ParticipantNewForm(), $participant)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$participant = $form->getData();
						
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Vos modifications ont été enregistré.');
			return $app->redirect($app['url_generator']->generate('user.admin.list', array()),301);
		}
		
		return $app['twig']->render('admin/participant/new.twig', array(
				'participant' => $participant,
				'user' => $user,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Affecte un participant à un groupe
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @param Participant $participant
	 */
	public function groupeAction(Application $app, Request $request, Participant $participant)
	{
		$form = $app['form.factory']->createBuilder(new ParticipantGroupeForm(), $participant, array('gnId' => $participant->getGn()->getId()))
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$participant = $form->getData();
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'Vos modifications ont été enregistré.');
			return $app->redirect($app['url_generator']->generate('gn.participants', array('gn' => $participant->getGn()->getId())),301);
		}
		
		return $app['twig']->render('admin/participant/groupe.twig', array(
				'participant' => $participant,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Retire la participation de l'utilisateur à un jeu
	 * 
	 * @param Application $app
	 * @param Request $request
	 * @param Participant $participant
	 */
	public function removeAction(Application $app, Request $request, Participant $participant)
	{
		$form = $app['form.factory']->createBuilder(new ParticipantRemoveForm(), $participant, array('gnId' => $participant->getGn()->getId()))
			->add('save','submit', array('label' => 'Oui, retirer la participation de cet utilisateur'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$participant = $form->getData();
			
			// si l'utilisateur est responsable d'un ou de plusieurs groupes, il faut mettre à jour ces groupes
			if ( $participant->getGroupeGns() )
			{
				$groupeGns = $participant->getGroupeGns();
				foreach ( $groupeGns as $groupeGn)
				{
					$groupeGn->setResponsableNull();
					$app['orm.em']->persist($groupeGn);
				}
				
			}
						
			// on retire toutes les restauration liés à cet utilisateur.
			if ( $participant->getParticipantHasRestaurations() )
			{
				$participantHasRestaurations = $participant->getParticipantHasRestaurations();
				foreach ( $participantHasRestaurations as $restauration)
				{
					$app['orm.em']->remove($restauration);
				}
			}
			
			// on supprime aussi les réponses aux sondages
			if ( $participant->getReponses() )
			{
				foreach ( $participant->getReponses() as $reponse)
				{
					$app['orm.em']->remove($reponse);
				}
			}
			
			$app['orm.em']->remove($participant);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'Vos modifications ont été enregistré.');
			return $app->redirect($app['url_generator']->generate('gn.participants', array('gn' => $participant->getGn()->getId())),301);
		}
		
		return $app['twig']->render('admin/participant/remove.twig', array(
				'participant' => $participant,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Ajout d'un billet à un utilisateur. L'utilisateur doit participer au même jeu que celui du billet qui lui est affecté
	 *
	 * @param Application $app
	 * @param Request $request
	 * @param User $user
	 */
	public function billetAction(Application $app, Request $request, Participant $participant)
	{
		$form = $app['form.factory']->createBuilder(new ParticipantBilletForm(), $participant)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$participant = $form->getData();
			$participant->setBilletDate(new \Datetime('NOW'));
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
				
			$app['notify']->newBillet($participant->getUser(), $participant->getBillet());
			
			$app['session']->getFlashBag()->add('success', 'Vos modifications ont été enregistré.');
			return $app->redirect($app['url_generator']->generate('gn.participants', array('gn' => $participant->getGn()->getId())),301);
		}
		
		return $app['twig']->render('admin/participant/billet.twig', array(
				'participant' => $participant,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Choix du lieu de restauration d'un utilisateur
	 *
	 * @param Application $app
	 * @param Request $request
	 * @param User $user
	 */
	public function restaurationAction(Application $app, Request $request, Participant $participant)
	{
		$originalParticipantHasRestaurations = new ArrayCollection();
		
		/**
		 *  Crée un tableau contenant les objets ParticipantHasRestauration du participant
		 */
		foreach ($participant->getParticipantHasRestaurations() as $participantHasRestauration)
		{
			$originalParticipantHasRestaurations->add($participantHasRestauration);
		}
		
		$form = $app['form.factory']->createBuilder(new ParticipantRestaurationForm(),  $participant)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$participant = $form->getData();
			
			/**
			 * Pour toutes les restaurations du participant
			 */
			foreach ($participant->getParticipantHasRestaurations() as $participantHasRestauration)
			{
				$participantHasRestauration->setParticipant($participant);
			}
			
			/**
			 *  supprime la relation entre participantHasRestauration et le participant
			 */
			foreach ($originalParticipantHasRestaurations as $participantHasRestauration) {
				if ($participant->getParticipantHasRestaurations()->contains($participantHasRestauration) == false) {
					$app['orm.em']->remove($participantHasRestauration);
				}
			}

			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Vos modifications ont été enregistrés.');
			return $app->redirect($app['url_generator']->generate('gn.participants', array('gn' => $participant->getGn()->getId())),301);
		}
		
		return $app['twig']->render('admin/participant/restauration.twig', array(
				'participant' => $participant,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Affiche le détail d'un personnage
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	/*public function personnageAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error','Vous n\'avez pas encore de personnage.');
			return $app->redirect($app['url_generator']->generate('participant.index', array('participant' => $participant->getId())),301);
		}

		$lois = $app['orm.em']->getRepository('LarpManager\Entities\Loi')->findAll();
		
		return $app['twig']->render('public/personnage/detail.twig', array(
				'personnage' => $personnage,
				'participant' => $participant,
				'lois' => $lois
		));
	}*/
	
	/**
	 * Fourni la page détaillant les relations entre les fiefs
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 */
	public function politiqueAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error','Vous n\'avez pas encore de personnage.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
		
		if ( ! $personnage->hasCompetence('Politique') )
		{
			$app['session']->getFlashBag()->add('error','Votre personnage ne dispose pas de la compétence Politique');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
				
		// recherche de tous les groupes participant au prochain GN
		$gn = $app['larp.manager']->getGnActif();
		$groupeGns = $gn->getGroupeGns();
		$groupes = new ArrayCollection();
		foreach ( $groupeGns as $groupeGn)
		{
			$groupe = $groupeGn->getGroupe();
			//if ( $groupe->getTerritoires()->count() > 0 )
			//{
				$groupes[] = $groupe;
			//}
			
		}
						
		return $app['twig']->render('public/personnage/politique.twig', array(
				'personnage' => $personnage,
				'participant' => $participant,
				'groupes' => $groupes,
		));
		
	}
	
	/**
	 * Modification de quelques informations concernant le personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 */
	public function personnageEditAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error','Vous n\'avez pas encore de personnage.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageEditForm(), $personnage)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Vos modifications ont été prises en compte.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
		
		return $app['twig']->render('public/personnage/edit.twig', array(
			'form' => $form->createView(),
			'personnage' => $personnage,
			'participant' => $participant,
		));
	}
	
	/**
	 * Modification de la photo lié à un personnage
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function personnageTrombineAction(Request $request, Application $app, Participant $participant, Personnage $personnage)
	{
		$form = $app['form.factory']->createBuilder(new TrombineForm(), array())
			->add('envoyer','submit', array('label' => 'Envoyer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$files = $request->files->get($form->getName());
	
			$path = __DIR__.'/../../../private/img/';
			$filename = $files['trombine']->getClientOriginalName();
			$extension = $files['trombine']->guessExtension();
	
			if (!$extension || ! in_array($extension, array('png', 'jpg', 'jpeg','bmp'))) {
				$app['session']->getFlashBag()->add('error','Désolé, votre image ne semble pas valide (vérifiez le format de votre image)');
				return $app->redirect($app['url_generator']->generate('participant.personnage.trombine', array('participant' => $participant->getId(), 'personnage' => $personnage->getId())),301);
			}
	
			$trombineFilename = hash('md5',$app['user']->getUsername().$filename . time()).'.'.$extension;
	
			$image = $app['imagine']->open($files['trombine']->getPathname());
			$image->resize($image->getSize()->widen( 160 ));
			$image->save($path. $trombineFilename);
	
			$personnage->setTrombineUrl($trombineFilename);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Votre photo a été enregistrée');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('public/personnage/trombine.twig', array(
				'participant' => $participant,
				'personnage' => $personnage,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Retire un personnage à un participant
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 * @param Personnage $personnage
	 */
	public function personnageRemoveAction(Request $request, Application $app, Participant $participant, Personnage $personnage)
	{
		$groupeGn = $participant->getGroupeGn();
		$groupe = $groupeGn->getGroupe();
		
		$form = $app['form.factory']->createBuilder()
			->add('save','submit', array('label' => 'Valider'))
			->getForm();
			
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$participant->setPersonnageNull();
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();

			$app['session']->getFlashBag()->add('success','Votre modification a été enregistrée');
			return $app->redirect($app['url_generator']->generate('groupe.detail', array('index' => $groupe->getId())));
		}
		
		return $app['twig']->render('admin/participant/personnage_remove.twig', array(
				'form' => $form->createView(),
				'groupe' => $groupe,
				'participant' => $participant,
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Reprendre un ancien personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 */
	public function personnageOldAction(Request $request, Application $app, Participant $participant)
	{
		$groupeGn = $participant->getGroupeGn();
		
		if ( ! $groupeGn )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous devez rejoindre un groupe avant de pouvoir créer votre personnage.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
		
		if ( ! $participant->getBillet() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous devez avoir un billet avant de pouvoir créer votre personnage.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $groupeGn->getGn()->getId())),301);
		}
		
		if ( $groupeGn->getGroupe()->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Désolé, ce groupe est fermé. La création de personnage est temporairement désactivé.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $groupeGn->getGn()->getId())),301);
		}
		
		if ( $participant->getPersonnage() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous disposez déjà d\'un personnage.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $groupeGn->getGn()->getId())),301);
		}
		
		$groupe = $groupeGn->getGroupe();
		$gn = $groupeGn->getGn();
		
		$default = $participant->getUser()->getPersonnages()->toArray()[0];
		$lastPersonnage = $participant->getUser()->getLastPersonnage();
		if($lastPersonnage != null) {
		    $default = $lastPersonnage;
		}
		error_log($default->getNom());
				
		$form = $app['form.factory']->createBuilder()
			->add('personnage','entity', array(
					'label' =>  'Choisissez votre personnage',
					'property' => 'resumeParticipations',
					'class' => 'LarpManager\Entities\Personnage',
					'choices' => array_unique($participant->getUser()->getPersonnages()->toArray()),
			        'data' => $default
			))
			->add('save','submit', array('label' => 'Valider'))
			->getForm();
			
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$participant->setPersonnage($data['personnage']);
			
			$territoire = $groupe->getTerritoire();
			if ( $territoire )
			{
				$langue = $territoire->getLangue();
				if ( $langue )
				{
					if ( ! $data['personnage']->isKnownLanguage($langue) )
					{
						$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
						$personnageLangue->setPersonnage($data['personnage']);
						$personnageLangue->setLangue($langue);
						$personnageLangue->setSource('GROUPE');
						$app['orm.em']->persist($personnageLangue);
					}
				}
			}
			
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $groupeGn->getGn()->getId())),301);
		}
		
		return $app['twig']->render('public/participant/personnage_old.twig', array(
				'form' => $form->createView(),
				'groupe' => $groupe,
				'participant' => $participant
		));
	}

	/**
	 * Reprendre un ancien personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 */
	public function adminPersonnageOldAction(Request $request, Application $app, Participant $participant)
	{
		$groupeGn = $participant->getGroupeGn();
		$groupe = $groupeGn->getGroupe();
		$gn = $groupeGn->getGn();
				
		$form = $app['form.factory']->createBuilder()
			->add('personnage','entity', array(
					'label' =>  'Choisissez le personnage',
					'property' => 'nom',
					'class' => 'LarpManager\Entities\Personnage',
					'choices' => array_unique($participant->getUser()->getPersonnages()->toArray()),
			))
			->add('save','submit', array('label' => 'Valider'))
			->getForm();
			
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$participant->setPersonnage($data['personnage']);
			
			$territoire = $groupe->getTerritoire();
			if ( $territoire )
			{
				$langue = $territoire->getLangue();
				if ( $langue )
				{
					if ( ! $data['personnage']->isKnownLanguage($langue) )
					{
						$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
						$personnageLangue->setPersonnage($data['personnage']);
						$personnageLangue->setLangue($langue);
						$personnageLangue->setSource('GROUPE');
						$app['orm.em']->persist($personnageLangue);
					}
				}
			}
			
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('gn.participants.withoutperso', array('gn' => $gn->getId())),301);
		}
		
		return $app['twig']->render('admin/participant/personnage_old.twig', array(
				'form' => $form->createView(),
				'groupe' => $groupe,
				'participant' => $participant,
		));
	}

	/**
	 * Création d'un nouveau personnage. L'utilisateur doit être dans un groupe et son billet doit être valide
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function personnageNewAction(Request $request, Application $app, Participant $participant)
	{
		$groupeGn = $participant->getGroupeGn();
		
		if ( ! $groupeGn )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous devez rejoindre un groupe avant de pouvoir créer votre personnage.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' =>  $participant->getGn()->getId())),301);
		}
		
		if ( ! $participant->getBillet() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous devez avoir un billet avant de pouvoir créer votre personnage.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $groupeGn->getGn()->getId())),301);
		}

		if ( $groupeGn->getGroupe()->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Désolé, ce groupe est fermé. La création de personnage est temporairement désactivé.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $groupeGn->getGn()->getId())),301);
		}
	
		if ( $participant->getPersonnage() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous disposez déjà d\'un personnage.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $groupeGn->getGn()->getId())),301);
		}
		
		$groupe = $groupeGn->getGroupe();
		
		/*if (  ! $groupe->hasEnoughClasse($groupeGn->getGn()) )
		{
			$app['session']->getFlashBag()->add('error','Désolé, ce groupe ne contient plus de classes disponibles');
			return $app->redirect($app['url_generator']->generate('participant.index', array('participant' => $participant->getId())),301);
		}*/
	
		$personnage = new \LarpManager\Entities\Personnage();
		$classes = $app['orm.em']->getRepository('\LarpManager\Entities\Classe')->findAllCreation();
	
		// j'ajoute içi certain champs du formulaires (les classes)
		// car j'ai besoin des informations du groupe pour les alimenter
		$form = $app['form.factory']->createBuilder(new PersonnageForm(), $personnage)
			->add('classe','entity', array(
				'label' =>  'Classes disponibles',
				'property' => 'label',
				'class' => 'LarpManager\Entities\Classe',
				'choices' => array_unique($classes),
			))
			->add('save','submit', array('label' => 'Valider mon personnage'))
			->getForm();
			
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			$personnage->setUser($app['user']);
			$participant->setPersonnage($personnage);
	
			// Ajout des points d'expérience gagné à la création d'un personnage
			$personnage->setXp($participant->getGn()->getXpCreation());

			// Set basic age
			$age = $personnage->getAge()->getMinimumValue();
			$age += rand(0, 4);
			$personnage->setAgeReel($age);
				
			// historique
			$historique = new \LarpManager\Entities\ExperienceGain();
			$historique->setExplanation("Création de votre personnage");
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setPersonnage($personnage);
			$historique->setXpGain($participant->getGn()->getXpCreation());
			$app['orm.em']->persist($historique);
					
			// ajout des compétences acquises à la création
			foreach ($personnage->getClasse()->getCompetenceFamilyCreations() as $competenceFamily)
			{
				$firstCompetence = $competenceFamily->getFirstCompetence();
				if ( $firstCompetence )
				{
					$personnage->addCompetence($firstCompetence);
					$firstCompetence->addPersonnage($personnage);
					$app['orm.em']->persist($firstCompetence);
				}
				
				if ( $competenceFamily->getLabel() == "Noblesse")
				{
					$personnage->addRenomme(2);
					$renomme_history = new \LarpManager\Entities\RenommeHistory();
						
					$renomme_history->setRenomme(2);
					$renomme_history->setExplication('Compétence Noblesse niveau 1');
					$renomme_history->setPersonnage($personnage);
					$app['orm.em']->persist($renomme_history);
				}
			}
	
			// Ajout des points d'expérience gagné grace à l'age
			$xpAgeBonus = $personnage->getAge()->getBonus();
			if ( $xpAgeBonus )
			{
				$personnage->addXp($xpAgeBonus);
				$historique = new \LarpManager\Entities\ExperienceGain();
				$historique->setExplanation("Bonus lié à l'age");
				$historique->setOperationDate(new \Datetime('NOW'));
				$historique->setPersonnage($personnage);
				$historique->setXpGain($xpAgeBonus);
				$app['orm.em']->persist($historique);
			}
	
			// Ajout des langues en fonction de l'origine du personnage
			$langue = $personnage->getOrigine()->getLangue();
			if ( $langue )
			{
				$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
				$personnageLangue->setPersonnage($personnage);
				$personnageLangue->setLangue($langue);
				$personnageLangue->setSource('ORIGINE');
				$app['orm.em']->persist($personnageLangue);
			}
			
			// Ajout des langues secondaires lié à l'origine du personnage
			foreach ( $personnage->getOrigine()->getLangues() as $langue) 
			{
				if ( ! $personnage->isKnownLanguage($langue) )
				{
					$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
					$personnageLangue->setPersonnage($personnage);
					$personnageLangue->setLangue($langue);
					$personnageLangue->setSource('ORIGINE');
					$app['orm.em']->persist($personnageLangue);
				}
			}
			
			// Ajout de la langue du groupe
			$territoire = $groupe->getTerritoire();
			if ( $territoire )
			{
				$langue = $territoire->getLangue();
				if ( $langue )
				{
					if ( ! $personnage->isKnownLanguage($langue) )
					{
						$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
						$personnageLangue->setPersonnage($personnage);
						$personnageLangue->setLangue($langue);
						$personnageLangue->setSource('GROUPE');
						$app['orm.em']->persist($personnageLangue);
					}
				}
			}
	
			$app['orm.em']->persist($personnage);
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
	
	
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $groupeGn->getGn()->getId())),301);
		}
	
		$ages = $app['orm.em']->getRepository('LarpManager\Entities\Age')->findAllOnCreation();
		$territoires = $app['orm.em']->getRepository('LarpManager\Entities\Territoire')->findRoot();
		
		return $app['twig']->render('public/participant/personnage_new.twig', array(
				'form' => $form->createView(),
				'classes' => array_unique($classes),
				'groupe' => $groupe,
				'participant' => $participant,
				'ages' => $ages,
				'territoires' => $territoires,
		));
	}
	
	/**
	 * Création d'un nouveau personnage. L'utilisateur doit être dans un groupe et son billet doit être valide
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminPersonnageNewAction(Request $request, Application $app, Participant $participant)
	{
		$groupeGn = $participant->getGroupeGn();	
		$groupe = $groupeGn->getGroupe();
		
		$personnage = new \LarpManager\Entities\Personnage();
		$classes = $app['orm.em']->getRepository('\LarpManager\Entities\Classe')->findAllCreation();
	
		// j'ajoute içi certain champs du formulaires (les classes)
		// car j'ai besoin des informations du groupe pour les alimenter
		$form = $app['form.factory']->createBuilder(new PersonnageForm(), $personnage)
			->add('classe','entity', array(
				'label' =>  'Classes disponibles',
				'property' => 'label',
				'class' => 'LarpManager\Entities\Classe',
				'choices' => array_unique($classes),
			))
		->add('save','submit', array('label' => 'Valider le personnage'))
		->getForm();
			
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
				
			$personnage->setUser($app['user']);
			$participant->setPersonnage($personnage);
	
			// Ajout des points d'expérience gagné à la création d'un personnage
			$personnage->setXp($participant->getGn()->getXpCreation());
	
			// historique
			$historique = new \LarpManager\Entities\ExperienceGain();
			$historique->setExplanation("Création de votre personnage");
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setPersonnage($personnage);
			$historique->setXpGain($participant->getGn()->getXpCreation());
			$app['orm.em']->persist($historique);
				
			// ajout des compétences acquises à la création
			foreach ($personnage->getClasse()->getCompetenceFamilyCreations() as $competenceFamily)
			{
				$firstCompetence = $competenceFamily->getFirstCompetence();
				if ( $firstCompetence )
				{
					$personnage->addCompetence($firstCompetence);
					$firstCompetence->addPersonnage($personnage);
					$app['orm.em']->persist($firstCompetence);
				}
	
				if ( $competenceFamily->getLabel() == "Noblesse")
				{
					$personnage->addRenomme(2);
					$renomme_history = new \LarpManager\Entities\RenommeHistory();
	
					$renomme_history->setRenomme(2);
					$renomme_history->setExplication('Compétence Noblesse niveau 1');
					$renomme_history->setPersonnage($personnage);
					$app['orm.em']->persist($renomme_history);
				}
			}
	
			// Ajout des points d'expérience gagné grace à l'age
			$xpAgeBonus = $personnage->getAge()->getBonus();
			if ( $xpAgeBonus )
			{
				$personnage->addXp($xpAgeBonus);
				$historique = new \LarpManager\Entities\ExperienceGain();
				$historique->setExplanation("Bonus lié à l'age");
				$historique->setOperationDate(new \Datetime('NOW'));
				$historique->setPersonnage($personnage);
				$historique->setXpGain($xpAgeBonus);
				$app['orm.em']->persist($historique);
			}
	
			// Ajout des langues en fonction de l'origine du personnage
			$langue = $personnage->getOrigine()->getLangue();
			if ( $langue )
			{
				$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
				$personnageLangue->setPersonnage($personnage);
				$personnageLangue->setLangue($langue);
				$personnageLangue->setSource('ORIGINE');
				$app['orm.em']->persist($personnageLangue);
			}
				
			// Ajout des langues secondaires lié à l'origine du personnage
			foreach ( $personnage->getOrigine()->getLangues() as $langue)
			{
				if ( ! $personnage->isKnownLanguage($langue) )
				{
					$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
					$personnageLangue->setPersonnage($personnage);
					$personnageLangue->setLangue($langue);
					$personnageLangue->setSource('ORIGINE');
					$app['orm.em']->persist($personnageLangue);
				}
			}
				
			// Ajout de la langue du groupe
			$territoire = $groupe->getTerritoire();
			if ( $territoire )
			{
				$langue = $territoire->getLangue();
				if ( $langue )
				{
					if ( ! $personnage->isKnownLanguage($langue) )
					{
						$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
						$personnageLangue->setPersonnage($personnage);
						$personnageLangue->setLangue($langue);
						$personnageLangue->setSource('GROUPE');
						$app['orm.em']->persist($personnageLangue);
					}
				}
			}
	
			$app['orm.em']->persist($personnage);
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
	
	
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('gn.participants.withoutperso', array('gn' => $groupeGn->getGn()->getId())),301);
		}
	
		$ages = $app['orm.em']->getRepository('LarpManager\Entities\Age')->findAllOnCreation();
		$territoires = $app['orm.em']->getRepository('LarpManager\Entities\Territoire')->findRoot();
	
		return $app['twig']->render('admin/participant/personnage_new.twig', array(
				'form' => $form->createView(),
				'classes' => array_unique($classes),
				'groupe' => $groupe,
				'participant' => $participant,
				'ages' => $ages,
				'territoires' => $territoires,
		));
	}
	
	/**
	 * Page listant les règles à télécharger
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function regleListAction(Request $request, Application $app, Participant $participant)
	{
		$regles = $app['orm.em']->getRepository('LarpManager\Entities\Rule')->findAll();
	
		return $app['twig']->render('public/rule/list.twig', array(
				'regles' => $regles,
				'participant' => $participant,
		));
	}
	
	/**
	 * Détail d'une règle
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 * @param Rule $regle
	 */
	public function regleDetailAction(Request $request, Application $app, Participant $participant, Rule $rule)
	{
		return $app['twig']->render('public/rule/detail.twig', array(
				'regle' => $rule,
				'participant' => $participant,
		));
	}	
	
	/**
	 * Télécharger une règle
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 * @param Rule rule
	 */
	public function regleDocumentAction(Request $request, Application $app, Participant $participant, Rule $rule)
	{
		$filename = __DIR__.'/../../../private/rules/'.$rule->getUrl();
		return $app->sendFile($filename);
	}
	
	/**
	 * Rejoindre un groupe
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function groupeJoinAction(Request $request, Application $app, Participant $participant)
	{
		// il faut un billet pour rejoindre un groupe
		if ( ! $participant->getBillet() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous devez obtenir un billet avant de pouvoir rejoindre un groupe');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
				
		$form = $app['form.factory']->createBuilder(new GroupeInscriptionForm(), array())
			->add('subscribe','submit', array('label' => 'S\'inscrire'))
			->getForm();
	
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
				$data = $form->getData();
				$code = $data['code'];
				$groupeGn = $app['orm.em']->getRepository('\LarpManager\Entities\GroupeGn')->findOneByCode($code);
				$groupe = $groupeGn->getGroupe();
				
				if ( ! $groupe )
				{
					$app['session']->getFlashBag()->add('error','Désolé, le code que vous utilisez ne correspond à aucun groupe');
					return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
				}
				
				$groupeGn = $groupe->getGroupeGn($participant->getGn());
				if ( ! $groupeGn )
				{
					$app['session']->getFlashBag()->add('error','Le code correspond à un groupe qui ne participe pas à cette session de jeu.');
					return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
				}
				
				// il faut que le groupe ai un responsable pour le rejoindre
				if ( ! $groupeGn->getResponsable() )
				{
					$app['session']->getFlashBag()->add('error','Le groupe n\'a pas encore de responsable, vous ne pouvez pas le rejoindre pour le moment.');
					return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
				}
					
				$participant->setGroupeGn($groupeGn);
				$app['orm.em']->persist($participant);
				$app['orm.em']->flush();
					
				// envoyer une notification au chef de groupe et au scénariste
				$app['notify']->joinGroupe($participant, $groupeGn);
						
				$app['session']->getFlashBag()->add('success','Vous avez rejoint le groupe.');
				return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);				
		}
		
		return $app['twig']->render('public/groupe/join.twig', array(
				'form' => $form->createView(),
				'participant' => $participant,
		));
	}
	
	
	/**
	 * Choix du personnage secondaire par un utilisateur
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function personnageSecondaireAction(Request $request, Application $app, Participant $participant)
	{	
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\PersonnageSecondaire');
		$personnageSecondaires = $repo->findAll();
	
		$form = $app['form.factory']->createBuilder(new ParticipantPersonnageSecondaireForm(), $participant)
			->add('choice','submit', array('label' => 'Enregistrer'))
			->getForm();
			
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$participant = $form->getData();
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Le personnage secondaire a été enregistré.');
						
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGroupeGn()->getGn()->getId())),301);
		}
	
		return $app['twig']->render('public/participant/personnageSecondaire.twig', array(
				'participant' => $participant,
				'personnageSecondaires' => $personnageSecondaires,
				'form' => $form->createView(),
		));
			
	}
	
	/**
	 * Liste des background pour le joueur
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function backgroundAction(Request $request, Application $app, Participant $participant)
	{
		// l'utilisateur doit avoir un personnage
		$personnage = $participant->getPersonnage();
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error','Désolé, Vous devez faire votre personnage pour pouvoir consulter votre background.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		$backsGroupe = new ArrayCollection();
		$backsJoueur = new ArrayCollection();
	
		// recherche les backgrounds liés au personnage (visibilité == OWNER)
		$backsJoueur = $personnage->getBackgrounds('OWNER');
	
		// recherche les backgrounds liés au groupe (visibilité == PUBLIC)
		$backsGroupe = new ArrayCollection(array_merge(
			$participant->getGroupeGn()->getGroupe()->getBacks('PUBLIC')->toArray(),
			$backsGroupe->toArray()
		));
	
		// recherche les backgrounds liés au groupe (visibilité == GROUP_MEMBER)
		$backsGroupe = new ArrayCollection(array_merge(
			$participant->getGroupeGn()->getGroupe()->getBacks('GROUPE_MEMBER')->toArray(),
			$backsGroupe->toArray()
		));
	
		// recherche les backgrounds liés au groupe (visibilité == GROUP_OWNER)
		if ( $app['user'] == $participant->getGroupeGn()->getGroupe()->getUserRelatedByResponsableId() )
		{
			$backsGroupe = new ArrayCollection(array_merge(
					$participant->getGroupeGn()->getGroupe()->getBacks('GROUPE_OWNER')->toArray(),
					$backsGroupe->toArray()
					));
		}
	
		return $app['twig']->render('public/participant/background.twig', array(
				'backsJoueur' => $backsJoueur,
				'backsGroupe' => $backsGroupe,
				'personnage' => $personnage,
				'participant' => $participant,
		));
	}
	
	/**
	 * Mise à jour de l'origine d'un personnage.
	 * Impossible si le personnage dispose déjà d'une origine.
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function origineAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous devez créer un personnage avant de choisir son origine.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
		
		if ( $personnage->getGroupe()->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'est plus possible de modifier ce personnage. Le groupe est verouillé. Contacter votre scénariste si vous pensez que cela est une erreur');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		if ( $personnage->getTerritoire() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'est pas possible de modifier votre origine. Veuillez contacter votre orga pour exposer votre problème.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		$form = $app['form.factory']->createBuilder(new PersonnageOriginForm(), $personnage)
			->add('save','submit', array('label' => 'Valider votre origine'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('public/participant/origine.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'participant' => $participant,
		));
	}

	/**
	 * Liste des religions
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function religionListAction(Request $request, Application $app, Participant $participant)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Religion');
		$religions = $repo->findAllOrderedByLabel();
	
		return $app['twig']->render('public/participant/religion.twig', array(
				'religions' => $religions,
				'participant' => $participant,
		));
	}

	/**
	 * Ajoute une religion au personnage
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function religionAddAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage avant de choisir une religion !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
				
		if ( $participant->getGroupeGn()->getGroupe()->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'est plus possible de modifier ce personnage. Le groupe est verouillé. Contacter votre scénariste si vous pensez que cela est une erreur');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
	
		// refuser la demande si le personnage est Fanatique
		if ( $personnage->isFanatique() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous êtes un Fanatique, il vous est impossible de choisir une nouvelle religion. Veuillez contacter votre orga en cas de problème.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		$personnageReligion = new \LarpManager\Entities\PersonnagesReligions();
		$personnageReligion->setPersonnage($personnage);
	
		// ne proposer que les religions que le personnage ne pratique pas déjà ...
		$availableReligions = $app['personnage.manager']->getAvailableReligions($personnage);
	
		if ( $availableReligions->count() == 0 )
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'y a plus de religion disponibles ( Sérieusement ? vous êtes éclectique, c\'est bien, mais ... faudrait savoir ce que vous voulez non ? L\'heure n\'est-il pas venu de faire un choix parmi tous ces dieux ?)');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		// construit le tableau de choix
		$choices = array();
		foreach ( $availableReligions as $religion)
		{
			$choices[] = $religion;
		}
	
		$form = $app['form.factory']->createBuilder(new PersonnageReligionForm(), $personnageReligion)
			->add('religion','entity', array(
					'required' => true,
					'label' => 'Votre religion',
					'class' => 'LarpManager\Entities\Religion',
					'choices' => $availableReligions,
					'property' => 'label',
			))
			->add('save','submit', array('label' => 'Valider votre religion'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$personnageReligion = $form->getData();
				
			// supprimer toutes les autres religions si l'utilisateur à choisi fanatique
			// n'autoriser que un Fervent que si l'utilisateur n'a pas encore Fervent.
			if ( $personnageReligion->getReligionLevel()->getIndex() == 3 )
			{
				$personnagesReligions = $personnage->getPersonnagesReligions();
				foreach ( $personnagesReligions as $oldReligion)
				{
					$app['orm.em']->remove($oldReligion);
				}
			}
			else if ( $personnageReligion->getReligionLevel()->getIndex() == 2 )
			{
				if ( $personnage->isFervent() )
				{
					$app['session']->getFlashBag()->add('error','Désolé, vous êtes déjà Fervent d\'une autre religion, il vous est impossible de choisir une nouvelle religion en tant que Fervent. Veuillez contacter votre orga en cas de problème.');
					return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
				}
			}
	
			$app['orm.em']->persist($personnageReligion);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('public/personnage/religion_add.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'participant' => $participant,
				'religions' => $availableReligions,
		));
	}
	
	/**
	 * Detail d'une priere
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 * @param Priere $priere
	 */
	public function priereDetailAction(Request $request, Application $app, Participant $participant, Priere $priere)
	{
		$personnage = $participant->getPersonnage();
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
		
		if ( ! $personnage->isKnownPriere($priere) )
		{
			$app['session']->getFlashBag()->add('error', 'Vous ne connaissez pas cette prière !');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
		
		return $app['twig']->render('public/priere/detail.twig', array(
				'priere' => $priere,
				'participant' => $participant,
				
		));
	}
	
	/**
	 * Obtenir le document lié à une priere
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 * @param Priere $priere
	 */
	public function priereDocumentAction(Request $request, Application $app, Participant $participant, Priere $priere)
	{
		$personnage = $participant->getPersonnage();
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
		
		if ( ! $personnage->isKnownPriere($priere) )
		{
			$app['session']->getFlashBag()->add('error', 'Vous ne connaissez pas cette prière !');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
			
		$file = __DIR__.'/../../../private/doc/'.$priere->getDocumentUrl();
		return $app->sendFile($file);
	}
	
	
	/**
	 * Detail d'une potion
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 * @param Potion $potion
	 */
	public function potionDetailAction(Request $request, Application $app, Participant $participant, Potion $potion)
	{
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		if ( ! $personnage->isKnownPotion($potion) )
		{
			$app['session']->getFlashBag()->add('error', 'Vous ne connaissez pas cette potion !');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('public/potion/detail.twig', array(
				'potion' => $potion,
				'participant' => $participant,
	
		));
	}
	
	/**
	 * Obtenir le document lié à une potion
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 * @param Potion $potion
	 */
	public function potionDocumentAction(Request $request, Application $app, Participant $participant, Potion $potion)
	{
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		if ( ! $personnage->isKnownPotion($potion) )
		{
			$app['session']->getFlashBag()->add('error', 'Vous ne connaissez pas cette potion !');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
			
		$file = __DIR__.'/../../../private/doc/'.$potion->getDocumentUrl();
		return $app->sendFile($file);
	}

	/**
	 * Choix d'une nouvelle potion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
		
		$niveau = $request->get('niveau');
	
		if ( ! $personnage->hasTrigger('ALCHIMIE APPRENTI')
				&& ! $personnage->hasTrigger('ALCHIMIE INITIE')
				&& ! $personnage->hasTrigger('ALCHIMIE EXPERT')
				&& ! $personnage->hasTrigger('ALCHIMIE MAITRE') )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous ne pouvez pas choisir de potions supplémentaires.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Potion');
		$potions = $repo->findByNiveau($niveau);
	
		$form = $app['form.factory']->createBuilder()
		->add('potions','entity', array(
				'required' => true,
				'label' => 'Choisissez votre potion',
				'multiple' => false,
				'expanded' => true,
				'class' => 'LarpManager\Entities\Potion',
				'choices' => $potions,
				'choice_label' => 'fullLabel'
		))
		->add('save','submit', array('label' => 'Valider votre potion'))
		->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$potion = $data['potions'];
	
			// Ajout de la potion au personnage
			$personnage->addPotion($potion);
			$app['orm.em']->persist($personnage);
	
			// suppression du trigger
			switch( $niveau)
			{
				case 1:
					$trigger = $personnage->getTrigger('ALCHIMIE APPRENTI');
					$app['orm.em']->remove($trigger);
					break;
				case 2:
					$trigger = $personnage->getTrigger('ALCHIMIE INITIE');
					$app['orm.em']->remove($trigger);
					break;
				case  3:
					$trigger = $personnage->getTrigger('ALCHIMIE EXPERT');
					$app['orm.em']->remove($trigger);
					break;
				case  4:
					$trigger = $personnage->getTrigger('ALCHIMIE MAITRE');
					$app['orm.em']->remove($trigger);
					break;
			}
	
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Vos modifications ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('personnage/potion.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'participant' => $participant,
				'potions' => $potions,
				'niveau' => $niveau,
		));
	}
	
	/**
	 * Choix d'une nouvelle description de religion
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 */
	public function religionDescriptionAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
		
		if ( ! $personnage->hasTrigger('PRETRISE INITIE') )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous ne pouvez pas choisir de descriptif de religion supplémentaire.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
		
		$availableDescriptionReligion = $app['personnage.manager']->getAvailableDescriptionReligion($personnage);
			
		$form = $app['form.factory']->createBuilder()
			->add('religion','entity', array(
					'required' => true,
					'label' => 'Choisissez votre nouveau descriptif religion',
					'multiple' => false,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Religion',
					'choices' => $availableDescriptionReligion,
					'choice_label' => 'label'
			))
			->add('save','submit', array('label' => 'Valider'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$religion = $data['religion'];
						
			$personnage->addReligion($religion);
			
			$trigger = $personnage->getTrigger('PRETRISE INITIE');
			$app['orm.em']->remove($trigger);
			
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Vos modifications ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
			
		return $app['twig']->render('public/personnage/descriptionReligion.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'participant' => $participant,
		));
	}
	
	/**
	 * Choix d'une nouvelle langue commune
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 */
	public function langueCommuneAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		if ( ! $personnage->hasTrigger('LANGUE COMMUNE') )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous ne pouvez pas choisir de langue commune supplémentaire.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		$availableLangues = $app['personnage.manager']->getAvailableLangues($personnage, 2);
			
		$form = $app['form.factory']->createBuilder()
			->add('langue','entity', array(
					'required' => true,
					'label' => 'Choisissez votre nouvelle langue',
					'multiple' => false,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Langue',
					'choices' => $availableLangues,
					'choice_label' => 'fullDescription'
			))
			->add('save','submit', array('label' => 'Valider votre nouvelle langue'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$langue = $data['langue'];
	
			$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
			$personnageLangue->setPersonnage($personnage);
			$personnageLangue->setLangue($langue);
			$personnageLangue->setSource('LITTERATURE');
			$app['orm.em']->persist($personnageLangue);
	
			$trigger = $personnage->getTrigger('LANGUE COMMUNE');
			$app['orm.em']->remove($trigger);
				
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Vos modifications ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('public/personnage/langueCommune.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'participant' => $participant,
		));
	}
	
	/**
	 * Choix d'une nouvelle langue courante
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 */
	public function langueCouranteAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		if ( ! $personnage->hasTrigger('LANGUE COURANTE') )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous ne pouvez pas choisir de langue courante supplémentaire.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
		
		$availableLangues = $app['personnage.manager']->getAvailableLangues($personnage, 1);
			
		$form = $app['form.factory']->createBuilder()
			->add('langue','entity', array(
					'required' => true,
					'label' => 'Choisissez votre nouvelle langue',
					'multiple' => false,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Langue',
					'choices' => $availableLangues,
					'choice_label' => 'fullDescription'
			))
			->add('save','submit', array('label' => 'Valider votre nouvelle langue'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$langue = $data['langue'];
	
			$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
			$personnageLangue->setPersonnage($personnage);
			$personnageLangue->setLangue($langue);
			$personnageLangue->setSource('LITTERATURE');
			$app['orm.em']->persist($personnageLangue);
	
			$trigger = $personnage->getTrigger('LANGUE COURANTE');
			$app['orm.em']->remove($trigger);
					
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Vos modifications ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('public/personnage/langueCourante.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'participant' => $participant,
		));
	}
	
	/**
	 * Choix d'une nouvelle langue ancienne
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 */
	public function langueAncienneAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		if ( ! $personnage->hasTrigger('LANGUE ANCIENNE') )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous ne pouvez pas choisir de langue ancienne supplémentaire.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		$availableLangues = $app['personnage.manager']->getAvailableLangues($personnage, 0);
			
		$form = $app['form.factory']->createBuilder()
			->add('langue','entity', array(
					'required' => true,
					'label' => 'Choisissez votre nouvelle langue',
					'multiple' => false,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Langue',
					'choices' => $availableLangues,
					'choice_label' => 'fullDescription'
			))
			->add('save','submit', array('label' => 'Valider votre nouvelle langue'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$langue = $data['langue'];
	
			$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
			$personnageLangue->setPersonnage($personnage);
			$personnageLangue->setLangue($langue);
			$personnageLangue->setSource('LITTERATURE');
			$app['orm.em']->persist($personnageLangue);
	
			$trigger = $personnage->getTrigger('LANGUE ANCIENNE');
			$app['orm.em']->remove($trigger);
				
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Vos modifications ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('public/personnage/langueAncienne.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'participant' => $participant,
		));
	}
	
	/**
	 * Choix d'un domaine de magie
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineMagieAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		if ( ! $personnage->hasTrigger('DOMAINE MAGIE') )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous ne pouvez pas choisir de domaine de magie supplémentaire.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
		
		$availableDomaines = $app['personnage.manager']->getAvailableDomaines($personnage);
			
		$form = $app['form.factory']->createBuilder()
			->add('domaine','entity', array(
					'required' => true,
					'label' => 'Choisissez votre domaine de magie',
					'multiple' => false,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Domaine',
					'choices' => $availableDomaines,
					'choice_label' => 'label'
			))
			->add('save','submit', array('label' => 'Valider votre domaine de magie'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$domaine = $data['domaine'];
				
			// Ajout du domaine de magie au personnage
			$personnage->addDomaine($domaine);
			$app['orm.em']->persist($personnage);
				
			// suppression du trigger
			$trigger = $personnage->getTrigger('DOMAINE MAGIE');
			$app['orm.em']->remove($trigger);
				
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Vos modifications ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
			
		}
		
		return $app['twig']->render('personnage/domaineMagie.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'participant' => $participant,
				'domaines' => $availableDomaines,
		));
	}
	
	/**
	 * Choix d'un nouveau sortilège
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
		
		$niveau = $request->get('niveau');
	
		if ( ! $personnage->hasTrigger('SORT APPRENTI')
				&& ! $personnage->hasTrigger('SORT INITIE')
				&& ! $personnage->hasTrigger('SORT EXPERT')
				&& ! $personnage->hasTrigger('SORT MAITRE') )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous ne pouvez pas choisir de sortilèges supplémentaires.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
		
		$sorts = $app['personnage.manager']->getAvailableSorts($personnage, $niveau);
			
		$form = $app['form.factory']->createBuilder()
			->add('sorts','entity', array(
					'required' => true,
					'label' => 'Choisissez votre sortilège',
					'multiple' => false,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Sort',
					'choices' => $sorts,
					'choice_label' => 'label'
			))
			->add('save','submit', array('label' => 'Valider votre sortilège'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$sort = $data['sorts'];
	
			// Ajout du domaine de magie au personnage
			$personnage->addSort($sort);
			$app['orm.em']->persist($personnage);
	
			// suppression du trigger
			switch( $niveau)
			{
				case 1:
					$trigger = $personnage->getTrigger('SORT APPRENTI');
					$app['orm.em']->remove($trigger);
					break;
				case 2:
					$trigger = $personnage->getTrigger('SORT INITIE');
					$app['orm.em']->remove($trigger);
					break;
				case  3:
					$trigger = $personnage->getTrigger('SORT EXPERT');
					$app['orm.em']->remove($trigger);
					break;
				case  4:
					$trigger = $personnage->getTrigger('SORT MAITRE');
					$app['orm.em']->remove($trigger);
					break;
			}
	
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Vos modifications ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('personnage/sort.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'participant' => $participant,
				'sorts' => $sorts,
				'niveau' => $niveau,
		));
	}
	
	/**
	 * Detail d'un sort
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 * @param Sort $potion
	 */
	public function sortDetailAction(Request $request, Application $app, Participant $participant, Sort $sort)
	{
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		if ( ! $personnage->isKnownSort($sort) )
		{
			$app['session']->getFlashBag()->add('error', 'Vous ne connaissez pas ce sort !');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('public/sort/detail.twig', array(
				'sort' => $sort,
				'participant' => $participant,
	
		));
	}
	
	/**
	 * Obtenir le document lié à un sort
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 * @param Potion $potion
	 */
	public function sortDocumentAction(Request $request, Application $app, Participant $participant, Sort $sort)
	{
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		if ( ! $personnage->isKnownSort($sort) )
		{
			$app['session']->getFlashBag()->add('error', 'Vous ne connaissez pas ce sort !');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
			
		$file = __DIR__.'/../../../private/doc/'.$sort->getDocumentUrl();
		return $app->sendFile($file);
	}
	
	/**
	 * Découverte de la magie, des domaines et sortilèges
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 */
	public function magieAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();

		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
		
		$domaines = $app['orm.em']->getRepository('\LarpManager\Entities\Domaine')->findAll();
		
		return $app['twig']->render('public/magie/index.twig', array(
				'domaines' => $domaines,
				'personnage' => $personnage,
				'participant' => $participant,
		));
	}
	
	/**
	 * Liste des compétences pour les joueurs
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function competenceListAction(Request $request, Application $app, Participant $participant)
	{
		$competences = $app['larp.manager']->getRootCompetences();
		
		return $app['twig']->render('public/competence/list.twig', array(
				'competences' => $competences,
				'participant' => $participant,
		));
	}
	
	/**
	 * Detail d'une competence
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 * @param Competence $competence
	 */
	public function competenceDetailAction(Request $request, Application $app, Participant $participant, Competence $competence)
	{
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		if ( ! $personnage->isKnownCompetence($competence) )
		{
			$app['session']->getFlashBag()->add('error', 'Vous ne connaissez pas cette competence !');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('public/competence/detail.twig', array(
				'competence' => $competence,
				'participant' => $participant,
	
		));
	}


	/**
	 * Liste des classes pour le joueur
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function classeListAction(Request $request, Application $app, Participant $participant)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Classe');
		$classes = $repo->findAllOrderedByLabel();
		return $app['twig']->render('public/classe/list.twig', array(
				'classes' => $classes,
				'participant' => $participant
		));
	}
	
	/**
	 * Obtenir le document lié à une competence
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 * @param Competence $competence
	 */
	public function competenceDocumentAction(Request $request, Application $app, Participant $participant, Competence $competence)
	{
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		if ( ! $personnage->isKnownCompetence($competence) )
		{
			$app['session']->getFlashBag()->add('error', 'Vous ne connaissez pas cette competence !');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
			
		$file = __DIR__.'/../../../private/doc/'.$competence->getDocumentUrl();
		return $app->sendFile($file);
	}
	
	/**
	 * Liste des groupes secondaires public (pour les joueurs)
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function groupeSecondaireListAction(Request $request, Application $app, Participant $participant)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\SecondaryGroup');
		$groupeSecondaires = $repo->findAllPublic();
	
		return $app['twig']->render('public/groupeSecondaire/list.twig', array(
				'groupeSecondaires' => $groupeSecondaires,
				'participant' => $participant,
		));
	}
	
	/**
	 * Postuler à un groupe secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function groupeSecondairePostulerAction(Request $request, Application $app, Participant $participant, SecondaryGroup $groupeSecondaire)
	{	
		/**
		 * L'utilisateur doit avoir un personnage
		 * @var Personnage $personnage
		 */
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage avant de postuler à un groupe secondaire!');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		/**
		 * Si le joueur est déjà postulant dans ce groupe, refuser la demande
		 */
		if ( $groupeSecondaire->isPostulant($personnage) )
		{
			$app['session']->getFlashBag()->add('error', 'Votre avez déjà postulé dans ce groupe. Inutile d\'en refaire la demande.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		/**
		 * Si le joueur est déjà membre de ce groupe, refuser la demande
		 */
		if ( $groupeSecondaire->isMembre($personnage) )
		{
			$app['session']->getFlashBag()->add('error', 'Votre êtes déjà membre de ce groupe. Inutile d\'en refaire la demande.');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
	
		/**
		 * Création du formulaire
		 * @var unknown $form
		 */
		$form = $app['form.factory']->createBuilder(new GroupeSecondairePostulerForm())
			->add('postuler','submit', array('label' => "Postuler"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			if ( empty($data['explanation'])) {
				$app['session']->getFlashBag()->add('error', 'Vos devez remplir le champ Explication.');
			}
			else
			{	
				$postulant = new \LarpManager\Entities\Postulant();
				$postulant->setPersonnage($personnage);
				$postulant->setSecondaryGroup($groupeSecondaire);
				$postulant->setExplanation($data['explanation']);
				$postulant->setWaiting(false);
		
				$app['orm.em']->persist($postulant);
				$app['orm.em']->flush();
					
					
				// envoi d'un mail au chef du groupe secondaire
				if ( $groupeSecondaire->getResponsable() )
				{
					// envoyer une notification au responsable
					$app['notify']->joinGroupeSecondaire($groupeSecondaire->getResponsable(), $groupeSecondaire);
				}
					
				$app['session']->getFlashBag()->add('success', 'Votre candidature a été enregistrée, et transmise au chef de groupe.');
		
				return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
			}
		}
	
		return $app['twig']->render('public/groupeSecondaire/postuler.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'participant' => $participant,
				'form' => $form->createView(),
		));
	}

	/**
	 * Affichage à destination d'un membre du groupe secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function groupeSecondaireDetailAction(Request $request, Application $app, Participant $participant, SecondaryGroup $groupeSecondaire)
	{
		$personnage = $participant->getPersonnage();
		$membre = $personnage->getMembre($groupeSecondaire);
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
		
		if ( ! $membre )
		{
			$app['session']->getFlashBag()->add('error', 'Votre n\'êtes pas membre de ce groupe.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('public/groupeSecondaire/detail.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'membre' => $membre,
				'participant' => $participant,
		));
	}
	
	
	
	/**
	 * Accepter une candidature à un groupe secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function groupeSecondaireAcceptAction(Request $request, Application $app, Participant $participant, SecondaryGroup $groupeSecondaire, Postulant $postulant)
	{
		$form = $app['form.factory']->createBuilder()
			->add('envoyer','submit', array('label' => "Accepter le postulant"))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $postulant->getPersonnage();
				
			$membre = new \LarpManager\Entities\Membre();
			$membre->setPersonnage($personnage);
			$membre->setSecondaryGroup($groupeSecondaire);
			$membre->setSecret(false);
	
			$app['orm.em']->persist($membre);
			$app['orm.em']->remove($postulant);
			$app['orm.em']->flush();
				
			// envoyer une notification au nouveau membre
			$app['notify']->acceptGroupeSecondaire($personnage->getUser(), $groupeSecondaire);
				
			$app['session']->getFlashBag()->add('success', 'Vous avez accepté la candidature. Un message a été envoyé au joueur concerné.');
			return $app->redirect($app['url_generator']->generate('participant.groupeSecondaire.detail', array('participant' => $participant->getId(), 'groupeSecondaire' => $groupeSecondaire->getId())),301);
		}
	
		return $app['twig']->render('public/groupeSecondaire/gestion_accept.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'postulant' => $postulant,
				'participant' => $participant,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Accepter une candidature à un groupe secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function groupeSecondaireRejectAction(Request $request, Application $app, Participant $participant, SecondaryGroup $groupeSecondaire, Postulant $postulant)
	{	
		$form = $app['form.factory']->createBuilder()
			->add('envoyer','submit', array('label' => "Refuser le postulant"))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $postulant->getPersonnage();
			$app['orm.em']->remove($postulant);
			$app['orm.em']->flush();
	
			$app['notify']->rejectGroupeSecondaire($personnage->getUser(), $groupeSecondaire);
	
			$app['session']->getFlashBag()->add('success', 'Vous avez refusé la candidature. Un message a été envoyé au joueur concerné.');
			return $app->redirect($app['url_generator']->generate('participant.groupeSecondaire.detail', array('participant' => $participant->getId(), 'groupeSecondaire' => $groupeSecondaire->getId())),301);
		}
	
	
		return $app['twig']->render('public/groupeSecondaire/gestion_reject.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'postulant' => $postulant,
				'participant' => $participant,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Laisser la candidature dans les postulant
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function groupeSecondaireWaitAction(Request $request, Application $app, Participant $participant, SecondaryGroup $groupeSecondaire, Postulant $postulant)
	{	
		$form = $app['form.factory']->createBuilder()
			->add('envoyer','submit', array('label' => "Laisser en attente"))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $postulant->getPersonnage();
			$postulant->setWaiting(true);
			$app['orm.em']->persist($postulant);
			$app['orm.em']->flush($postulant);
				
			$app['notify']->waitGroupeSecondaire($personnage->getUser(), $groupeSecondaire);
	
			$app['session']->getFlashBag()->add('success', 'La candidature reste en attente. Un message a été envoyé au joueur concerné.');
			return $app->redirect($app['url_generator']->generate('participant.groupeSecondaire.detail', array('participant' => $participant->getId(), 'groupeSecondaire' => $groupeSecondaire->getId())),301);
		}
	
	
		return $app['twig']->render('public/groupeSecondaire/gestion_wait.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'postulant' => $postulant,
				'participant' => $participant,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Répondre à un postulant
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function groupeSecondaireResponseAction(Request $request, Application $app, Participant $participant, SecondaryGroup $groupeSecondaire, Postulant $postulant)
	{
		$message = new Message();
	
		$message->setUserRelatedByAuteur($app['user']);
		$message->setUserRelatedByDestinataire($postulant->getPersonnage()->getUser());
		$message->setCreationDate(new \Datetime('NOW'));
		$message->setUpdateDate(new \Datetime('NOW'));
	
		$form = $app['form.factory']->createBuilder(new MessageForm(), $message)
			->add('envoyer','submit', array('label' => "Envoyer votre réponse"))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$message = $form->getData();
				
			$app['orm.em']->persist($message);
			$app['orm.em']->flush();
				
			$app['notify']->newMessage($postulant->getPersonnage()->getUser(), $message);
	
			$app['session']->getFlashBag()->add('success', 'Votre message a été envoyé au joueur concerné.');
			return $app->redirect($app['url_generator']->generate('participant.groupeSecondaire.detail', array('participant' => $participant->getId(), 'groupeSecondaire' => $groupeSecondaire->getId())),301);
		}
	
	
		return $app['twig']->render('public/groupeSecondaire/gestion_response.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'postulant' => $postulant,
				'participant' => $participant,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Ajoute une compétence au personnage
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function competenceAddAction(Request $request, Application $app, Participant $participant)
	{
		$personnage = $participant->getPersonnage();
	
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage !');
			return $app->redirect($app['url_generator']->generate('gn.detail', array('gn' => $participant->getGn()->getId())),301);
		}
		
		if ( $participant->getGroupeGn()->getGroupe()->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'est plus possible de modifier ce personnage. Le groupe est verouillé. Contacter votre scénariste si vous pensez que cela est une erreur');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		$availableCompetences = $app['personnage.manager']->getAvailableCompetences($personnage);
	
		if ( $availableCompetences->count() == 0 )
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'y a plus de compétence disponible (Bravo !).');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		// construit le tableau de choix
		$choices = array();
		foreach ( $availableCompetences as $competence)
		{
			$choices[$competence->getId()] = $competence->getLabel() . ' (cout : '.$app['personnage.manager']->getCompetenceCout($personnage, $competence).' xp)';
		}
	
		$form = $app['form.factory']->createBuilder()
			->add('competenceId','choice', array(
					'label' =>  'Choisissez une nouvelle compétence',
					'choices' => $choices,
			))
			->add('save','submit', array('label' => 'Valider la compétence'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$data = $form->getData();
				
			$competenceId = $data['competenceId'];
			$competence = $app['orm.em']->find('\LarpManager\Entities\Competence', $competenceId);
	
			$cout = $app['personnage.manager']->getCompetenceCout($personnage, $competence);
			$xp = $personnage->getXp();
				
			if ( $xp - $cout < 0 )
			{
				$app['session']->getFlashBag()->add('error','Vos n\'avez pas suffisement de point d\'expérience pour acquérir cette compétence.');
				return $app->redirect($app['url_generator']->generate('homepage'),301);
			}
			$personnage->setXp($xp - $cout);
			$personnage->addCompetence($competence);
			$competence->addPersonnage($personnage);
				
			// cas special noblesse
			// noblesse apprentit +2 renomme
			// noblesse initie  +3 renomme
			// noblesse expert +2 renomme
			// TODO : trouver un moyen pour ne pas implémenter les règles spéciales de ce type dans le code.
			if ( $competence->getCompetenceFamily()->getLabel() == "Noblesse")
			{
				switch ($competence->getLevel()->getId())
				{
					case 1:
						$personnage->addRenomme(2);
						$renomme_history = new \LarpManager\Entities\RenommeHistory();
						
						$renomme_history->setRenomme(2);
						$renomme_history->setExplication('Compétence Noblesse niveau 1');
						$renomme_history->setPersonnage($personnage);
						$app['orm.em']->persist($renomme_history);
						break;
					case 2:
						$personnage->addRenomme(3);
						$renomme_history = new \LarpManager\Entities\RenommeHistory();
						
						$renomme_history->setRenomme(3);
						$renomme_history->setExplication('Compétence Noblesse niveau 2');
						$renomme_history->setPersonnage($personnage);
						$app['orm.em']->persist($renomme_history);
						break;
					case 3:
						$personnage->addRenomme(2);
						$renomme_history = new \LarpManager\Entities\RenommeHistory();
						
						$renomme_history->setRenomme(2);
						$renomme_history->setExplication('Compétence Noblesse niveau 3');
						$renomme_history->setPersonnage($personnage);
						$app['orm.em']->persist($renomme_history);
						break;
					case 4:
						$personnage->addRenomme(5);
						$renomme_history = new \LarpManager\Entities\RenommeHistory();
						
						$renomme_history->setRenomme(5);
						$renomme_history->setExplication('Compétence Noblesse niveau 4');
						$renomme_history->setPersonnage($personnage);
						$app['orm.em']->persist($renomme_history);
						break;
					case 5:
						$personnage->addRenomme(6);
						$renomme_history = new \LarpManager\Entities\RenommeHistory();
						
						$renomme_history->setRenomme(6);
						$renomme_history->setExplication('Compétence Noblesse niveau 5');
						$renomme_history->setPersonnage($personnage);
						$app['orm.em']->persist($renomme_history);
						break;
				}
			}
				
			// cas special prêtrise
			if ( $competence->getCompetenceFamily()->getLabel() == "Prêtrise")
			{
				// le personnage doit avoir une religion au niveau fervent ou fanatique
				if ( $personnage->isFervent() || $personnage->isFanatique() )
				{
					// ajoute toutes les prières de niveau de sa compétence liés aux sphère de sa religion fervente ou fanatique
					$religion = $personnage->getMainReligion();
					foreach ( $religion->getSpheres() as $sphere)
					{
						foreach ( $sphere->getPrieres() as $priere)
						{
							if ( $priere->getNiveau() == $competence->getLevel()->getId() )
							{
								if ( ! $personnage->hasPriere($priere) )
								{
									$priere->addPersonnage($personnage);
									$personnage->addPriere($priere);
								}
							}
						}
					}
				}
				else
				{
					$app['session']->getFlashBag()->add('error','Pour obtenir la compétence Prêtrise, vous devez être FERVENT ou FANATIQUE');
					return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
				}
			}
			
			// case special prêtrise initié
			if ( $competence->getCompetenceFamily()->getLabel() == "Prêtrise")
			{
				if (  $competence->getLevel()->getId() >= 2 )
				{
					$trigger = new \LarpManager\Entities\PersonnageTrigger();
					$trigger->setPersonnage($personnage);
					$trigger->setTag('PRETISE INITIE');
					$trigger->setDone(false);
					$app['orm.em']->persist($trigger);
					$app['orm.em']->flush();
					
					$trigger = new \LarpManager\Entities\PersonnageTrigger();
					$trigger->setPersonnage($personnage);
					$trigger->setTag('PRETISE INITIE');
					$trigger->setDone(false);
					$app['orm.em']->persist($trigger);
					$app['orm.em']->flush();
					
					$trigger = new \LarpManager\Entities\PersonnageTrigger();
					$trigger->setPersonnage($personnage);
					$trigger->setTag('PRETISE INITIE');
					$trigger->setDone(false);
					$app['orm.em']->persist($trigger);
					$app['orm.em']->flush();
				}
			}
					
				
			// cas special alchimie
			if ( $competence->getCompetenceFamily()->getLabel() == "Alchimie")
			{
				switch ($competence->getLevel()->getId())
				{
					case 1: // le personnage doit choisir 2 potions de niveau apprenti
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('ALCHIMIE APPRENTI');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
	
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('ALCHIMIE APPRENTI');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						break;
					case 2: // le personnage doit choisir 1 potion de niveau initie et 1 potion de niveau apprenti
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('ALCHIMIE INITIE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
	
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('ALCHIMIE APPRENTI');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						break;
					case 3: // le personnage doit choisir 1 potion de niveau expert
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('ALCHIMIE EXPERT');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						break;
					case 4: // le personnage doit choisir 1 potion de niveau maitre
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('ALCHIMIE MAITRE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						break;
				}
			}
				
			// cas special magie
			if ( $competence->getCompetenceFamily()->getLabel() == "Magie")
			{
				switch ($competence->getLevel()->getId())
				{
					case 1: // le personnage doit choisir un domaine de magie
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('DOMAINE MAGIE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
	
						// il obtient aussi la possibilité de choisir un sort de niveau 1
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('SORT APPRENTI');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						break;
					case 2:
						// il obtient aussi la possibilité de choisir un sort de niveau 2
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('SORT INITIE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						break;
					case 3: // le personnage peut choisir un nouveau domaine de magie
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('DOMAINE MAGIE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
	
						// il obtient aussi la possibilité de choisir un sort de niveau 3
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('SORT EXPERT');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
					case 4:
						break;
						// il obtient aussi la possibilité de choisir un sort de niveau 4
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('SORT MAITRE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
				}
			}
				
			// cas special littérature
			if ( $competence->getCompetenceFamily()->getLabel() == "Littérature")
			{
				switch ($competence->getLevel()->getId())
				{
					case 1: // 2 langues très répandue supplémentaires de son choix
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE COURANTE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE COURANTE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						
						break;
					case 2: //  Sait parler, lire et écrire trois autres langues vivantes (courante ou très répandue) de son choix.
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE COURANTE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE COURANTE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE COURANTE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						break;
					case 3: // Sait parler, lire et écrire un langage ancien ainsi que trois autres langues vivantes (courante ou très répandue) de son choix ainsi qu'une langue ancienne
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE COURANTE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE COURANTE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE COURANTE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE ANCIENNE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						break;
					case 4: // Sait parler, lire et écrire un autre langage ancien ainsi que trois autres langues vivantes de son choix (courante ou très répandue) ainsi qu'une langue ancienne
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE COURANTE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE COURANTE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE COURANTE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LANGUE ANCIENNE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						$app['orm.em']->flush();
						break;
				}
			}
				
			// historique
			$historique = new \LarpManager\Entities\ExperienceUsage();
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setXpUse($cout);
			$historique->setCompetence($competence);
			$historique->setPersonnage($personnage);
				
			$app['orm.em']->persist($competence);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->persist($historique);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('gn.personnage', array('gn' => $participant->getGn()->getId())),301);
		}
	
		return $app['twig']->render('public/personnage/competence.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'competences' =>  $availableCompetences,
				'participant' => $participant
		));
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
	
	
	/**
	 * Demander une nouvelle alliance
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function requestAllianceAction(Request $request, Application $app)
	{
		$participant = $request->get('participant');
		$groupe = $request->get('groupe');
		$groupeGn = $participant->getGroupeGn();
	
		if ( $groupe->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Les relations diplomatiques entre pays sont actuellement gelées jusqu’au GN (pour que nous puissions avoir un état de la situation). Vous pourrez les modifier en jeu désormais (voir le jeu diplomatique)');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		// un groupe ne peux pas avoir plus de 3 alliances
		if ( $groupe->getAlliances()->count() >= 3 )
		{
			$app['session']->getFlashBag()->add('error', 'Désolé, vous avez déjà 3 alliances, ce qui est le maximum possible.');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		// un groupe ne peux pas avoir plus d'alliances que d'ennemis
		if ( $groupe->getEnnemies()->count() - $groupe->getAlliances()->count() <= 0 )
		{
			$app['session']->getFlashBag()->add('error', 'Désolé, vous n\'avez pas suffisement d\'ennemis pour pouvoir vous choisir un allié.');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		$alliance = new \LarpManager\Entities\GroupeAllie();
		$alliance->setGroupe($groupe);
	
		$form = $app['form.factory']->createBuilder(new RequestAllianceForm(), $alliance)
		->add('send','submit', array('label' => 'Envoyer'))
		->getForm();
	
		$form->handleRequest($request);
	
		if ($request->isMethod('POST')) {
				
			$alliance = $form->getData();
			$alliance->setGroupeAccepted(true);
			$alliance->setGroupeAllieAccepted(false);
				
			// vérification des conditions pour le groupe choisi
			$requestedGroupe = $alliance->getRequestedGroupe();
			if ( $requestedGroupe == $groupe)
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, vous ne pouvez pas choisir votre propre groupe pour faire une alliance ...');
				return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
			}
				
			if ( $groupe->isAllyTo($requestedGroupe))
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, vous êtes déjà allié avec ce groupe');
				return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
			}
				
			if ( $groupe->isEnemyTo($requestedGroupe))
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, vous êtes ennemi avec ce groupe. Impossible de faire une alliance, faites d\'abord la paix !');
				return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
			}
				
			if ( $requestedGroupe->getAlliances()->count() >= 3 )
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, le groupe demandé dispose déjà de 3 alliances, ce qui est le maximum possible.');
				return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
			}
				
			if ( $requestedGroupe->getEnnemies()->count() - $requestedGroupe->getAlliances()->count() <= 0 )
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, le groupe demandé n\'a pas suffisement d\'ennemis pour pouvoir obtenir un allié supplémentaire.');
				return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
			}
				
			$app['orm.em']->persist($alliance);
			$app['orm.em']->flush();
				
			$app['user.mailer']->sendRequestAlliance($alliance);
				
			$app['session']->getFlashBag()->add('success', 'Votre demande a été envoyé.');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		return $app['twig']->render('public/groupe/requestAlliance.twig', array(
				'groupe' => $groupe,
				'participant' => $participant,
				'groupeGn' => $groupeGn,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Annuler une demande d'alliance
	 * @param Request $request
	 * @param Application $app
	 */
	public function cancelRequestedAllianceAction(Request $request, Application $app)
	{
		$participant = $request->get('participant');
		$groupe = $request->get('groupe');
		$alliance = $request->get('alliance');
		$groupeGn = $participant->getGroupeGn();
	
		if ( $groupe->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Les relations diplomatiques entre pays sont actuellement gelées jusqu’au GN (pour que nous puissions avoir un état de la situation). Vous pourrez les modifier en jeu désormais (voir le jeu diplomatique)');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		$form = $app['form.factory']->createBuilder(new CancelRequestedAllianceForm(), $alliance)
		->add('send','submit', array('label' => 'Oui, j\'annule ma demande'))
		->getForm();
	
		$form->handleRequest($request);
	
		if ($request->isMethod('POST')) {
				
			$alliance = $form->getData();
				
			$app['orm.em']->remove($alliance);
			$app['orm.em']->flush();
				
			$app['user.mailer']->sendCancelAlliance($alliance);
				
			$app['session']->getFlashBag()->add('success', 'Votre demande d\'alliance a été annulée.');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		return $app['twig']->render('public/groupe/cancelAlliance.twig', array(
				'alliance' => $alliance,
				'groupe' => $groupe,
				'participant' => $participant,
				'groupeGn' => $groupeGn,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Accepter une alliance
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function acceptAllianceAction(Request $request, Application $app)
	{
		$participant = $request->get('participant');
		$groupe = $request->get('groupe');
		$alliance = $request->get('alliance');
		$groupeGn = $participant->getGroupeGn();
	
		if ( $groupe->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Les relations diplomatiques entre pays sont actuellement gelées jusqu’au GN (pour que nous puissions avoir un état de la situation). Vous pourrez les modifier en jeu désormais (voir le jeu diplomatique)');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		$form = $app['form.factory']->createBuilder(new AcceptAllianceForm(), $alliance)
		->add('send','submit', array('label' => 'Envoyer'))
		->getForm();
	
		$form->handleRequest($request);
	
		if ($request->isMethod('POST')) {
	
			$alliance = $form->getData();
				
			$alliance->setGroupeAllieAccepted(true);
			$app['orm.em']->persist($alliance);
			$app['orm.em']->flush();
	
			$app['user.mailer']->sendAcceptAlliance($alliance);
	
			$app['session']->getFlashBag()->add('success', 'Vous avez accepté la proposition d\'alliance.');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
			
		return $app['twig']->render('public/groupe/acceptAlliance.twig', array(
				'alliance' => $alliance,
				'groupe' => $groupe,
				'participant' => $participant,
				'groupeGn' => $groupeGn,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Refuser une alliance
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function refuseAllianceAction(Request $request, Application $app)
	{
		$participant = $request->get('participant');
		$groupe = $request->get('groupe');
		$alliance = $request->get('alliance');
		$groupeGn = $participant->getGroupeGn();
	
		if ( $groupe->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Les relations diplomatiques entre pays sont actuellement gelées jusqu’au GN (pour que nous puissions avoir un état de la situation). Vous pourrez les modifier en jeu désormais (voir le jeu diplomatique)');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		$form = $app['form.factory']->createBuilder(new RefuseAllianceForm(), $alliance)
		->add('send','submit', array('label' => 'Envoyer'))
		->getForm();
	
		$form->handleRequest($request);
	
		if ($request->isMethod('POST')) {
	
			$alliance = $form->getData();
	
			$app['orm.em']->remove($alliance);
			$app['orm.em']->flush();
	
			$app['user.mailer']->sendRefuseAlliance($alliance);
	
			$app['session']->getFlashBag()->add('success', 'Vous avez refusé la proposition d\'alliance.');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
			
		return $app['twig']->render('public/groupe/refuseAlliance.twig', array(
				'alliance' => $alliance,
				'groupe' => $groupe,
				'participant' => $participant,
				'groupeGn' => $groupeGn,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Briser une alliance
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function breakAllianceAction(Request $request, Application $app)
	{
		$participant = $request->get('participant');
		$groupe = $request->get('groupe');
		$alliance = $request->get('alliance');
		$groupeGn = $participant->getGroupeGn();
	
		if ( $groupe->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Les relations diplomatiques entre pays sont actuellement gelées jusqu’au GN (pour que nous puissions avoir un état de la situation). Vous pourrez les modifier en jeu désormais (voir le jeu diplomatique)');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		$form = $app['form.factory']->createBuilder(new BreakAllianceForm(), $alliance)
		->add('send','submit', array('label' => 'Envoyer'))
		->getForm();
	
		$form->handleRequest($request);
	
		if ($request->isMethod('POST')) {
	
			$alliance = $form->getData();
	
	
			$app['orm.em']->remove($alliance);
			$app['orm.em']->flush();
	
			if  ( $alliance->getGroupe() == $groupe )
			{
				$app['user.mailer']->sendBreakAlliance($alliance, $alliance->getRequestedGroupe());
			}
			else
			{
				$app['user.mailer']->sendBreakAlliance($alliance, $groupe);
			}
				
	
			$app['session']->getFlashBag()->add('success', 'Vous avez brisé une alliance.');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
			
		return $app['twig']->render('public/groupe/breakAlliance.twig', array(
				'alliance' => $alliance,
				'groupe' => $groupe,
				'participant' => $participant,
				'groupeGn' => $groupeGn,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Déclarer la guerre
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function declareWarAction(Request $request, Application $app)
	{
		$participant = $request->get('participant');
		$groupe = $request->get('groupe');
		$groupeGn = $participant->getGroupeGn();
	
		if ( $groupe->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Les relations diplomatiques entre pays sont actuellement gelées jusqu’au GN (pour que nous puissions avoir un état de la situation). Vous pourrez les modifier en jeu désormais (voir le jeu diplomatique)');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		// un groupe ne peux pas faire de déclaration de guerre si il a 3 ou plus ennemis
		if ( $groupe->getEnnemies()->count() >= 3 )
		{
			$app['session']->getFlashBag()->add('error', 'Désolé, vous avez déjà 3 ennemis ou plus, impossible de faire une nouvelle déclaration de guerre .');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		$war = new \LarpManager\Entities\GroupeEnemy();
		$war->setGroupe($groupe);
		$war->setGroupePeace(false);
		$war->setGroupeEnemyPeace(false);
	
		$form = $app['form.factory']->createBuilder(new DeclareWarForm(), $war)
		->add('send','submit', array('label' => 'Envoyer'))
		->getForm();
	
		if ($request->isMethod('POST')) {
				
			$form->handleRequest($request);
				
			$war = $form->getData();
			$war->setGroupePeace(false);
			$war->setGroupeEnemyPeace(false);
	
			// vérification des conditions pour le groupe choisi
			$requestedGroupe = $war->getRequestedGroupe();
			if ( $requestedGroupe == $groupe)
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, vous ne pouvez pas choisir votre propre groupe comme ennemi ...');
				return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
			}
	
			if ( $groupe->isEnemyTo($requestedGroupe))
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, vous êtes déjà en guerre avec ce groupe');
				return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
			}
				
			if ( $requestedGroupe->getEnnemies()->count() >= 5 )
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, le groupe demandé dispose déjà de 5 ennemis, ce qui est le maximum possible.');
				return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
			}
	
			if ( $groupe->isEnemyTo($requestedGroupe))
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, vous êtes déjà allié avec ce groupe');
				return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
			}
	
			$app['orm.em']->persist($war);
			$app['orm.em']->flush();
				
			$app['user.mailer']->sendDeclareWar($war);
				
			$app['session']->getFlashBag()->add('success', 'Votre déclaration de guerre vient d\'être envoyé.');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
	
		}
			
		return $app['twig']->render('public/groupe/declareWar.twig', array(
				'groupe' => $groupe,
				'participant' => $participant,
				'groupeGn' => $groupeGn,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Demander la paix
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function requestPeaceAction(Request $request, Application $app)
	{
		$participant = $request->get('participant');
		$groupe = $request->get('groupe');
		$war = $request->get('enemy');
		$groupeGn = $participant->getGroupeGn();
	
		if ( $groupe->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Les relations diplomatiques entre pays sont actuellement gelées jusqu’au GN (pour que nous puissions avoir un état de la situation). Vous pourrez les modifier en jeu désormais (voir le jeu diplomatique)');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		$form = $app['form.factory']->createBuilder(new RequestPeaceForm(), $war)
		->add('send','submit', array('label' => 'Envoyer'))
		->getForm();
	
		if ($request->isMethod('POST')) {
	
			$form->handleRequest($request);
			$war = $form->getData();
				
			if ( $groupe == $war->getGroupe() )
			{
				$war->setGroupePeace(true);
			}
			else
			{
				$war->setGroupeEnemyPeace(true);
			}
				
			$app['orm.em']->persist($war);
			$app['orm.em']->flush();
				
			$app['user.mailer']->sendRequestPeace($war, $groupe);
				
			$app['session']->getFlashBag()->add('success', 'Votre demande de paix vient d\'être envoyé.');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		return $app['twig']->render('public/groupe/requestPeace.twig', array(
				'war' => $war,
				'groupe' => $groupe,
				'participant' => $participant,
				'groupeGn' => $groupeGn,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Accepter la paix
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function acceptPeaceAction(Request $request, Application $app)
	{
		$participant = $request->get('participant');
		$groupe = $request->get('groupe');
		$war = $request->get('enemy');
		$groupeGn = $participant->getGroupeGn();
	
	
		if ( $groupe->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Les relations diplomatiques entre pays sont actuellement gelées jusqu’au GN (pour que nous puissions avoir un état de la situation). Vous pourrez les modifier en jeu désormais (voir le jeu diplomatique)');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		$form = $app['form.factory']->createBuilder(new AcceptPeaceForm(), $war)
		->add('send','submit', array('label' => 'Envoyer'))
		->getForm();
	
		if ($request->isMethod('POST')) {
	
			$form->handleRequest($request);
			$war = $form->getData();
	
			if ( $groupe == $war->getGroupe() )
			{
				$war->setGroupePeace(true);
			}
			else
			{
				$war->setGroupeEnemyPeace(true);
			}
				
			$app['orm.em']->persist($war);
			$app['orm.em']->flush();
	
			$app['user.mailer']->sendAcceptPeace($war, $groupe);
				
			$app['session']->getFlashBag()->add('success', 'Vous avez fait la paix !');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
			
		return $app['twig']->render('public/groupe/acceptPeace.twig', array(
				'war' => $war,
				'groupe' => $groupe,
				'participant' => $participant,
				'groupeGn' => $groupeGn,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Refuser la paix
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function refusePeaceAction(Request $request, Application $app)
	{
		$participant = $request->get('participant');
		$groupe = $request->get('groupe');
		$war = $request->get('enemy');
		$groupeGn = $participant->getGroupeGn();
		
		if ( $groupe->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Les relations diplomatiques entre pays sont actuellement gelées jusqu’au GN (pour que nous puissions avoir un état de la situation). Vous pourrez les modifier en jeu désormais (voir le jeu diplomatique)');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		$form = $app['form.factory']->createBuilder(new RefusePeaceForm(), $war)
		->add('send','submit', array('label' => 'Envoyer'))
		->getForm();
	
		if ($request->isMethod('POST')) {
	
			$form->handleRequest($request);
			$war = $form->getData();
	
			$war->setGroupePeace(false);
			$war->setGroupeEnemyPeace(false);
	
			$app['orm.em']->persist($war);
			$app['orm.em']->flush();
	
			$app['user.mailer']->sendRefusePeace($war, $groupe);
				
			$app['session']->getFlashBag()->add('success', 'Vous avez refusé la proposition de paix.');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
			
		return $app['twig']->render('public/groupe/refusePeace.twig', array(
				'war' => $war,
				'groupe' => $groupe,
				'participant' => $participant,
				'groupeGn' => $groupeGn,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Annuler la demande de paix
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function cancelRequestedPeaceAction(Request $request, Application $app)
	{
		$participant = $request->get('participant');
		$groupe = $request->get('groupe');
		$war = $request->get('enemy');
				
		$groupeGn = $participant->getGroupeGn();
	
		if ( $groupe->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Les relations diplomatiques entre pays sont actuellement gelées jusqu’au GN (pour que nous puissions avoir un état de la situation). Vous pourrez les modifier en jeu désormais (voir le jeu diplomatique)');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
	
		$form = $app['form.factory']->createBuilder(new CancelRequestedPeaceForm(), $war)
		->add('send','submit', array('label' => 'Envoyer'))
		->getForm();
	
		if ($request->isMethod('POST')) {
	
			$form->handleRequest($request);
			$war = $form->getData();
	
			if ( $groupe == $war->getGroupe() )
			{
				$war->setGroupePeace(false);
			}
			else
			{
				$war->setGroupeEnemyPeace(false);
			}
	
			$app['orm.em']->persist($war);
			$app['orm.em']->flush();
	
			$app['user.mailer']->sendRefusePeace($war, $groupe);
	
			$app['session']->getFlashBag()->add('success', 'Vous avez annulé votre proposition de paix.');
			return $app->redirect($app['url_generator']->generate('groupeGn.groupe', array('groupeGn' => $groupeGn->getId())));
		}
			
		return $app['twig']->render('public/groupe/cancelPeace.twig', array(
				'war' => $war,
				'groupe' => $groupe,
				'participant' => $participant,
				'groupeGn' => $groupeGn,
				'form' => $form->createView()
		));
	}
	
}
