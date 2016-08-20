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
use JasonGrimes\Paginator;
use LarpManager\Form\GroupeSecondaireForm;
use LarpManager\Form\GroupeSecondairePostulerForm;
use LarpManager\Form\SecondaryGroupFindForm;
use LarpManager\Entities\Message;
use LarpManager\Form\MessageForm;

/**
 * LarpManager\Controllers\GroupeSecondaireController
 *
 * @author kevin
 *
 */
class GroupeSecondaireController
{
	/**
	 * Page d'accueil de gestion des groupes secondaires
	 * @param Request $request
	 * @param Application $app
	 */
	public function accueilAction(Request $request, Application $app)
	{
		return $app['twig']->render('public/groupeSecondaire/accueil.twig', array());
	}
	
	/**
	 * Liste des groupes secondaires (pour les orgas)
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminListAction(Request $request, Application $app)
	{
		$order_by = $request->get('order_by') ?: 'label';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		
		$form = $app['form.factory']->createBuilder(new SecondaryGroupFindForm())
			->add('find','submit', array('label' => 'Rechercher'))
			->getForm();
		
		$criteria = array();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\SecondaryGroup');
		$groupeSecondaires = $repo->findBy(
				$criteria,
				array( $order_by => $order_dir),
				$limit,
				$offset);
		
		$numResults = $repo->findCount($criteria);
		
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('groupeSecondaire.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
		
		return $app['twig']->render('admin/groupeSecondaire/list.twig', array(
				'groupeSecondaires' => $groupeSecondaires,
				'paginator' => $paginator,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Liste des groupes secondaires (pour les joueurs)
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$order_by = $request->get('order_by') ?: 'label';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
	
		$criteria = array();
	
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\SecondaryGroup');
		$groupeSecondaires = $repo->findBy(
				$criteria,
				array( $order_by => $order_dir),
				$limit,
				$offset);
	
		$numResults = $repo->findCount($criteria);
	
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('groupeSecondaire.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
	
		return $app['twig']->render('public/groupeSecondaire/list.twig', array(
				'groupeSecondaires' => $groupeSecondaires,
				'paginator' => $paginator,
		));
	}
	
	/**
	 * Postuler à un groupe secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function postulerAction(Request $request, Application $app)
	{	
		$groupeSecondaire = $request->get('groupe');

		/**
		 * L'utilisateur doit avoir un personnage
		 * @var Personnage $personnage
		 */
		$personnage = $app['user']->getPersonnage();
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage avant de postuler à un groupe !');
			return $app->redirect($app['url_generator']->generate('homepage'));
		}

		/**
		 * Si le joueur est déjà postulant dans ce groupe, refuser la demande
		 */
		if ( $groupeSecondaire->isPostulant($personnage) )
		{
			$app['session']->getFlashBag()->add('error', 'Votre avez déjà postulé dans ce groupe. Inutile d\'en refaire la demande.');
			return $app->redirect($app['url_generator']->generate('homepage'));
		}
		
		/**
		 * Si le joueur est déjà membre de ce groupe, refuser la demande
		 */
		if ( $groupeSecondaire->isMembre($personnage) )
		{
			$app['session']->getFlashBag()->add('error', 'Votre êtes déjà membre de ce groupe. Inutile d\'en refaire la demande.');
			return $app->redirect($app['url_generator']->generate('homepage'));
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
			
			$postulant = new \LarpManager\Entities\Postulant();
			$postulant->setPersonnage($app['user']->getPersonnage());
			$postulant->setSecondaryGroup($groupeSecondaire);
			$postulant->setExplanation($data['explanation']);
			$postulant->setWaiting(false);
		
			$app['orm.em']->persist($postulant);
			$app['orm.em']->flush();
			
			
			// envoi d'un mail au chef du groupe secondaire
			if ( $groupeSecondaire->getResponsable() )
			{
				$message = "Nouvelle candidature";
				$message = \Swift_Message::newInstance()
					->setSubject('[LarpManager] Nouvelle candidature')
					->setFrom(array('noreply@eveoniris.com'))
					->setTo(array($groupeSecondaire->getResponsable()->getParticipant()->getUser()->getEmail()))
					->setBody($message);
				 
				$app['mailer']->send($message);
			}
			
			$app['session']->getFlashBag()->add('success', 'Votre candidature a été enregistrée, et transmise au chef de groupe.');
		
			return $app->redirect($app['url_generator']->generate('homepage'));
		}
		
		return $app['twig']->render('public/groupeSecondaire/postuler.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Ajoute un groupe secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddAction(Request $request, Application $app)
	{
		$groupeSecondaire = new \LarpManager\Entities\SecondaryGroup();
	
		$form = $app['form.factory']->createBuilder(new GroupeSecondaireForm(), $groupeSecondaire)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$groupeSecondaire = $form->getData();
	
			/**
			 * Création des topics associés à ce groupe
			 * un topic doit être créé par GN auquel ce groupe est inscrit
			 * @var \LarpManager\Entities\Topic $topic
			 */
			$topic = new \LarpManager\Entities\Topic();
			$topic->setTitle($groupeSecondaire->getLabel());
			$topic->setDescription($groupeSecondaire->getDescription());
			$topic->setUser($app['user']);
			$topic->setTopic($app['larp.manager']->findTopic('TOPIC_GROUPE_SECONDAIRE'));
			$app['orm.em']->persist($topic);
			$app['orm.em']->persist($groupeSecondaire);
			$app['orm.em']->flush();
			
			// défini les droits d'accés à ce forum
			// (les membres du groupe ont le droit d'accéder à ce forum)
			$topic->setRight('GROUPE_SECONDAIRE_MEMBER');
			$topic->setObjectId($groupeSecondaire->getId());
			$groupeSecondaire->setTopic($topic);

			/**
			 * Ajoute le responsable du groupe dans le groupe si il n'y est pas déjà
			 */
			$personnage = $groupeSecondaire->getResponsable();
			if ( ! $groupeSecondaire->isMembre($personnage) )
			{
				$membre = new \LarpManager\Entities\Membre();
				$membre->setPersonnage($personnage);
				$membre->setSecondaryGroup($groupeSecondaire);
				$membre->setSecret(false);
				
				$app['orm.em']->persist($membre);
				$app['orm.em']->flush();
				
				$groupeSecondaire->addMembre($membre);
			}
			
			$app['orm.em']->persist($topic);
			$app['orm.em']->persist($groupeSecondaire);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le groupe secondaire a été ajouté.');
	
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupeSecondaire.admin.list'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupeSecondaire.admin.add'),301);
			}
		}
	
		return $app['twig']->render('admin/groupeSecondaire/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour un de groupe secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateAction(Request $request, Application $app)
	{
		$groupeSecondaire = $request->get('groupe');
	
		$form = $app['form.factory']->createBuilder(new GroupeSecondaireForm(), $groupeSecondaire)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$groupeSecondaire = $form->getData();
	
			if ($form->get('update')->isClicked())
			{
				/**
				 * Ajoute le responsable du groupe dans le groupe si il n'y est pas déjà
				 */
				$personnage = $groupeSecondaire->getResponsable();
				if ( ! $groupeSecondaire->isMembre($personnage) )
				{
					$membre = new \LarpManager\Entities\Membre();
					$membre->setPersonnage($personnage);
					$membre->setSecondaryGroup($groupeSecondaire);
					$membre->setSecret(false);
					
					$app['orm.em']->persist($membre);
					$app['orm.em']->flush();
					
					$groupeSecondaire->addMembre($membre);
				}
				
				/**
				 * Retire la candidature du responsable si elle existe
				 */
				foreach ( $groupeSecondaire->getPostulants() as $postulant)
				{
					if ( $postulant->getPersonnage() == $personnage )
					{
						$app['orm.em']->remove($postulant);
					}
				}
				
				$app['orm.em']->persist($groupeSecondaire);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le groupe secondaire a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($groupeSecondaire);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le groupe secondaire a été supprimé.');
			}
	
			return $app->redirect($app['url_generator']->generate('groupeSecondaire.admin.list'));
		}
	
		return $app['twig']->render('admin/groupeSecondaire/update.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Détail d'un groupe secondaire (pour les orgas)
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDetailAction(Request $request, Application $app)
	{
		$groupeSecondaire = $request->get('groupe');

		return $app['twig']->render('admin/groupeSecondaire/detail.twig', array(
				'groupeSecondaire' => $groupeSecondaire));
	}
	
	/**
	 * Retire un postulant du groupe
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminRemovePostulantAction(Request $request, Application $app)
	{
		$groupeSecondaire = $request->get('groupe');
		$postulant = $request->get('postulant');
		
		$app['orm.em']->remove($postulant);
		$app['orm.em']->flush();
				
		$app['session']->getFlashBag()->add('success', 'la candidature a été supprimée.');
		
		return $app['twig']->render('admin/groupeSecondaire/detail.twig', array(
				'groupeSecondaire' => $groupeSecondaire));
	}
	
	/**
	 * Accepte un postulant dans le groupe
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAcceptPostulantAction(Request $request, Application $app)
	{
		$groupeSecondaire = $request->get('groupe');
		$postulant = $request->get('postulant');
	
		$personnage = $postulant->getPersonnage();
			
		$membre = new \LarpManager\Entities\Membre();
		$membre->setPersonnage($personnage);
		$membre->setSecondaryGroup($groupeSecondaire);
		$membre->setSecret(false);
		
		$app['orm.em']->persist($membre);
		$app['orm.em']->remove($postulant);
		$app['orm.em']->flush();
			
		$app['user.mailer']->sendGroupeSecondaireAcceptMessage($personnage->getParticipant()->getUser(), $groupeSecondaire);
			
		$app['session']->getFlashBag()->add('success', 'la candidature a été accepté.');
	
		return $app['twig']->render('admin/groupeSecondaire/detail.twig', array(
				'groupeSecondaire' => $groupeSecondaire));
	}	
	
	/**
	 * Retire un membre du groupe
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminRemoveMembreAction(Request $request, Application $app)
	{
		$groupeSecondaire = $request->get('groupe');
		$membre = $request->get('membre');
		
	
		$app['orm.em']->remove($membre);
		$app['orm.em']->flush();
	
		$app['session']->getFlashBag()->add('success', 'le membre a été retiré.');
	
		return $app['twig']->render('admin/groupeSecondaire/detail.twig', array(
				'groupeSecondaire' => $groupeSecondaire));
	}
	
	/**
	 * Retirer le droit de lire les secrets à un utilisateur
	 * 
	 * @param Request $request
	 * @param Applicetion $app
	 */
	public function adminSecretOffAction(Request $request, Application $app)
	{
		$groupeSecondaire = $request->get('groupe');
		$membre = $request->get('membre');
		
		$membre->setSecret(false);
		$app['orm.em']->persist($membre);
		$app['orm.em']->flush();
		
		return $app['twig']->render('admin/groupeSecondaire/detail.twig', array(
				'groupeSecondaire' => $groupeSecondaire));
	}
	
	/**
	 * Active le droit de lire les secrets à un utilisateur
	 *
	 * @param Request $request
	 * @param Applicetion $app
	 */
	public function adminSecretOnAction(Request $request, Application $app)
	{
		$groupeSecondaire = $request->get('groupe');
		$membre = $request->get('membre');
	
		$membre->setSecret(true);
		$app['orm.em']->persist($membre);
		$app['orm.em']->flush();
	
		return $app['twig']->render('admin/groupeSecondaire/detail.twig', array(
				'groupeSecondaire' => $groupeSecondaire));
	}
		
	/**
	 * Affichage à destination d'un membre du groupe secondaire
	 * @param Request $request
	 * @param Application $app
	 */
	public function joueurAction(Request $request, Application $app)
	{
		$groupeSecondaire = $request->get('groupe');
		
		$personnage = $app['user']->getPersonnage();
		$membre = $personnage->getMembre($groupeSecondaire);
		
		return $app['twig']->render('public/groupeSecondaire/detail.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'membre' => $membre,
		));		
	}	
	
	/**
	 * Accepter une candidature à un groupe secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function gestionAcceptAction(Request $request, Application $app)
	{
		$groupeSecondaire = $request->get('groupe');
		$postulant = $request->get('postulant');
		
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
			
			$app['user.mailer']->sendGroupeSecondaireAcceptMessage($personnage->getParticipant()->getUser(), $groupeSecondaire);
			
			$app['session']->getFlashBag()->add('success', 'Vous avez accepté la candidature. Un message a été envoyé au joueur concerné.');
			return $app->redirect($app['url_generator']->generate('groupeSecondaire.joueur', array('groupe' => $groupeSecondaire->getId())),301);
		}
		
		return $app['twig']->render('public/groupeSecondaire/gestion_accept.twig', array(
			'groupeSecondaire' => $groupeSecondaire,
			'postulant' => $postulant,
			'form' => $form->createView(),
		));
	}
	
	/**
	 * Accepter une candidature à un groupe secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function gestionRejectAction(Request $request, Application $app)
	{
		$groupeSecondaire = $request->get('groupe');
		$postulant = $request->get('postulant');
		
		$form = $app['form.factory']->createBuilder()
			->add('envoyer','submit', array('label' => "Refuser le postulant"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $postulant->getPersonnage();
			$app['orm.em']->remove($postulant);
			$app['orm.em']->flush();
				
			$app['user.mailer']->sendGroupeSecondaireRejectMessage($personnage()->getParticipant()->getUser(), $groupeSecondaire);
				
			$app['session']->getFlashBag()->add('success', 'Vous avez refusé la candidature. Un message a été envoyé au joueur concerné.');
			return $app->redirect($app['url_generator']->generate('groupeSecondaire.joueur', array('groupe' => $groupeSecondaire->getId())),301);
		}
		
		
		return $app['twig']->render('public/groupeSecondaire/gestion_reject.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'postulant' => $postulant,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Laisser la candidature dans les postulant
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function gestionWaitAction(Request $request, Application $app)
	{
		$groupeSecondaire = $request->get('groupe');
		$postulant = $request->get('postulant');
		
		$form = $app['form.factory']->createBuilder()
			->add('envoyer','submit', array('label' => "Laisser en attente"))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$postulant->setWaiting(true);
			$app['orm.em']->persist($postulant);
			$app['orm.em']->flush($postulant);
			
			$app['user.mailer']->sendGroupeSecondaireWaitMessage($postulant->getPersonnage()->getParticipant()->getUser(), $groupeSecondaire);
	
			$app['session']->getFlashBag()->add('success', 'La candidature reste en attente. Un message a été envoyé au joueur concerné.');
			return $app->redirect($app['url_generator']->generate('groupeSecondaire.joueur', array('groupe' => $groupeSecondaire->getId())),301);
		}
	
	
		return $app['twig']->render('public/groupeSecondaire/gestion_wait.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'postulant' => $postulant,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Répondre à un postulant
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function gestionResponseAction(Request $request, Application $app)
	{
		$groupeSecondaire = $request->get('groupe');
		$postulant = $request->get('postulant');
	
		$message = new Message();
		
		$message->setUserRelatedByAuteur($app['user']);
		$message->setUserRelatedByDestinataire($postulant->getPersonnage()->getParticipant()->getUser());
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
			
			$app['user.mailer']->sendNewMessage($message);
	
			$app['session']->getFlashBag()->add('success', 'Votre message a été envoyé au joueur concerné.');
			return $app->redirect($app['url_generator']->generate('groupeSecondaire.joueur', array('groupe' => $groupeSecondaire->getId())),301);
		}
	
	
		return $app['twig']->render('public/groupeSecondaire/gestion_response.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'postulant' => $postulant,
				'form' => $form->createView(),
		));
	}
}