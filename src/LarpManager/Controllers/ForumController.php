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

use LarpManager\Form\PostForm;
use LarpManager\Form\PostDeleteForm;
use LarpManager\Form\TopicForm;

/**
 * LarpManager\Controllers\ForumController
 *
 * @author kevin
 *
 */
class ForumController
{	
	/**
	 * Liste des forums de premier niveau
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function forumAction(Request $request, Application $app)
	{
		$topics = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')
					->findAllRoot();
		
		// rechercher tous les nouveaux posts concernant l'utilisateur
		// - forums principaux
		// - forum de groupe
		// - forum de territoire
		// - forum de religion
		// - forum de groupe secondaire
		$newPosts = new ArrayCollection();
		
		// pour chacune des participations à un GN de l'utilisateur
		foreach ( $app['user']->getParticipants() as $participant )
		{
			$groupeGn = $participant->getGroupeGn();
			if ( $groupeGn )
			{
				if ( $groupeGn->getGn() )
				{
					$topicGn = $groupeGn->getGn()->getTopic(); // forum du GN
					$newPosts = new ArrayCollection(array_merge($newPosts->toArray(), $app['user']->newPosts($topicGn)->toArray()));
				}
				if ( $groupeGn->getGroupe() )
				{
					$topicGroupe = $groupeGn->getGroupe()->getTopic(); // forum du groupe
					$newPosts = new ArrayCollection(array_merge($newPosts->toArray(), $app['user']->newPosts($topicGroupe)->toArray()));
					foreach ( $groupeGn->getGroupe()->getTerritoires() as $territoire )
					{
						if ( $territoire->getTopic() )
						{
							$topicTerritoire = $territoire->getTopic(); // forum du territoire
							$newPosts = new ArrayCollection(array_merge($newPosts->toArray(), $app['user']->newPosts($topicTerritoire)->toArray()));
						}
						foreach ($territoire->getAncestors() as $ancestor ) // forum des territoires dépendants
						{
							if ( $ancestor->getTopic() )
							{
								$topicAncestor = $ancestor->getTopic(); // forum du territoire dépendant
								$newPosts = new ArrayCollection(array_merge($newPosts->toArray(), $app['user']->newPosts($topicAncestor)->toArray()));
							}	
						}
					}
				}
			}
			
			$personnage = $participant->getPersonnage();
			if ( $personnage )
			{
				foreach ( $personnage->getPersonnagesReligions() as $personnageReligion ) // toutes les religions du personnage
				{
					$religion = $personnageReligion->getReligion();
					if ( $religion->getTopic() )
					{
						$topicReligion = $religion->getTopic();
						$newPosts = new ArrayCollection(array_merge($newPosts->toArray(), $app['user']->newPosts($topicReligion)->toArray()));
					}
				}
				
				foreach ( $personnage->getMembres() as $membre) // tous les groupes secondaires du personnage
				{
					$secondaryGroup = $membre->getSecondaryGroup();
					if ( $secondaryGroup->getTopic())
					{
						$topicSecondaryGroup = $secondaryGroup->getTopic();
						$newPosts = new ArrayCollection(array_merge($newPosts->toArray(), $app['user']->newPosts($topicSecondaryGroup)->toArray()));
					}
				}
			}
			
		}
		
		$view = $app['twig']->render('forum/root.twig', array(
				'topics' => $topics,
				'newPosts' => $newPosts,
		));
		
		return $view;
		
	}
	
	/**
	 * Ajout d'un forum de premier niveau
	 * (admin uniquement)
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function forumAddAction(Request $request, Application $app)
	{
		$topic = new \LarpManager\Entities\Topic();
		
		$form = $app['form.factory']->createBuilder(new TopicForm(), $topic)
			->add('right','choice', array(
					'label' => 'Droits',
					'choices' => $app['larp.manager']->getAvailableTopicRight(),
			))
			->add('object_id','number', array(
					'required' => false,
					'label' => 'Identifiant'
			))
			->add('key','text', array(
					'required' => false,
					'label' => 'Clé'
			))
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$topic = $form->getData();
			$topic->setUser($app['user']);
			
			$app['orm.em']->persist($topic);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le forum a été ajouté.');
			
			return $app->redirect($app['url_generator']->generate('forum'),301);
		}
		
		return $app['twig']->render('forum/forum_add.twig', array(
				'form' => $form->createView(),	
		));
	}
	
	/**
	 * Détail d'un topic
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @return View $view
	 */
	public function topicAction(Request $request, Application $app)
	{
		$id = $request->get('index');

		$topic = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')->find($id);
		
		$view = $app['twig']->render('forum/topic.twig', array(
				'topic' => $topic,
		));
		return $view;
	}
		
