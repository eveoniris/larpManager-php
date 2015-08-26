<?php

namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

use LarpManager\Form\GnForm;

class GnController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app) 
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Gn');
		$gns = $repo->findAll();
		return $app['twig']->render('gn/index.twig', array('gns' => $gns));
	}
	
	/**
	 * @description affiche le formulaire d'ajout d'un gn
	 */
	public function addAction(Request $request, Application $app)
	{
		$gn = new \LarpManager\Entities\Gn();
	
		$form = $app['form.factory']->createBuilder(new GnForm(), $gn)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$gn = $form->getData();
				
			$app['orm.em']->persist($gn);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Le gn a été ajouté.');
	
			return $app->redirect($app['url_generator']->generate('gn'),301);
		}
	
		return $app['twig']->render('gn/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Detail d'un gn
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$gn = $app['orm.em']->find('\LarpManager\Entities\Gn',$id);
	
		if ( $gn )
		{
			return $app['twig']->render('gn/detail.twig', array('gn' => $gn));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le gn n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('gn'));
		}	
	}
	
	/**
	 * Met à jour un gn
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$gn = $app['orm.em']->find('\LarpManager\Entities\Gn',$id);
	
		$form = $app['form.factory']->createBuilder(new GnForm(), $gn)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$gn = $form->getData();
	
			if ($form->get('update')->isClicked())
			{	
				$app['orm.em']->persist($gn);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le gn a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($gn);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le gn a été supprimé.');
			}
	
			return $app->redirect($app['url_generator']->generate('gn'));
		}
	
		return $app['twig']->render('gn/update.twig', array(
				'gn' => $gn,
				'form' => $form->createView(),
		));
	}
}
