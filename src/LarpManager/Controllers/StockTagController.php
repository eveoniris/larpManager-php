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
class StockTagController
{

	/**
	 * @description affiche la liste des tags
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Tag');
		$tags = $repo->findAll();
	
		return $app['twig']->render('stock/tag/index.twig', array('tags' => $tags));
	}
	
	/**
	 * @description ajoute un tag
	 */
	public function addAction(Request $request, Application $app)
	{
		// valeur par défaut lorsque le formulaire est chargé pour la premiere fois
		$defaultData = array();
	
		// preparation du formulaire
		$form = $app['form.factory']->createBuilder('form', $defaultData)
		->add('nom','text')
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
			$tag = new \LarpManager\Entities\Tag();
			$tag->setNom($data['nom']);
				
			$app['orm.em']->persist($tag);
			$app['orm.em']->flush();
	
			return $app->redirect($app['url_generator']->generate('stock_tag_index'));
		}
	
		return $app['twig']->render('stock/tag/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * @description Met à jour un tag
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
			
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Tag');
		$tag = $repo->find($id);
	
		$defaultData = array(
				'id' => $tag->getId(),
				'nom' => $tag->getNom()
		);
	
		$form = $app['form.factory']->createBuilder('form', $defaultData)
		->add('id','hidden')
		->add('nom','text')
		->add('update','submit', array('attr' => array('class' => 'pure-button pure-button-primary button-primary')))
		->add('delete','submit', array('attr' => array('class' => 'pure-button button-warning')))
		->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
	
			if ( $tag->getId() == $data['id']) {
	
				if ($form->get('update')->isClicked()) {
					$tag->setNom($data['nom']);
					$app['orm.em']->persist($tag);
					$app['orm.em']->flush();
				}
				else if ($form->get('delete')->isClicked()) {
					$app['orm.em']->remove($tag);
					$app['orm.em']->flush();
				}
	
				return $app->redirect($app['url_generator']->generate('stock_tag_index'));
			}
		}
		return $app['twig']->render('stock/tag/update.twig', array(
				'tag' => $tag,
				'form' => $form->createView()));
	}
}