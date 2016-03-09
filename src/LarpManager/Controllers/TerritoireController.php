<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\TerritoireForm;
use LarpManager\Form\TerritoireDeleteForm;

/**
 * LarpManager\Controllers\TerritoireController
 *
 * @author kevin
 */
class TerritoireController
{
	/**
	 * Liste des territoires
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$territoires = $app['orm.em']->getRepository('\LarpManager\Entities\Territoire')->findAll();
		$territoires = $app['larp.manager']->sortTerritoire($territoires);
		
		return $app['twig']->render('admin/territoire/list.twig', array('territoires' => $territoires));
	}
	
	/**
	 * Detail d'un territoire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		
		return $app['twig']->render('admin/territoire/detail.twig', array('territoire' => $territoire));
	}
	
	
	/**
	 * Ajoute un territoire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$territoire = new \LarpManager\Entities\Territoire();
		
		$form = $app['form.factory']->createBuilder(new TerritoireForm(), $territoire)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$territoire = $form->getData();
			
			$app['orm.em']->persist($territoire);
			$app['orm.em']->flush();
											
			$app['session']->getFlashBag()->add('success', 'Le territoire a été ajouté.');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('territoire.admin.list'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('territoire.admin.add'),301);
			}
		}
		
		return $app['twig']->render('admin/territoire/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modifie un territoire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
				
		$form = $app['form.factory']->createBuilder(new TerritoireForm(), $territoire)
			->add('update','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$territoire = $form->getData();
			
			$app['orm.em']->persist($territoire);
			$app['orm.em']->flush();
			$app['session']->getFlashBag()->add('success', 'Le territoire a été mis à jour.');
		
			return $app->redirect($app['url_generator']->generate('territoire.admin.detail',array('territoire' => $territoire->getId())),301);
		}		

		return $app['twig']->render('admin/territoire/update.twig', array(
				'territoire' => $territoire,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supression d'un territoire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function deleteAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		
		$form = $app['form.factory']->createBuilder(new TerritoireDeleteForm(), $territoire)
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$territoire = $form->getData();
			
			$app['orm.em']->remove($territoire);
			$app['orm.em']->flush();
			$app['session']->getFlashBag()->add('success', 'Le territoire a été supprimé.');
			return $app->redirect($app['url_generator']->generate('territoire.admin.list'),301);
		}
		
		return $app['twig']->render('admin/territoire/delete.twig', array(
				'territoire' => $territoire,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Ajout d'un topic pour un territoire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addTopicAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		
		$topic = new \LarpManager\Entities\Topic();
		$topic->setTitle($territoire->getNom());
		$topic->setDescription($territoire->getDescription());
		$topic->setUser($app['user']);
		$topic->setRight('TERRITOIRE_MEMBER');
		$topic->setObjectId($territoire->getId());
		$topic->addTerritoire($territoire);
		$topic->setTopic($app['larp.manager']->findTopic('TOPIC_TERRITOIRE'));
		
		$territoire->setTopic($topic);

		$app['orm.em']->persist($topic);
		$app['orm.em']->persist($territoire);
		$app['orm.em']->flush();
		
		$app['session']->getFlashBag()->add('success', 'Le topic a été ajouté.');
		return $app->redirect($app['url_generator']->generate('territoire.admin.detail', array('territoire' => $territoire->getId())),301);
	}
	
	/**
	 * Supression d'un topic pour un territoire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function deleteTopicAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		
		$topic = $territoire->getTopic();
		
		if ( $topic)
		{
			$territoire->setTopic(null);
			
			$app['orm.em']->persist($territoire);
			$app['orm.em']->remove($topic);
			$app['orm.em']->flush();
		}
		
		$app['session']->getFlashBag()->add('success', 'Le topic a été supprimé.');
		return $app->redirect($app['url_generator']->generate('territoire.admin.detail', array('territoire' => $territoire->getId())),301);
	}
	
	/**
	 * Ajoute un événement à un territoire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function eventAddAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		$event = $request->get('event');

		$event = new \LarpManager\Entities\Chronologie();
		
		$form = $app['form.factory']->createBuilder(new EventForm(), $event)
			->add('add','submit', array('label' => "Ajouter"))
			->getForm();

		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$event = $form->getData();
					
			$app['orm.em']->persist($event);
			$app['orm.em']->flush();
			$app['session']->getFlashBag()->add('success', 'L\'evenement a été ajouté.');
			return $app->redirect($app['url_generator']->generate('territoire.admin.detail', array('territoire' => $territoire->getId())),301);
		}
			
		return $app['twig']->render('admin/territoire/addEvent.twig', array(
				'territoire' => $territoire,
				'event' => $event,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour un événement
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function eventUpdateAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		$event = $request->get('event');

		$form = $app['form.factory']->createBuilder(new ChronologieForm(), $event)
			->add('update','submit', array('label' => "Mettre à jour"))
			->getForm();

		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$event = $form->getData();
					
			$app['orm.em']->persist($event);
			$app['orm.em']->flush();
			$app['session']->getFlashBag()->add('success', 'L\'evenement a été modifié.');
			return $app->redirect($app['url_generator']->generate('territoire.admin.detail', array('territoire' => $territoire->getId())),301);
		}
			
		return $app['twig']->render('admin/territoire/updateEvent.twig', array(
				'territoire' => $territoire,
				'event' => $event,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime un événement
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function eventDeleteAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		$event = $request->get('event');

		$form = $app['form.factory']->createBuilder(new ChronologieDeleteForm(), $event)
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();

		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$event = $form->getData();
					
			$app['orm.em']->remove($event);
			$app['orm.em']->flush();
			$app['session']->getFlashBag()->add('success', 'L\'evenement a été supprimé.');
			return $app->redirect($app['url_generator']->generate('territoire.admin.detail', array('territoire' => $territoire->getId())),301);
		}
			
		return $app['twig']->render('admin/territoire/deleteEvent.twig', array(
				'territoire' => $territoire,
				'event' => $event,
				'form' => $form->createView(),
		));
	}	
}