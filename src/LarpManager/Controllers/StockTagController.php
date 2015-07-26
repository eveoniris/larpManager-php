<?php

namespace LarpManager\Controllers;

use LarpManager\Form\Type\TagType;

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
		$tag = new \LarpManager\Entities\Tag();
	
		$form = $app['form.factory']->createBuilder(new TagType(), $tag)
				->add('save','submit')
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$tag = $form->getData();				
			$app['orm.em']->persist($tag);
			$app['orm.em']->flush();

			$app['session']->getFlashBag()->add('success', 'Le tag a été ajouté.');
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
	
		$form = $app['form.factory']->createBuilder(new TagType(), $tag)
			->add('update','submit')
			->add('delete','submit')
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$tag = $form->getData();
	
			if ($form->get('update')->isClicked()) 
			{
				$app['orm.em']->persist($tag);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Le tag a été modifié.');
			}
			else if ($form->get('delete')->isClicked()) 
			{
				$app['orm.em']->remove($tag);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Le tag a été supprimé.');
			}
	
			return $app->redirect($app['url_generator']->generate('stock_tag_index'));
		}
		
		return $app['twig']->render('stock/tag/update.twig', array(
				'tag' => $tag,
				'form' => $form->createView()));
	}
}