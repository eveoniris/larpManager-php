<?php

namespace LarpManager\Controllers;

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
		// valeur par défaut lorsque le formulaire est chargé pour la premiere fois
		$defaultData = array();
	
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder('form', $defaultData)
		->add('nom','text')
		->add('code','text')
		->add('description','textarea', array('required' => false))
		->add('photo', 'file', array('required' => false, 'attr' => array( 'accept' => "image/*", 'capture' => "camera")))
		->add('localisation','entity', array('required' => false, 'class' => 'LarpManager\Entities\Localisation', 'property' => 'label'))
		->add('etat','entity', array('required' => false, 'class' => 'LarpManager\Entities\Etat', 'property' => 'label'))
		->add('taille','integer', array('required' => false))
		->add('poid','integer', array('required' => false))
		->add('couleur','choice', array('required' => false))
		->add('proprietaire','entity', array('required' => false, 'class' => 'LarpManager\Entities\Proprietaire', 'property' => 'nom'))
		->add('responsable','entity', array('required' => false, 'class' => 'LarpManager\Entities\Users', 'property' => 'name'))
		->add('cout','integer', array('required' => false))
		->add('nombre','integer', array('required' => false))
		->add('budget','integer', array('required' => false))
		->add('investissement','choice', array('choices' => array('false' =>'usage unique','true' => 'ré-utilisable')))
		->add('save','submit')
		->add('save_continue','submit')
		->getForm();
	
		// on passe la requête de l'utilisateur au formulaire
		$form->handleRequest($request);
	
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$data = $form->getData();
			
			$objet = new \LarpManager\Entities\Objet();
							
			$objet->setNom($data['nom']);
			$objet->setCode($data['code']);
			$objet->setDescription($data['description']);
			$objet->setLocalisation($data['localisation']);
			$objet->setEtat($data['etat']);			
			$objet->setProprietaire($data['proprietaire']);
			$objet->setResponsable($data['responsable']);
			$objet->setCout($data['cout']);
			$objet->setNombre($data['nombre']);
			$objet->setBudget($data['budget']);
			$objet->setInvestissement($data['investissement']);
			//$objet->setCreateur(new \LarpManager\Entities\Users()); //TODO use current connected user
			$app['orm.em']->persist($objet);
			$app['orm.em']->flush();
			
			// caractèristiques de l'objet
			$carac = new \LarpManager\Entities\ObjetCarac();
			$carac->setObjet($objet);
			$carac->setObjetId($objet->getId());
			$carac->setTaille($data['taille']);
			$carac->setPoid($data['poid']);
			$carac->setCouleur($data['couleur']);
			$app['orm.em']->persist($carac);
			
			
			// photo de l'objet
			if ( $data['photo'] ) {
				$files = $request->files->get($form->getName());
				$path = __DIR__.'/../../../web/upload/';
			
				// create a unique filename
				$date = new \Datetime('NOW');
				$hash = hash('sha1',$files['photo']->getClientOriginalName().$date->format('Y-m-d H:i:s'));
			
				// store photo on upload dir
				$files['photo']->move($path,$hash);
			
				// store file information on database
				$photo = new \LarpManager\Entities\ObjetPhoto();
				$photo->setObjet($objet);
				$photo->setObjetId($objet->getId());
				$photo->setLabel($files['photo']->getClientOriginalName());
				$photo->setCreationDate($date);
				$photo->setRealName($hash);
				$app['orm.em']->persist($photo);			
			}
			
			$app['orm.em']->flush();
			
			return $app->redirect($app['url_generator']->generate('stock_objet_index'));
		}
	
		return $app['twig']->render('stock/objet/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un objet
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		$objet = $repo->find($id);
		
		// valeur par défaut lorsque le formulaire est chargé pour la premiere fois
		$defaultData = array(
			'id' => $objet->getId(),
			'nom' => $objet->getNom(),
			'code' => $objet->getCode(),
			'description' => $objet->getDescription(),
			'photo' => $objet->getPhoto(),
			'localisation' => $objet->getLocalisation(),
			'etat' => $objet->getEtat(),
			'taille' => $objet->getTaille(),
			'poid' => $objet->getPoid(),
			'couleur' => $objet->getCouleur(),
			'proprietaire' => $objet->getProprietaire(),
			'responsable' => $objet->getResponsable(),
			'cout' => $objet->getCout(),
			'nombre' => $objet->getNombre(),
			'budget' => $objet->getBudget(),
			'investissement' => $objet->getInvestissement()
			);
	
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder('form', $defaultData)
		->add('id','hidden')
		->add('nom','text')
		->add('code','text')
		->add('description','textarea', array('required' => false))
		->add('photo', 'text', array('required' => false))
		->add('localisation','entity', array('required' => false, 'class' => 'LarpManager\Entities\Localisation', 'property' => 'label'))
		->add('etat','entity', array('required' => false, 'class' => 'LarpManager\Entities\Etat', 'property' => 'label'))
		->add('taille','integer', array('required' => false))
		->add('poid','integer', array('required' => false))
		->add('couleur','choice', array('required' => false))
		->add('proprietaire','entity', array('required' => false, 'class' => 'LarpManager\Entities\Proprietaire', 'property' => 'nom'))
		->add('responsable','entity', array('required' => false, 'class' => 'LarpManager\Entities\Users', 'property' => 'name'))
		->add('cout','integer', array('required' => false))
		->add('nombre','integer', array('required' => false))
		->add('bugdet','integer', array('required' => false))
		->add('investissement','choice', array('choices' => array('false' =>'usage unique','true' => 'ré-utilisable')))
		->add('update','submit', array('attr' => array('class' => 'pure-button pure-button-primary button-primary')))
		->add('delete','submit', array('attr' => array('class' => 'pure-button button-warning')))
		->getForm();
	
		// on passe la requête de l'utilisateur au formulaire
		$form->handleRequest($request);
	
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$data = $form->getData();
			
			if ( $objet->getId() == $data['id']) {
				
				if ($form->get('update')->isClicked()) {
					$objet->setNom($data['nom']);
					$objet->setCode($data['code']);
					$objet->setDescription($data['description']);
					$objet->setPhoto($data['photo']);
					$objet->setLocalisation($data['localisation']);
					$objet->setEtat($data['etat']);
					$objet->setTaille($data['taille']);
					$objet->setPoid($data['poid']);
					$objet->setCouleur($data['couleur']);
					$objet->setProprietaire($data['proprietaire']);
					$objet->setResponsable($data['responsable']);
					$objet->setCout($data['cout']);
					$objet->setNombre($data['nombre']);
					$objet->setBudget($data['budget']);
					$objet->setInvestissement($data['investissement']);
					
					$app['orm.em']->persist($objet);
					$app['orm.em']->flush();
				}
				else if ($form->get('delete')->isClicked()) {
					$app['orm.em']->remove($objet);
					$app['orm.em']->flush();					
				}
				
				return $app->redirect($app['url_generator']->generate('stock_objet_index'));
			}
		}
	
		return $app['twig']->render('stock/objet/update.twig', array('objet' => $objet, 'form' => $form->createView()));
	}
	
}