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
class StockProprietaireController
{
	/**
	 * @description affiche la liste des proprietaire
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Proprietaire');
		$proprietaires = $repo->findAll();
	
		return $app['twig']->render('stock/proprietaire/index.twig', array('proprietaires' => $proprietaires));
	}
	
	/**
	 * @description affiche le détail d'un proprietaire
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Proprietaire');
		$proprietaire = $repo->find($id);
	
		return $app['twig']->render('stock/proprietaire/detail.twig', array('proprietaire' => $proprietaire));
	}
	
	/**
	 * @description Ajoute un proprietaire
	 */
	public function addAction(Request $request, Application $app)
	{
		$defaultData = array();
	
		$form = $app['form.factory']->createBuilder('form', $defaultData)
		->add('nom','text')
		->add('adresse','text')
		->add('mail','text')
		->add('tel','text')
		->add('save','submit', array('attr' => array('class' => 'pure-button pure-button-primary button-primary')))
		->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
				
			$proprietaire = new \LarpManager\Entities\Proprietaire();
			$proprietaire->setNom($data['nom']);
			$proprietaire->setAdresse($data['adresse']);
			$proprietaire->setMail($data['mail']);
			$proprietaire->setTel($data['tel']);
				
			$app['orm.em']->persist($proprietaire);
			$app['orm.em']->flush();
	
			return $app->redirect($app['url_generator']->generate('stock_proprietaire_index'));
		}
	
		return $app['twig']->render('stock/proprietaire/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un proprietaire
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Proprietaire');
		$proprietaire = $repo->find($id);
	
		$defaultData = array(
				'id' => $proprietaire->getId(),
				'nom' => $proprietaire->getNom(),
				'adresse' => $proprietaire->getAdresse(),
				'mail' => $proprietaire->getMail(),
				'tel' => $proprietaire->getTel()
		);
	
		$form = $app['form.factory']->createBuilder('form', $defaultData)
		->add('id','hidden')
		->add('nom','text')
		->add('adresse','text')
		->add('mail','text')
		->add('tel','text')
		->add('update','submit', array('attr' => array('class' => 'pure-button pure-button-primary button-primary')))
		->add('delete','submit', array('attr' => array('class' => 'pure-button button-warning')))
		->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			// on récupére les data de l'utilisateur
			$data = $form->getData();
				
			if ( $proprietaire->getId() == $data['id']) {
				
				if ($form->get('update')->isClicked()) {
					$proprietaire->setNom($data['nom']);
					$proprietaire->setAdresse($data['adresse']);
					$proprietaire->setMail($data['mail']);
					$proprietaire->setTel($data['tel']);
					
					$app['orm.em']->persist($proprietaire);
					$app['orm.em']->flush();
				}
				else if ($form->get('delete')->isClicked()) {
					$app['orm.em']->remove($proprietaire);
					$app['orm.em']->flush();
				}
	
				return $app->redirect($app['url_generator']->generate('stock_proprietaire_index'));
			}
				
		}
	
		return $app['twig']->render('stock/proprietaire/update.twig', array('proprietaire' => $proprietaire,'form' => $form->createView()));
	}
}