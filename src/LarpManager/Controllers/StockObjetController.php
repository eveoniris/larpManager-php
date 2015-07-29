<?php

namespace LarpManager\Controllers;

use LarpManager\Form\Type\ObjetType;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

/**
 * Gestion du stock
 * @author kevin
 */
class StockObjetController
{
	
	private function addWhere($qb, $searchPhrase, $searchType) {
		if ( $searchPhrase !== null ) {
				
			switch($searchType) {
				case 'tag':
					break;
				case 'code':
					$qb->where("objet.code LIKE :search");
					break;
				default:
					$qb->where("objet.nom LIKE :search");
					break;
			}
				
			$qb->setParameter('search','%'.$searchPhrase.'%');
		}
		return $qb;
	}

	/**
	 * Fetch objet from database with user constraint
	 * 
	 * @param array $params
	 */
	private function getObjets(Array $params, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		
		// recherche du nombre d'objet total
		$qb = $repo->createQueryBuilder('objet')
					->select('COUNT(objet)');
		$qb = $this->addWhere($qb, $params['searchPhrase'], $params['searchType']);
		$objetCount = $qb->getQuery()->getSingleScalarResult();

		
		// selection des objets
		$qb = $repo->createQueryBuilder('objet');		
		$qb = $this->addWhere($qb, $params['searchPhrase'], $params['searchType']); 
		
		// tri
		if ( $params['sort'] ) {
			foreach ( $params['sort'] as $sort => $order ) {
				$qb->orderBy('objet.'.$sort, $order);
			}
		}

		// pagination
		if ( $params['rowCount'] != -1 ) {	
			$qb->setFirstResult( ($params['current'] -1 )* $params['rowCount'] );
			$qb->setMaxResults( $params['rowCount'] );
		}
		
		return array(
				'objets' => $qb->getQuery()->getResult(),
				'total' => $objetCount,
				);
	}
	
	/**
	 * @description affiche la liste des objets
	 */
	public function indexAction(Request $request, Application $app)
	{
		if ( $request->isXmlHttpRequest() ) {
			
			$params = array(
				'current' => $request->get('current'),
				'rowCount' => $request->get('rowCount'),
				'searchPhrase' => $request->get('searchPhrase'),
				'searchType' => $request->get('searchType'),
				'sort' => $request->get('sort'),
				'thumbnail' => $request->get('thumbnail'),
			);
			
			$data = $this->getObjets($params, $app);
			
			// create thnumbnail if requested
			$thumbnails = array();
			if ( $params['thumbnail'] ) {
				foreach ( $data['objets'] as $objet ) {
					$thumbnails[] = $app['twig']->render('stock/objet/thumbnail.twig', array('objet' => $objet));
				}
			}
			
			return $app->json(array(
					'current' => $params['current'],
					'rowCount' => $params['rowCount'],
					'total' => $data['total'],
					'rows' => $data['objets'],
					'thumbnails' => $thumbnails
					),200);
		}	
		
		// creation du formulaire de recherche/tri
		$form = $app['form.factory']->createBuilder('form', array())
			->add('search','text')
			->add('save','submit')
			->getForm();
		
		return $app['twig']->render('stock/objet/index.twig', array('form_search' => $form->createView()));
	}

	/**
	 * @description affiche la détail d'un objet
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		$objet = $repo->find($id);
	
		return $app['twig']->render('stock/objet/detail.twig', array('objet' => $objet));
	}
	
	/**
	 * @description ajoute un objet
	 */
	public function addAction(Request $request, Application $app)
	{
		$objet = new \LarpManager\Entities\Objet();
		
		$objet->setNombre(1);
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etat = $repo->find(1);
		if ( $etat ) $objet->setEtat($etat);
		
		$form = $app['form.factory']->createBuilder(new ObjetType(), $objet)
				->add('save','submit', array('label' => 'Sauvegarder et fermer'))
				->add('save_continue','submit',array('label' => 'Sauvegarder et nouveau'))
				->add('save_clone','submit',array('label' => 'Sauvegarder et cloner'))
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$objet = $form->getData();
			
			if ($objet->getObjetCarac() ) 
			{
				$app['orm.em']->persist($objet->getObjetCarac());
			}

			if ( $objet->getPhoto() )
			{
				$objet->getPhoto()->upload();
				$app['orm.em']->persist($objet->getPhoto());
			}
			
			$app['orm.em']->persist($objet);				
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'L\'objet a été ajouté dans le stock');
			
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('stock_objet_index'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('stock_objet_add'),301);
			}
			else if ( $form->get('save_clone')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('stock_objet_clone', array('index' => $objet->getId())),301);
			}
			
		}
	
		return $app['twig']->render('stock/objet/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * Créé un objet à partir d'un autre
	 * @param Request $request
	 * @param Application $app
	 */
	public function cloneAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		$objet = $repo->find($id);

		$form = $app['form.factory']->createBuilder(new ObjetType(), $objet)
			->add('save','submit', array('label' => 'Sauvegarder et fermer'))
			->add('save_clone','submit',array('label' => 'Sauvegarder et cloner'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$objet = $form->getData();
			$newObjet = clone $objet;
			$newObjet->setId(null);
			
			if ($newObjet->getObjetCarac() )
			{
				$app['orm.em']->persist($newObjet->getObjetCarac());
			}
		
			if ( $newObjet->getPhoto() )
			{
				$newObjet->getPhoto()->upload();
				$app['orm.em']->persist($newObjet->getPhoto());
			}
				
			$app['orm.em']->persist($newObjet);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'L\'objet a été ajouté dans le stock');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('stock_objet_index'),301);
			}
			else
			{
				return $app->redirect($app['url_generator']->generate('stock_objet_clone', array('index' => $newObjet->getId())),301);
			}
		}
		
		return $app['twig']->render('stock/objet/clone.twig', array('objet' => $objet, 'form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un objet
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		$objet = $repo->find($id);
		
		
		$form = $form = $app['form.factory']->createBuilder(new ObjetType(), $objet)
				->add('update','submit', array('label' => "Sauvegarder et fermer"))
				->add('delete','submit', array('label' => "Supprimer"))
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$objet = $form->getData();
				
			if ($form->get('update')->isClicked()) 
			{					
				if ($objet->getObjetCarac() )
				{
					$app['orm.em']->persist($objet->getObjetCarac());
				}
				
				if ( $objet->getPhoto() )
				{
					$objet->getPhoto()->upload();
					$app['orm.em']->persist($objet->getPhoto());
				}

				$app['orm.em']->persist($objet);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'L\'objet a été mis à jour');
			}
			else if ($form->get('delete')->isClicked()) 
			{
				$app['orm.em']->remove($objet);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'L\'objet a été supprimé');
			}
			
			return $app->redirect($app['url_generator']->generate('stock_objet_index'));
		}
	
		return $app['twig']->render('stock/objet/update.twig', array('objet' => $objet, 'form' => $form->createView()));
	}
	
	/**
	 * @description Exporte la liste des objets en fichier csv.
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function exportAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		$objets = $repo->findAll();
		
		
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=eveoniris_stock_".date("Ymd").".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$output = fopen("php://output", "w");
		
		// header
		fputcsv($output,
					array(
						'nom',
						'code',
						'description',
						'photo',
						'rangement',
						'etat',
						'proprietaire',
						'responsable',
						'nombre',
						'creation_date'), ',');
		
		foreach ($objets as $objet)
		{
			fputcsv($output, $objet->getExportValue(), ',');			
		}
		
		fclose($output);
		exit();
		
	}
	
	
}