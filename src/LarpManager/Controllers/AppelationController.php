<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\AppelationForm;

/**
 * LarpManager\Controllers\AppelationController
 *
 * @author kevin
 *
 */
class AppelationController
{
	/**
	 * @description affiche le tableau de bord de gestion des appelations
	 */
	public function indexAction(Request $request, Application $app)
	{
		$appelations = $app['appelation.manager']->findAll();
		$appelations = $app['appelation.manager']->sort($appelations);
		
		return $app['twig']->render('appelation/index.twig', array('appelations' => $appelations));
	}
	
	/**
	 * Detail d'une appelation
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$appelation = $app['appelation.manager']->find($id);
		
		return $app['twig']->render('appelation/detail.twig', array('appelation' => $appelation));
	}
	
	/**
	 * Ajoute une appelation
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$appelation = new \LarpManager\Entities\Appelation();
		
		$form = $app['form.factory']->createBuilder(new AppelationForm(), $appelation)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$appelation = $form->getData();
			$app['appelation.manager']->insert($appelation);
			
			$app['session']->getFlashBag()->add('success', 'L\'appelation a été ajoutée.');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('appelation'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('appelation.add'),301);
			}
		}
		
		return $app['twig']->render('appelation/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modifie une appelation
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$appelation = $app['appelation.manager']->find($id);
		
		$form = $app['form.factory']->createBuilder(new AppelationForm(), $appelation)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$appelation = $form->getData();
		
			if ( $form->get('update')->isClicked())
			{
				$app['appelation.manager']->update($appelation);
				$app['session']->getFlashBag()->add('success', 'L\'appelation a été mise à jour.');
				
				return $app->redirect($app['url_generator']->generate('appelation.detail',array('index' => $id)),301);
			}
			else if ( $form->get('delete')->isClicked())
			{
				$app['appelation.manager']->delete($appelation);
				$app['session']->getFlashBag()->add('success', 'L\'appelation a été supprimée.');
				return $app->redirect($app['url_generator']->generate('appelation'),301);
			}
		}

		return $app['twig']->render('appelation/update.twig', array(
				'appelation' => $appelation,
				'form' => $form->createView(),
		));
	}
}