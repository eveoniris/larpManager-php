<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;
use LarpManager\Form\TerritoireForm;
use Doctrine\ORM\Query;

/**
 * LarpManager\Controllers\TerritoireController
 *
 * @author kevin
 *
 */
class TerritoireController
{
	/**
	 * API: fourni la liste des territoires
	 * GET /api/territoire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function apiListAction(Request $request, Application $app)
	{
		$qb = $app['orm.em']->createQueryBuilder();
		$qb->select('Territoire, Groupes, Langue, Religion')
			->from('\LarpManager\Entities\Territoire','Territoire')
			->leftJoin('Territoire.groupes', 'Groupes')
			->leftJoin('Territoire.langue', 'Langue')
			->leftJoin('Territoire.religion', 'Religion');
			
		
		$query = $qb->getQuery();
		
		$territoires = $query->getResult(Query::HYDRATE_ARRAY);
		return new JsonResponse($territoires);
	}
	
	/**
	 * API : mettre à jour un territoire
	 * POST /api/territoire/{territoire}
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function apiUpdateAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		
		$payload = json_decode($request->getContent());
		
		$territoire->setNom($payload->nom);
		$territoire->setDescription($payload->description);
		$territoire->setCapitale($payload->capitale);
		$territoire->setPolitique($payload->politique);
		$territoire->setDirigeant($payload->dirigeant);
		
		$app['orm.em']->persist($territoire);
		$app['orm.em']->flush();
		
				
		return new JsonResponse($payload);
	}
	
	/**
	 * Retourne tous les événements lié à un territoire
	 * @param Request $request
	 * @param Application $app
	 */
	public function apiEventListAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');		
		return new JsonResponse($territoire->getChronologies()->toArray());
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
		
		$payload = json_decode($request->getContent());
		$event  = new \LarpManager\Entities\Chronologie();
		
		$event->setTerritoire($territoire);
		$event->setYear($payload->year);
		$event->setMonth($payload->month);
		$event->setDay($payload->day);
		$event->setDescription($payload->description);
		$event->setVisibilite($payload->visibilite);
		
		$app['orm.em']->persist($event);
		$app['orm.em']->flush();
		
		return new JsonResponse($event);
	}
	
	/**
	 * Récupére le détail d'un evenmeent particulier
	 * @param Request $request
	 * @param Application $app
	 */
	public function eventDetailAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		$event = $request->get('event');
		
		return new JsonResponse($event);
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
		
		$payload = json_decode($request->getContent());
		
		$event->setTerritoire($territoire);
		$event->setYear($payload->year);
		$event->setMonth($payload->month);
		$event->setDay($payload->day);
		$event->setDescription($payload->description);
		$event->setVisibilite($payload->visibilite);
		
		$app['orm.em']->persist($event);
		$app['orm.em']->flush();
		
		return new JsonResponse($event);
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
		
		$app['orm.em']->remove($event);
		$app['orm.em']->flush();
		return '';
	}
	
	
	/**
	 * Liste des territoires
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$territoires = $app['orm.em']->getRepository('\LarpManager\Entities\Territoire')->findAll();
		$territoires = $app['larp.manager']->sortTerritoire($territoires);
		
		return $app['twig']->render('territoire/index.twig', array('territoires' => $territoires));
	}
	
	/**
	 * Detail d'un territoire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$territoire = $app['orm.em']->find('\LarpManager\Entities\Territoire',$id);
		
		if ( $app['security.authorization_checker']->isGranted('ROLE_SCENARISTE') )
		{		
			return $app['twig']->render('territoire/detail.twig', array(
					'territoire' => $territoire
			));
		}
		else
		{
			return $app['twig']->render('territoire/detail_joueur.twig', array('territoire' => $territoire));
		}
	}
	
	public function addEvent(Request $request, Application $app)
	{
		
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
			 * Création du topic associés à ce territoire
			 * @var \LarpManager\Entities\Topic $topic
			 */
			$topic = new \LarpManager\Entities\Topic();
			$topic->setTitle($territoire->getNom());
			$topic->setDescription($territoire->getDescription());
			$topic->setUser($app['user']);
			// défini les droits d'accés à ce forum
			// (les membres du territoire ont le droit d'accéder à ce forum)
			$topic->setRight('TERRITOIRE_MEMBER');
				
			$territoire->setTopic($topic);
				
			$app['orm.em']->persist($topic);
			$app['orm.em']->persist($territoire);
			$app['orm.em']->flush();
			
			$topic->setObjectId($territoire->getId());
			$app['orm.em']->persist($topic);
			$app['orm.em']->flush();
									
			$app['session']->getFlashBag()->add('success', 'Le territoire a été ajouté.');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('territoire'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('territoire.add'),301);
			}
		}
		
		return $app['twig']->render('territoire/add.twig', array(
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
		$id = $request->get('index');
		
		$territoire = $app['orm.em']->find('\LarpManager\Entities\Territoire',$id);
		
		$form = $app['form.factory']->createBuilder(new TerritoireForm(), $territoire)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$territoire = $form->getData();
			
			if ( $form->get('update')->isClicked())
			{
				
				$app['orm.em']->persist($territoire);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le territoire a été mis à jour.');
		
				return $app->redirect($app['url_generator']->generate('territoire.detail',array('index' => $id)),301);
			}
			else if ( $form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($territoire);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le territoire a été supprimé.');
				return $app->redirect($app['url_generator']->generate('territoire'),301);
			}
		}		

		return $app['twig']->render('territoire/update.twig', array(
				'territoire' => $territoire,
				'form' => $form->createView(),
		));
	}
}