	/**
	 * Ajout d'un post dans un topic
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function postAddAction(Request $request, Application $app)
	{
		$topicId = $request->get('index');
		
		$topic = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')
					->find($topicId);
				
		$post = new \LarpManager\Entities\Post();
		$post->setTopic($topic);
		
		$form = $app['form.factory']->createBuilder(new PostForm(), $post)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$post = $form->getData();			
			$post->setTopic($topic);
			$post->setUser($app['user']);
			$post->addWatchingUser($app['user']);
			
			// ajout de la signature
			$personnage = $app['user']->getPersonnage();
			if ( $personnage )
			{
				$text = $post->getText();
				$text .= '<address><strong>Envoyé par</strong><br />'.$personnage->getNom().' '.$personnage->getSurnom().'<address>';
				$post->setText($text);
			}
			
			$app['orm.em']->persist($post);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le message a été ajouté.');
			
			return $app->redirect($app['url_generator']->generate('forum.topic',array('index'=> $topic->getId())),301);
		}
			
		return $app['twig']->render('forum/post_add.twig', array(
				'form' => $form->createView(),
				'topic' => $topic,
		));
	}
	
	
	/**
	 * Lire un post
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function postAction(Request $request, Application $app)
	{
		$postId = $request->get('index');
		
		$post = $app['orm.em']->getRepository('\LarpManager\Entities\Post')
							->find($postId);
				
		// Mettre à jour les vues de ce post (et de toutes ces réponses)
		if ( ! $app['user']->alreadyView($post))
		{
			$postView = new \LarpManager\Entities\PostView();
			$postView->setDate(new \Datetime('NOW'));
			$postView->setUser($app['user']);
			$postView->setPost($post);
			$app['orm.em']->persist($postView);
		}
							
		foreach ( $post->getPosts() as $p)
		{
			if ( ! $app['user']->alreadyView($p))
			{
				$postView = new \LarpManager\Entities\PostView();
				$postView->setDate(new \Datetime('NOW'));
				$postView->setUser($app['user']);
				$postView->setPost($p);
				
				$app['orm.em']->persist($postView);
			}
		}
		
		$app['orm.em']->flush();
									
		return $app['twig']->render('forum/post.twig', array(
				'post' => $post,
		));
	}
	
	/**
	 * Répondre à un post
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function postResponseAction(Request $request, Application $app)
	{
		$postId = $request->get('index');
		
		$postToResponse = $app['orm.em']->getRepository('\LarpManager\Entities\Post')
							->find($postId);
		
		$post = new \LarpManager\Entities\Post();
		$post->setTitle($postToResponse->getTitle());
		
		$form = $app['form.factory']->createBuilder(new PostForm(), $post)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{			
			$post = $form->getData();
			$post->setPost($postToResponse);
			$post->setUser($app['user']);
			
			// ajout de la signature
			$personnage = $app['user']->getPersonnage();
			if ( $personnage )
			{
				$text = $post->getText();
				$text .= '<address><strong>Envoyé par</strong><br />'.$personnage->getNom().' '.$personnage->getSurnom().'<address>';
				$post->setText($text);
			}
			
			$postToResponse->addWatchingUser($app['user']);
			$app['orm.em']->persist($postToResponse);
			$app['orm.em']->persist($post);
			$app['orm.em']->flush();

			// envoie des notifications mails
			$watchingUsers = $postToResponse->getWatchingUsers();
			foreach ($watchingUsers as $user)
			{
				if ( $user == $postToResponse->getUser() ) continue;
				if ( $user == $app['user'] ) continue;
				$app['user.mailer']->sendNotificationMessage($user, $post);
			}
				
			$app['session']->getFlashBag()->add('success', 'Le message a été ajouté.');
				
			return $app->redirect($app['url_generator']->generate('forum.post',array('index'=> $postToResponse->getId())),301);
		}
	
		return $app['twig']->render('forum/post_response.twig', array(
				'form' => $form->createView(),
				'postToResponse' => $postToResponse,
		));
	}
	
	/**
	 * Modifier un post
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function postUpdateAction(Request $request, Application $app)
	{
		$postId = $request->get('index');
	
		$post = $app['orm.em']->getRepository('\LarpManager\Entities\Post')
			->find($postId);
		
		$form = $app['form.factory']->createBuilder(new PostForm(), $post)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$post = $form->getData();
			$post->setUpdateDate(new \Datetime('NOW'));
	
			$app['orm.em']->persist($post);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Le message a été modifié.');
	
			return $app->redirect($app['url_generator']->generate('forum.post',array('index'=> $post->getId())),301);
		}
	
		return $app['twig']->render('forum/post_update.twig', array(
				'form' => $form->createView(),
				'post' => $post,
		));
	}
	
	/**
	 * Active les notifications sur un post
	 * @param Request $request
	 * @param Application $app
	 */
	public function postNotificationOnAction(Request $request, Application $app)
	{
		$postId = $request->get('index');
		
		$post = $app['orm.em']->getRepository('\LarpManager\Entities\Post')
			->find($postId);
		
		$post->addWatchingUser($app['user']);
		
		$app['orm.em']->persist($post);
		$app['orm.em']->flush();
		
		$app['session']->getFlashBag()->add('success', 'Les notifications sont maintenant activées.');
		return $app->redirect($app['url_generator']->generate('forum.post',array('index'=> $post->getId())),301);
	}
	
