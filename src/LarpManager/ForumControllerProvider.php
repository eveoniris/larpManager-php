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
		
		/** liste des topics */
		$controllers->match('/topic','LarpManager\Controllers\ForumController::topicAction')
			->bind("forum.topic")
			->method('GET');
		
		/** Formulaire d'ajout d'un topic */
		$controllers->match('/topic/add/form','LarpManager\Controllers\ForumController::topicAddFormAction')
			->bind("forum.topic.add.form")
			->method('GET');
		
		/** Ajout d'un topic */
		$controllers->match('/topic/add','LarpManager\Controllers\ForumController::topicAddAction')
			->bind("forum.topic.add")
			->method('POST');
		
		/** Formulaire de modification d'un topic */
		$controllers->match('/topic/{index}/update/form','LarpManager\Controllers\ForumController::topicUpdateFormAction')
			->assert('index', '\d+')
			->bind("forum.topic.update.form")
			->method('GET');
			
		/** modification d'un topic */
		$controllers->match('/topic/{index}/update','LarpManager\Controllers\ForumController::topicUpdateAction')
			->assert('index', '\d+')
			->bind("forum.topic.update")
			->method('POST');
		
		/** liste des posts dans un topic */
		$controllers->match('/topic/{index}/post','LarpManager\Controllers\ForumController::topicPostAction')
			->assert('index', '\d+')
			->bind("forum.topic.post")
			->method('GET');
		
		/** formulaire d'ajout d'un post dans un topic */
		$controllers->match('/topic/{index}/post/add','LarpManager\Controllers\ForumController::postAddFormAction')
			->assert('index', '\d+')
			->bind("forum.topic.post.add.form")
			->method('GET');
			
		/** ajout d'un post dans un topic */
		$controllers->match('/topic/{index}/post/add','LarpManager\Controllers\ForumController::postAddAction')
			->assert('index', '\d+')
			->bind("forum.topic.post.add")
			->method('POST');
		
		/** formulaire de modification d'un post */
		$controllers->match('/post/{index}/update/form','LarpManager\Controllers\ForumController::postUpdateFormAction')
			->assert('index', '\d+')
			->bind("forum.post.update.form")
			->method('GET');
		
		/** modification d'un post */
		$controllers->match('/post/{index}/update','LarpManager\Controllers\ForumController::postUpdateAction')
			->assert('index', '\d+')
			->bind("forum.post.update")
			->method('POST');
		
		return $controllers;
	}
}
