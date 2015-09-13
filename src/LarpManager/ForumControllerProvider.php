<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
	 * 	- forum.topic.post.add : ajout d'un post dans un topic
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
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_MODERATOR')) {
					throw new AccessDeniedException();
				}
			});

		/** detail d'un forum */
		$controllers->match('/{index}','LarpManager\Controllers\ForumController::topicAction')
			->assert('index', '\d+')
			->bind("forum.topic")
			->method('GET')
			->before(function(Request $request) use ($app) {
				$topicId = $request->get('index');
				$topic = $app['orm.em']->find('\LarpManager\Entities\Topic',$topicId);
				if (!$app['security.authorization_checker']->isGranted('TOPIC_RIGHT',$topic)) {
					throw new AccessDeniedException();
				}
			});
		
		/** Ajouter un sous-forum */
		$controllers->match('/{index}/add','LarpManager\Controllers\ForumController::topicAddAction')
			->assert('index', '\d+')
			->bind("forum.topic.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_MODERATOR')) {
					throw new AccessDeniedException();
				}
			});
			
		/** Modifier un forum */
		$controllers->match('/{index}/update','LarpManager\Controllers\ForumController::topicUpdateAction')
			->assert('index', '\d+')
			->bind("forum.topic.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
			if (!$app['security.authorization_checker']->isGranted('ROLE_MODERATOR')) {
				throw new AccessDeniedException();
			}
		});			

		/** Ajout d'un post */
		$controllers->match('/post/add','LarpManager\Controllers\ForumController::postAddAction')
			->bind("forum.post.add")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				$topicId = $request->get('index');
				$topic = $app['orm.em']->find('\LarpManager\Entities\Topic',$topicId);
				if (!$app['security.authorization_checker']->isGranted('TOPIC_RIGHT',$topic)) {
					throw new AccessDeniedException();
				}
			});
		
		/** Répondre à un post */
		$controllers->match('/post/{index}/response','LarpManager\Controllers\ForumController::postResponseAction')
			->assert('index', '\d+')
			->bind("forum.post.response")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				$postId = $request->get('index');
				$post = $app['orm.em']->find('\LarpManager\Entities\Post',$postId);
				if (!$app['security.authorization_checker']->isGranted('TOPIC_RIGHT',$post->getTopic()) ) {
					throw new AccessDeniedException();
				}
			});

		/** Modifier un post */
		$controllers->match('/post/{index}/update','LarpManager\Controllers\ForumController::postUpdateAction')
			->assert('index', '\d+')
			->bind("forum.post.update")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('POST_OWNER', $request->get('index'))) {
					throw new AccessDeniedException();
				}
			});
		
		/** Voir un post */
		$controllers->match('/post/{index}','LarpManager\Controllers\ForumController::postAction')
			->assert('index', '\d+')
			->bind("forum.post")
			->method('GET')
			->before(function(Request $request) use ($app) {
				$postId = $request->get('index');
				$post = $app['orm.em']->find('\LarpManager\Entities\Post',$postId);
				if (!$app['security.authorization_checker']->isGranted('TOPIC_RIGHT',$post->getTopic()) ) {
					throw new AccessDeniedException();
				}
			});

		/** Ajouter un sous-forum */
		$controllers->match('/post/{index}/delete','LarpManager\Controllers\ForumController::postDeleteAction')
			->assert('index', '\d+')
			->bind("forum.post.delete")
			->method('GET|POST')
			->before(function(Request $request) use ($app) {
				if (!$app['security.authorization_checker']->isGranted('ROLE_MODERATOR')) {
					throw new AccessDeniedException();
				}
			});
				
		return $controllers;
	}
}