	/**
	 * Desactive les notifications sur un post
	 * @param Request $request
	 * @param Application $app
	 */
	public function postNotificationOffAction(Request $request, Application $app)
	{
		$postId = $request->get('index');
	
		$post = $app['orm.em']->getRepository('\LarpManager\Entities\Post')
			->find($postId);
	
		$post->removeWatchingUser($app['user']);
	
		$app['orm.em']->persist($post);
		$app['orm.em']->flush();
	
		$app['session']->getFlashBag()->add('success', 'Les notifications sont maintenant desactivées.');
		return $app->redirect($app['url_generator']->generate('forum.post',array('index'=> $post->getId())),301);
	}
	
	/**
	 * Supprimer un post
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function postDeleteAction(Request $request, Application $app)
	{
		$postId = $request->get('index');
		
		$post = $app['orm.em']->getRepository('\LarpManager\Entities\Post')
			->find($postId);
		
		$form = $app['form.factory']->createBuilder(new PostDeleteForm(), $post)
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$post = $form->getData();
			
			if ( $post->isRoot() )
			{				
				$url = $app['url_generator']->generate('forum.topic',array('index'=> $post->getTopic()->getId()));
			}
			else
			{
				$ancestor = $post->getAncestor();			
				$url = $app['url_generator']->generate('forum.post',array('index'=> $ancestor->getId()));
			}
			
			// supprimer toutes les vues
			foreach ( $post->getPostViews() as $view )
			{
				$app['orm.em']->remove($view);
			}
			
			
			// supprimer tous les posts qui en dépendent
			foreach ( $post->getPosts() as $child)
			{
				foreach ( $child->getPostViews() as $view )
				{
					$app['orm.em']->remove($view);
				}
					
				$app['orm.em']->remove($child);
			}

			
			$app['orm.em']->remove($post);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le message a été supprimé.');
			
			return $app->redirect($url,301);
		}
		
		return $app['twig']->render('forum/post_delete.twig', array(
				'form' => $form->createView(),
				'post' => $post,
		));
	}
	
	/**
	 * Ajouter un sous-forum
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function topicAddAction(Request $request, Application $app)
	{
		$topicId = $request->get('index');
		
		$topicRelated = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')
			->find($topicId);
		
		$topic = new \LarpManager\Entities\Topic();
		
		$form = $app['form.factory']->createBuilder(new TopicForm(), $topic)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$topic = $form->getData();
			$topic->setTopic($topicRelated);
			$topic->setUser($app['user']);
			$topic->setRight($topicRelated->getRight());
			$topic->setObjectId($topicRelated->getObjectId());
		
			$app['orm.em']->persist($topic);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'Le forum a été ajouté.');
		
			return $app->redirect($app['url_generator']->generate('forum.topic',array('index'=> $topic->getId())),301);
		}
		
		return $app['twig']->render('forum/topic_add.twig', array(
				'form' => $form->createView(),
				'topic' => $topicRelated,
		));
	}
	
	/**
	 * Modfifier un topic
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function topicUpdateAction(Request $request, Application $app)
	{
		$topicId = $request->get('index');
	
		$topic = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')
						->find($topicId);
	
		$formBuilder = $app['form.factory']->createBuilder(new TopicForm(), $topic);
		
		if ( $app['security.authorization_checker']->isGranted('ROLE_MODERATOR'))
		{
			$formBuilder->add('topic','entity', array(
					'required' => false,
					'label' => 'Choisissez le topic parent',
					'property' => 'title',
					'class' => 'LarpManager\Entities\Topic'
				));
		}
		$form =	$formBuilder->add('save','submit', array('label' => "Sauvegarder"))
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$topic = $form->getData();
			$app['orm.em']->persist($topic);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Le forum a été modifié.');
	
			return $app->redirect($app['url_generator']->generate('forum.topic',array('index'=> $topic->getId())),301);
		}
	
		return $app['twig']->render('forum/topic_update.twig', array(
				'form' => $form->createView(),
				'topic' => $topic,
		));
	}
}