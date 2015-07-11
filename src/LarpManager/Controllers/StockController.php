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
class StockController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/index.twig');
	}
	
	/**
	 * @description affiche la liste des objets
	 */
	public function objetListAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		$objets = $repo->findAll();
		
		return $app['twig']->render('stock/objet/list.twig', array('objets' => $objets));
	}
	
	
	/**
	 * @description affiche la liste des proprietaire
	 */
	public function proprietaireListAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Proprietaire');
		$proprietaires = $repo->findAll();
		
		return $app['twig']->render('stock/proprietaire/list.twig', array('proprietaires' => $proprietaires));
	}
	
	/**
	 * @description affiche la liste des tags
	 */
	public function tagListAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Tag');
		$tags = $repo->findAll();
		
		return $app['twig']->render('stock/tag/list.twig', array('tags' => $tags));
	}
	
	/**
	 * @description affiche la liste des etats
	 */
	public function etatListAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etats = $repo->findAll();
		
		return $app['twig']->render('stock/etat/list.twig', array('etats' => $etats));
	}
	
	/**
	 * @description affiche la liste des localisation
	 */
	public function localisationListAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Localisation');
		$localisations = $repo->findAll();
		
		return $app['twig']->render('stock/localisation/list.twig', array('localisations' => $localisations));
	}
	
	
	
	
	/**
	 * @description affiche la détail d'un objet
	 */
	public function objetDetailAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/objet/detail.twig');
	}
	
	/**
	 * @description ajoute un objet
	 */
	public function objetAddAction(Request $request, Application $app)
	{
		// valeur par défaut lorsque le formulaire est chargé pour la premiere fois
		$defaultData = array();
				
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder('form', $defaultData)
					->add('nom','text')
					->add('code','text')
					->add('description','textarea')
					->add('photo')
					->add('localisation','entity', array('class' => 'LarpManager\Entities\Localisation', 'property' => 'label'))
					->add('etat','entity', array('class' => 'LarpManager\Entities\Etat', 'property' => 'label'))
					->add('taille','integer')
					->add('poid','integer')
					->add('couleur','choice')
					->add('proprietaire','entity', array('class' => 'LarpManager\Entities\Proprietaire', 'property' => 'nom'))
					->add('responsable','entity', array('class' => 'LarpManager\Entities\Users', 'property' => 'name'))
					->add('cout','integer')
					->add('nombre','integer')
					->add('bugdet','integer')
					->add('investissement','choice', array('choices' => array('false' =>'usage unique','true' => 'ré-utilisable')))
					->add('save','submit')
					->add('save_continue','submit', array('label' => 'Sauvegarder & continuer'))
					->getForm();
		
		// on passe la requête de l'utilisateur au formulaire
		$form->handleRequest($request);
		
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$data = $form->getData();
			
			// traitement des data
			// ...
		}
		
		return $app['twig']->render('stock/objet/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un objet
	 */
	public function objetUpdateAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/objet/update.twig');
	}
	
	/**
	 * @description Supprime un objet
	 */
	public function objetDeleteAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/objet/delete.twig');
	}
	
		
	
	
	/**
	 * @description affiche le détail d'un proprietaire
	 */
	public function proprietaireDetailAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/proprietaire/detail.twig');
	}
	
	/**
	 * @description Ajoute un proprietaire
	 */
	public function proprietaireAddAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/proprietaire/add.twig');
	}
	
	/**
	 * @description Met à jour un proprietaire
	 */
	public function proprietaireUpdateAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/proprietaire/update.twig');
	}
	
	/**
	 * @description Supprime un proprietaire
	 */
	public function proprietaireDeleteAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/proprietaire/delete.twig');
	}
	
	
	
	
	/**
	 * @description affiche la détail d'un tag
	 */
	public function tagDetailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Tag');
		$tag = $repo->find($id);
	
		return $app['twig']->render('stock/tag/detail.twig', array('tag' => $tag));
	}
	
	/**
	 * @description ajoute un tag
	 */
	public function tagAddAction(Request $request, Application $app)
	{
		// valeur par défaut lorsque le formulaire est chargé pour la premiere fois
		$defaultData = array();
		
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder('form', $defaultData)
					->add('nom','text')
					->add('save','submit')
					->getForm();
		
		// on passe la requête de l'utilisateur au formulaire
		$form->handleRequest($request);
		
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$data = $form->getData();
			
			// traitement des data
			$tag = new \LarpManager\Entities\Tag();
			$tag->setLabel($data['nom']);
			
			$app['orm.em']->persist($tag);
			$app['orm.em']->flush();
						
			return $app->redirect($app['url_generator']->generate('stock_tag_list'));
		}
		
		return $app['twig']->render('stock/tag/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un tag
	 */
	public function tagUpdateAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/tag/update.twig');
	}
	
	/**
	 * @description Supprime un tag
	 */
	public function tagDeleteAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/tag/delete.twig');
	}
	
	

	/**
	 * @description affiche le détail d'un etat
	 */
	public function etatDetailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etat = $repo->find($id);
	
		return $app['twig']->render('stock/etat/detail.twig', array('etat' => $etat));
	}
	
	/**
	 * @description ajoute un etat
	 */
	public function etatAddAction(Request $request, Application $app)
	{
		// valeur par défaut lorsque le formulaire est chargé pour la premiere fois
		$defaultData = array();
		
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder('form', $defaultData)
					->add('label','text')
					->add('save','submit')
					->getForm();
		
		// on passe la requête de l'utilisateur au formulaire
		$form->handleRequest($request);
		
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$data = $form->getData();
			
			// traitement des data
			$etat = new \LarpManager\Entities\Etat();
			$etat->setLabel($data['label']);
			
			$app['orm.em']->persist($etat);
			$app['orm.em']->flush();
						
			return $app->redirect($app['url_generator']->generate('stock_etat_list'));
		}
		
		return $app['twig']->render('stock/etat/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un etat
	 */
	public function etatUpdateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etat = $repo->find($id);
		
		$defaultData = array(
				'id' => $etat->getId(),
				'label' => $etat->getLabel()
		);
		
		$form = $app['form.factory']->createBuilder('form', $defaultData)
			->add('id','hidden')
			->add('label','text')
			->add('update','submit')
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
				
			if ( $etat->getId() == $data['id']) {
				
				$etat->setLabel($data['label']);
				$app['orm.em']->persist($etat);
				$app['orm.em']->flush();			
				
				return $app->redirect($app['url_generator']->generate('stock_etat_list'));
			}
		}
		return $app['twig']->render('stock/etat/update.twig', array(
					'etat' => $etat,
					'form' => $form->createView()));
	}
	
	/**
	 * @description Supprime un etat
	 */
	public function etatDeleteAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etat = $repo->find($id);
		
		$defaultData = array(
				'id' => $etat->getId(),
				'label' => $etat->getLabel()
		);
		
		$form = $app['form.factory']->createBuilder('form', $defaultData)
			->add('id','hidden')
			->add('label','text', array('read_only' => true))
			->add('delete','submit')
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$data = $form->getData();
			
			if ( $etat->getId() == $data['id']) {
				$app['orm.em']->remove($etat);
				$app['orm.em']->flush();
				
				return $app->redirect($app['url_generator']->generate('stock_etat_list'));
			}
			
		}
		
		return $app['twig']->render('stock/etat/delete.twig', array('etat' => $etat,'form' => $form->createView()));
	}
	
	
	/**
	 * @description affiche la détail d'une localisation
	 */
	public function localisationDetailAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/localisation/detail.twig');
	}
	
	/**
	 * @description ajoute une localisation
	 */
	public function localisationAddAction(Request $request, Application $app)
	{
		// valeur par défaut lorsque le formulaire est chargé pour la premiere fois
		$defaultData = array();
		
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder('form', $defaultData)
					->add('label','text')
					->add('precision','textarea')
					->add('save','submit')
					->getForm();
		
		// on passe la requête de l'utilisateur au formulaire
		$form->handleRequest($request);
		
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$data = $form->getData();
			
			// traitement des data
			// ...
		}
		
		return $app['twig']->render('stock/localisation/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour une localisation
	 */
	public function localisationUpdateAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/localisation/update.twig');
	}
	
	/**
	 * @description Supprime une localisation
	 */
	public function localisationDeleteAction(Request $request, Application $app)
	{
		return $app['twig']->render('stock/localisation/delete.twig');
	}
	
}