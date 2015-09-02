<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

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

		$view = $app['twig']->render('forum/topic.twig', array(
				'topic' => null,
				'topics' => $topics,
				'posts' => null,
				'parent' => null
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
				'topics' => $topic->getTopics(),
				'posts' => $topic->getPosts(),
				'parent' => $topic->getTopic(),
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
		
	}
	
	/**
	 * Traitement du formulaire d'ajout d'un post dans un topic
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function postAddAction(Request $request, Application $app)
	{
	
	}
}