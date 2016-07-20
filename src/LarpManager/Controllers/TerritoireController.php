<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\TerritoireForm;
use LarpManager\Form\TerritoireDeleteForm;
use LarpManager\Form\TerritoireStrategieForm;
use LarpManager\Form\TerritoireIngredientsForm;
use LarpManager\Form\TerritoireBlasonForm;

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
		$territoires = $app['orm.em']->getRepository('\LarpManager\Entities\Territoire')->findRoot();
				
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
	 * Ajoute une construction dans un territoire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function constructionAddAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
				
		$form = $app['form.factory']->createBuilder()
			->add('construction','entity', array(
					'label' => 'Choisissez la construction',
					'required' => true,
					'class' => 'LarpManager\Entities\Construction',
					'property' => 'fullLabel',
					'expanded' => true,
			))
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			$territoire->addConstruction($data['construction']);
			$app['orm.em']->persist($territoire);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La construction a été ajoutée.');
			return $app->redirect($app['url_generator']->generate('territoire.admin.detail',array('territoire' => $territoire->getId())),301);
		}
		
		return $app['twig']->render('admin/territoire/addConstruction.twig', array(
				'territoire' => $territoire,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Retire une construction d'un territoire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function constructionRemoveAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		$construction = $request->get('construction');
		
		$form = $app['form.factory']->createBuilder()
			->add('save','submit', array('label' => "Retirer la construction"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$data = $form->getData();
				
			$territoire->removeConstruction($construction);
			$app['orm.em']->persist($territoire);
			$app['orm.em']->flush();
					
			$app['session']->getFlashBag()->add('success', 'La construction a été retiré.');
			return $app->redirect($app['url_generator']->generate('territoire.admin.detail',array('territoire' => $territoire->getId())),301);
		}
			
		return $app['twig']->render('admin/territoire/removeConstruction.twig', array(
				'territoire' => $territoire,
				'construction' => $construction,
				'form' => $form->createView(),
		));	
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
			
			/**
			 * Création des topics associés à ce groupe
			 * un topic doit être créé par GN auquel ce groupe est inscrit
			 * @var \LarpManager\Entities\Topic $topic
			 */
			$topic = new \LarpManager\Entities\Topic();
			$topic->setTitle($territoire->getNom());
			$topic->setDescription($territoire->getDescription());
			$topic->setUser($app['user']);
			// défini les droits d'accés à ce forum
			// (les membres du groupe ont le droit d'accéder à ce forum)
			$topic->setRight('TERRITOIRE_MEMBER');
			$topic->setTopic($app['larp.manager']->findTopic('TOPIC_TERRITOIRE'));
				
			$territoire->setTopic($topic);
				
			$app['orm.em']->persist($topic);
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
	 * Mise à jour de la liste des ingrédients fourni par un territoire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateIngredientsAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		
		$form = $app['form.factory']->createBuilder(new TerritoireIngredientsForm(), $territoire)
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
		
		return $app['twig']->render('admin/territoire/updateIngredients.twig', array(
				'territoire' => $territoire,
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
	 * Met à jour le blason d'un territoire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateBlasonAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		
		$form = $app['form.factory']->createBuilder(new TerritoireBlasonForm(), $territoire)
			->add('update','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$files = $request->files->get($form->getName());
			
			$path = __DIR__.'/../../../private/img/blasons/';
			$filename = $files['blason']->getClientOriginalName();
			$extension = $files['blason']->guessExtension();
				
			if (!$extension || ! in_array($extension, array('png', 'jpg', 'jpeg','bmp'))) {
				$app['session']->getFlashBag()->add('error','Désolé, votre image ne semble pas valide (vérifiez le format de votre image)');
				return $app->redirect($app['url_generator']->generate('territoire.admin.detail', array('territoire' => $territoire->getId())),301);
			}
			
			$blasonFilename = hash('md5',$app['user']->getUsername().$filename . time()).'.'.$extension;
			
			$image = $app['imagine']->open($files['blason']->getPathname());
			$image->resize($image->getSize()->widen(160));
			$image->save($path. $blasonFilename);
			
			$territoire->setBlason($blasonFilename);
			$app['orm.em']->persist($territoire);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le blason a été enregistré');
			return $app->redirect($app['url_generator']->generate('territoire.admin.detail', array('territoire' => $territoire->getId())),301);
		}
		
		return $app['twig']->render('admin/territoire/blason.twig', array(
				'territoire' => $territoire,
				'form' => $form->createView(),
		));
	}
		
	/**
	 * Modifie le jeu strategique d'un territoire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateStrategieAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
	
		$form = $app['form.factory']->createBuilder(new TerritoireStrategieForm(), $territoire)
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
	
		return $app['twig']->render('admin/territoire/updateStrategie.twig', array(
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