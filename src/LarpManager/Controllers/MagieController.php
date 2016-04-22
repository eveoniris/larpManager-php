<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\DomaineForm;
use LarpManager\Form\DomaineDeleteForm;

/**
 * LarpManager\Controllers\MagieController
 *
 * @author kevin
 *
 */
class MagieController
{
	/**
	 * Liste des domaines de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineListAction(Request $request, Application $app)
	{
		$domaines = $app['orm.em']->getRepository('\LarpManager\Entities\Domaine')->findAll();
		
		return $app['twig']->render('admin/domaine/list.twig', array(
				'domaines' => $domaines,
		));
	}
	
	/**
	 * Detail d'un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineDetailAction(Request $request, Application $app)
	{
		$domaine = $request->get('domaine');
		
		return $app['twig']->render('admin/domaine/detail.twig', array(
				'domaine' => $domaine,
		));
	}
	
	/**
	 * Ajoute un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineAddAction(Request $request, Application $app)
	{
		$domaine = new \LarpManager\Entities\Domaine();
		
		$form = $app['form.factory']->createBuilder(new DomaineForm(), $domaine)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$domaine = $form->getData();
			
			$app['orm.em']->persist($domaine);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le domaine de magie a été ajouté');
			return $app->redirect($app['url_generator']->generate('magie.domaine.detail',array('domaine'=>$domaine->getId())),301);
		}
		
		return $app['twig']->render('admin/domaine/add.twig', array(
				'domaine' => $domaine,
				'form' => $form->createView(),
		));
		
	}
	
	/**
	 * Met à jour un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineUpdateAction(Request $request, Application $app)
	{
		$domaine = $request->get('domaine');
		
		$form = $app['form.factory']->createBuilder(new DomaineForm(), $domaine)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$domaine = $form->getData();
				
			$app['orm.em']->persist($domaine);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le domaine de magie a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('magie.domaine.detail',array('domaine'=>$domaine->getId())),301);
		}
		
		return $app['twig']->render('admin/domaine/update.twig', array(
				'domaine' => $domaine,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineDeleteAction(Request $request, Application $app)
	{
		$domaine = $request->get('domaine');
		
		$form = $app['form.factory']->createBuilder(new DomaineDeleteForm(), $domaine)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$domaine = $form->getData();
		
			$app['orm.em']->remove($domaine);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le domaine de magie a été suprimé');
			return $app->redirect($app['url_generator']->generate('magie.domaine.list'),301);
		}
		
		return $app['twig']->render('admin/domaine/delete.twig', array(
				'domaine' => $domaine,
				'form' => $form->createView(),
		));
	}
}