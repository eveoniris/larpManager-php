<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * LarpManager\ForumControllerProvider
 * 
 * @author kevin
 */
class ForumControllerProvider implements ControllerProviderInterface
{
	/**
	 * Initialise les routes pour les forum
	 * Routes :
	 * 	- forum.topic : affichage de tous les topics de premier niveau auquel à le droits le joueur
	 *  - forum.topic.add.form : Formulaire d'ajout d'un topic
	 *  - forum.topic.add : ajout d'un topic dans un topic
	 *  - forum.topic.update.form : Formulaire de modification d'un topic
	 *  - forum.topic.update : modification d'un topic
	 * 	- forum.topic.post : affichage des posts d'un topic
	 * 	- forum.topic.post.add.form : formulaire d'ajout d'un post dans un topic
	 * 	- forum.topic.post.add : ajout d'un post dans un topic
	 * 	- forum.post.update.form : formulaire de modification d'un post
	 * 	- forum.post.update : modification d'un post
	 *   
	 * @param Application $app
	 * @return Controllers $controllers
	 */
	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];
		
		/** liste des forums de premier niveau (qui ne sont pas des sous-forums) */
		$controllers->match('/','LarpManager\Controllers\ForumController::forumAction')
			->bind("forum")
			->method('GET');
		
		/** Ajouter un topic de premier niveau (qui n'est pas un sous-forum) */
		$controllers->match('/add','LarpManager\Controllers\ForumController::forumAddAction')
			->bind("forum.add")
			->method('GET|POST');
		
		/** detail d'un forum */
		$controllers->match('/{index}','LarpManager\Controllers\ForumController::topicAction')
			->assert('index', '\d+')
			->bind("forum.topic")
			->method('GET');
		
		/** Ajouter un sous-forum */
		$controllers->match('/{index}/add','LarpManager\Controllers\ForumController::topicAddAction')
			->assert('index', '\d+')
			->bind("forum.topic.add")
			->method('GET|POST');

		/** Ajout d'un post */
		$controllers->match('/post/add','LarpManager\Controllers\ForumController::postAddAction')
			->bind("forum.post.add")
			->method('GET|POST');
		
		/** Répondre à un post */
		$controllers->match('/post/{index}/response','LarpManager\Controllers\ForumController::postResponseAction')
			->assert('index', '\d+')
			->bind("forum.post.response")
			->method('GET|POST');
		
		/** Voir un post */
		$controllers->match('/post/{index}','LarpManager\Controllers\ForumController::postAction')
			->assert('index', '\d+')
			->bind("forum.post")
			->method('GET');

		return $controllers;
	}
}
