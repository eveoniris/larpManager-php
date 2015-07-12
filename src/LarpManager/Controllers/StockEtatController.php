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
class StockEtatController
{

	/**
	 * @description affiche la liste des etats
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etats = $repo->findAll();
	
		return $app['twig']->render('stock/etat/index.twig', array('etats' => $etats));
	}

	/**
	 * @description affiche le détail d'un etat
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etat = $repo->find($id);
	
		return $app['twig']->render('stock/etat/detail.twig', array('etat' => $etat));
	}
	
	/**
	 * @description ajoute un etat
	 */
	public function addAction(Request $request, Application $app)
	{
		// valeur par défaut lorsque le formulaire est chargé pour la premiere fois
		$defaultData = array();
	
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder('form', $defaultData)
		->add('label','text')
		->add('save','submit', array('attr' => array('class' => 'pure-button pure-button-primary button-primary')))
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
	
			return $app->redirect($app['url_generator']->generate('stock_etat_index'));
		}
	
		return $app['twig']->render('stock/etat/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un etat
	 */
	public function updateAction(Request $request, Application $app)
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
		->add('update','submit', array('attr' => array('class' => 'pure-button pure-button-primary button-primary')))
		->add('delete','submit', array('attr' => array('class' => 'pure-button button-warning')))
		->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
	
			if ( $etat->getId() == $data['id']) {
	
				if ($form->get('update')->isClicked()) {
					$etat->setLabel($data['label']);
					$app['orm.em']->persist($etat);
					$app['orm.em']->flush();					
				}
				else if ($form->get('delete')->isClicked()) {
					$app['orm.em']->remove($etat);
					$app['orm.em']->flush();
				}
				
				return $app->redirect($app['url_generator']->generate('stock_etat_index'));
			}
		}
		return $app['twig']->render('stock/etat/update.twig', array(
				'etat' => $etat,
				'form' => $form->createView()));
	}	
}