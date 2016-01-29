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
		$qb->select('Territoire, Groupes, Chronologies, Langue, Langues, Religion, Religions, Exportations, Importations')
			->from('\LarpManager\Entities\Territoire','Territoire')
			->leftJoin('Territoire.groupes', 'Groupes')
			->leftJoin('Territoire.langue', 'Langue')
			->leftJoin('Territoire.langues', 'Langues')
			->leftJoin('Territoire.chronologies', 'Chronologies')
			->leftJoin('Territoire.religion', 'Religion')
			->leftJoin('Territoire.religions', 'Religions')
			->leftJoin('Territoire.exportations', 'Exportations')
			->leftJoin('Territoire.importations', 'Importations');
			
		
		$query = $qb->getQuery();
		
		$territoires = $query->getResult(Query::HYDRATE_ARRAY);
		
		foreach ( $territoires as $key => $territoire)
		{
			$territoires[$key]['religionsArray'] = array();
			$territoires[$key]['languesArray'] = array();
			
			foreach ( $territoire['religions'] as $religion)
			{
				$territoires[$key]['religionsArray'][] = $religion['id'];
			}
			
			foreach ( $territoire['langues'] as $langue)
			{
				$territoires[$key]['languesArray'][] = $langue['id'];
			}
			
			foreach ( $territoire['importations'] as $importation)
			{
				$territoires[$key]['importationsArray'][] = $importation['id'];
			}
			
			foreach ( $territoire['exportations'] as $exportation)
			{
				$territoires[$key]['exportationsArray'][] = $exportation['id'];
			}
			
		}
		return new JsonResponse($territoires);
	}
	
	/**
	 * API : créer un nouveau territoire
	 * POST /api/territoire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function apiAddAction(Request $request, Application $app)
	{
		$territoire = new \LarpManager\Entities\Territoire();
		
		$payload = json_decode($request->getContent());
		$territoire->jsonUnserialize($payload);
		
		$app['orm.em']->persist($territoire);
		$app['orm.em']->flush();
		
		return new JsonResponse($payload);
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
		$territoire->jsonUnserialize($payload);
		
		// si la religion principale est mise à jour
		if ( isset($payload->religion_id) )
		{
			$religion = $app['orm.em']->find('\LarpManager\Entities\Religion',$payload->religion_id);
			$territoire->setReligionPrincipale($religion);
		}
		
		// si la langue principale est mise à jour
		if ( isset($payload->langue_id) )
		{
			$langue = $app['orm.em']->find('\LarpManager\Entities\Langue',$payload->langue_id);
			$territoire->setLanguePrincipale($langue);
		}
		
		// si les religions secondaires sont mises à jour
 		if ( isset($payload->religion_id_list) )
		{
			$oldReligions = $territoire->getReligions();
			foreach ( $oldReligions as $religion )
			{
				if ( ! in_array( $religion->getId(),$payload->religion_id_list)  )
				{
					$territoire->removeReligion($religion);
				}
			}
			foreach ( $payload->religion_id_list as $id )
			{
				$religion = $app['orm.em']->find('\LarpManager\Entities\Religion',$id);
				if (  ! $oldReligions->contains($religion) )
				{
					$territoire->addReligion($religion);
				}
			}
		}
		
		// si les langues secondaires sont mises à jour
		if ( isset($payload->langue_id_list) )
		{
			$oldLangues = $territoire->getLangues();
			foreach ( $oldLangues as $langue )
			{
				if ( ! in_array( $langue->getId(),$payload->langue_id_list)  )
				{
					$territoire->removeLangue($langue);
				}
			}
			foreach ( $payload->langue_id_list as $id )
			{
				$langue = $app['orm.em']->find('\LarpManager\Entities\Langue',$id);
				if (  ! $oldLangues->contains($langue) )
				{
					$territoire->addLangue($langue);
				}
			}
		}
		
		// si les importations sont mises à jour
		if ( isset($payload->importation_id_list) )
		{
			$oldImportations = $territoire->getImportations();
			foreach ( $oldImportations as $importation )
			{
				if ( ! in_array( $importation->getId(),$payload->importation_id_list)  )
				{
					$territoire->removeImportation($importation);
				}
			}
			foreach ( $payload->importation_id_list as $id )
			{
				$importation = $app['orm.em']->find('\LarpManager\Entities\Ressource',$id);
				if (  ! $oldImportations->contains($importation) )
				{
					$territoire->addImportation($importation);
				}
			}
		}
		
		// si les exportations sont mises à jour
		if ( isset($payload->exportation_id_list) )
		{
			$oldExportations = $territoire->getExportations();
			foreach ( $oldExportations as $exportation )
			{
				if ( ! in_array( $exportation->getId(),$payload->exportation_id_list)  )
				{
					$territoire->removeExportation($exportation);
				}
			}
			foreach ( $payload->exportation_id_list as $id )
			{
				$exportation = $app['orm.em']->find('\LarpManager\Entities\Ressource',$id);
				if (  ! $oldExportations->contains($exportation) )
				{
					$territoire->addExportation($exportation);
				}
			}
		}
		
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
	
	public function apiEventUpdateAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		$event = $request->get('event');
		
		$payload = json_decode($request->getContent());
		
		$event->setYear($payload->year);
		
		$app['orm.em']->persist($event);
		$app['orm.em']->flush();
		
		return new JsonResponse($payload);
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
		
		return $app['twig']->render('admin/territoire/index.twig', array('territoires' => $territoires));
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