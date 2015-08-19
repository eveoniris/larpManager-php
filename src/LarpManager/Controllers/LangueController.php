<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use LarpManager\Form\LangueForm;


class LangueController
{
	/**
	 * @description affiche la liste des langues
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Langue');
		$langues = $repo->findAll();
		
		return $app['twig']->render('langue/index.twig', array('langues' => $langues));
	}
	
	/**
	 * Detail d'une langue
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$langue = $app['orm.em']->find('\LarpManager\Entities\Langue',$id);
		
		return $app['twig']->render('langue/detail.twig', array('langue' => $langue));
	}
	
	/**
	 * Ajoute une langue
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$langue = new \LarpManager\Entities\Langue();
		
		$form = $app['form.factory']->createBuilder(new LangueForm(), $langue)
		->add('save','submit', array('label' => "Sauvegarder"))
		->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
		->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$langue = $form->getData();
			$app['orm.em']->persist($langue);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La langue a été ajoutée.');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('langue'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('langue.add'),301);
			}
		}
		
		return $app['twig']->render('langue/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modifie une langue
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{	
		$id = $request->get('index');
		
		$langue = $app['orm.em']->find('\LarpManager\Entities\Langue',$id);
		
		$form = $app['form.factory']->createBuilder(new LangueForm(), $langue)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$langue = $form->getData();
		
			if ( $form->get('update')->isClicked())
			{
				$app['orm.em']->persist($langue);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La langue a été mise à jour.');
		
				return $app->redirect($app['url_generator']->generate('langue.detail',array('index' => $id)),301);
			}
			else if ( $form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($langue);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La langue a été supprimée.');
				return $app->redirect($app['url_generator']->generate('langue'),301);
			}
		}		

		return $app['twig']->render('langue/update.twig', array(
				'langue' => $langue,
				'form' => $form->createView(),
		));
	}
}