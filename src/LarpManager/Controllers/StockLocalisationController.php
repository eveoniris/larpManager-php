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
class StockLocalisationController
{
	/**
	 * @description affiche la liste des localisation
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Localisation');
		$localisations = $repo->findAll();
	
		return $app['twig']->render('stock/localisation/index.twig', array('localisations' => $localisations));
	}
	
	/**
	 * @description affiche la détail d'une localisation
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Localisation');
		$localisation = $repo->find($id);
	
		return $app['twig']->render('stock/localisation/detail.twig', array('localisation' => $localisation));
	}
	
	/**
	 * @description ajoute une localisation
	 */
	public function addAction(Request $request, Application $app)
	{
		// valeur par défaut lorsque le formulaire est chargé pour la premiere fois
		$defaultData = array();
	
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder('form', $defaultData)
		->add('label','text')
		->add('precision','textarea')
		->add('save','submit', array('attr' => array('class' => 'pure-button pure-button-primary button-primary')))
		->getForm();
	
		// on passe la requête de l'utilisateur au formulaire
		$form->handleRequest($request);
	
		// si la requête est valide
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$data = $form->getData();
				
			$localisation = new \LarpManager\Entities\Localisation();
			$localisation->setLabel($data['label']);
			$localisation->setPrecision($data['precision']);
	
			$app['orm.em']->persist($localisation);
			$app['orm.em']->flush();
				
			return $app->redirect($app['url_generator']->generate('stock_localisation_index'));
		}
	
		return $app['twig']->render('stock/localisation/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour une localisation
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Localisation');
		$localisation = $repo->find($id);
	
		$defaultData = array(
				'id' => $localisation->getId(),
				'label' => $localisation->getLabel(),
				'precision' => $localisation->getPrecision()				
		);
	
		$form = $app['form.factory']->createBuilder('form', $defaultData)
		->add('id','hidden')
		->add('label','text')
		->add('precision','textarea')
		->add('update','submit', array('attr' => array('class' => 'pure-button pure-button-primary button-primary')))
		->add('delete','submit', array('attr' => array('class' => 'pure-button button-warning')))
		->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
	
			if ( $localisation->getId() == $data['id']) {
	
				if ($form->get('update')->isClicked()) {
					$localisation->setLabel($data['label']);
					$localisation->setPrecision($data['precision']);
					$app['orm.em']->persist($localisation);
					$app['orm.em']->flush();					
				}
				else if ($form->get('delete')->isClicked()) {
					$app['orm.em']->remove($localisation);
					$app['orm.em']->flush();
				}
				
	
				return $app->redirect($app['url_generator']->generate('stock_localisation_index'));
			}
		}
		return $app['twig']->render('stock/localisation/update.twig', array(
				'localisation' => $localisation,
				'form' => $form->createView()));
	}	
}