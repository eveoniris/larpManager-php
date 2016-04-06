<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

use LarpManager\Form\ConstructionForm;
use LarpManager\Form\ConstructionDeleteForm;

/**
 * LarpManager\Controllers\ConstructionController
 *
 * @author kevin
 *
 */
class ConstructionController
{
	/**
	 * Présentation des constructions
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Construction');
		$constructions = $repo->findAll();
		
		return $app['twig']->render('admin/construction/index.twig', array(
				'constructions' => $constructions));
	}
	
	/**
	 * Ajoute une construction
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$construction = new \LarpManager\Entities\Construction();
		
		$form = $app['form.factory']->createBuilder(new ConstructionForm(), $construction)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$construction = $form->getData();
			
			$app['orm.em']->persist($construction);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La construction a été ajoutée.');
			
			return $app->redirect($app['url_generator']->generate('construction.detail', array('construction' => $construction->getId())),301);
		}
				
		return $app['twig']->render('admin/construction/add.twig', array(
				'construction' => $construction,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Modifie une construction
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$construction = $request->get('construction');
		
		$form = $app['form.factory']->createBuilder(new ConstructionForm(), $construction)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$construction = $form->getData();
				
			$app['orm.em']->persist($construction);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La construction a été modifié.');
			
			return $app->redirect($app['url_generator']->generate('construction.detail', array('construction' => $construction->getId())),301);
		}
		
		return $app['twig']->render('admin/construction/update.twig', array(
				'construction' => $construction,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Supprimme une construction
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function deleteAction(Request $request, Application $app)
	{
		$construction = $request->get('construction');
		
		$form = $app['form.factory']->createBuilder( new ConstructionDeleteForm() , $construction)
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$construction = $form->getData();
		
			$app['orm.em']->remove($construction);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'La construction a été supprimée.');
				
			return $app->redirect($app['url_generator']->generate('construction'),301);
		}
		
		return $app['twig']->render('admin/construction/delete.twig', array(
				'construction' => $construction,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Détail d'une construction
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$construction = $request->get('construction');
		
		return $app['twig']->render('admin/construction/detail.twig', array(
				'construction' => $construction));
	}
}