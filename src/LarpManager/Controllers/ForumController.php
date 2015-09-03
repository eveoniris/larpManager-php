<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

use LarpManager\Form\PostForm;

/**
 * LarpManager\Controllers\ForumController
 *
 * @author kevin
 *
 */
class ForumController
{	
	/**
	 * Liste des topics
	 * TODO  : ne lister que les topics correspondants aux droits de l'utilisateur (territoire, gn, groupe, religion, groupe secondaire)
	 *  
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @return View $view
	 */
	public function topicAction(Request $request, Application $app)
	{
		$joueur = $app['user']->getJoueur();
		
		if ( ! $joueur )
		{
			// erreur, l'utilisateur doit avoir un joueur pour acceder aux forums
		}
		
		$topics = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')
						->findAllRelatedToJoueurReferedGns($joueur->getId());

		$view = $app['twig']->render('forum/root.twig', array(
				'topics' => $topics,

		));
		return $view;
	}
	
	/**
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function topicPostAction(Request $request, Application $app)
	{
		$topicId = $request->get('index');
		
		$joueur = $app['user']->getJoueur();
		
		if ( ! $joueur )
		{
			// erreur, l'utilisateur doit avoir un joueur pour acceder aux forums
		}
		
		$topic = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')
					->find($topicId);
		
		$view = $app['twig']->render('forum/topic.twig', array(
				'topic' => $topic,
		));
		
		return $view;
		
	}
	
	/**
	 * Formulaire d'ajout d'un post dans un topic
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function postAddFormAction(Request $request, Application $app)
	{
		$topicId = $request->get('index');
		
		$topic = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')
					->find($topicId);
		
		$post = new \LarpManager\Entities\Post();
		$post->setTopic($topic);
		
		$form = $app['form.factory']->createBuilder(new PostForm(), $post)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		return $app['twig']->render('forum/post_add.twig', array(
				'form' => $form->createView(),
				'topic' => $topic,
		));
	}
	
	/**
	 * Traitement du formulaire d'ajout d'un post dans un topic
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
				
		$form = $app['form.factory']->createBuilder(new PostForm(), $post)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
		
		/**
		 * Si la requête est invalide, renvoyer vers une page d'erreur
		 */
		if ( ! $form->isValid() )
		{
			throw new LarpManager\Exception\RequestInvalidException();
		}
		
		$post = $form->getData();
		
		$post->setTopic($topic);
		$post->setUser($app['user']);
		
		$app['orm.em']->persist($post);
		$app['orm.em']->flush();
		
		$app['session']->getFlashBag()->add('success', 'Le message a été ajouté.');
		
		return $app->redirect($app['url_generator']->generate('forum.topic',array('index'=> $topic->getId())),301);
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
		
		return $app['twig']->render('forum/post.twig', array(
				'post' => $post,
		));
	}
}