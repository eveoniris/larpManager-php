<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\TitreForm;
use LarpManager\Form\TitreDeleteForm;

/**
 * LarpManager\Controllers\TitreController
 *
 * @author kevin
 */
class TitreController
{
	/**
	 * Liste des titres
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminListAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Titre');
		$titres = $repo->findAll();
		

		return $app['twig']->render('admin/titre/list.twig', array('titres' => $titres));
	}
	
	
	/**
	 * Detail d'un titre
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDetailAction(Request $request, Application $app)
	{
		$titre = $request->get('titre');
	
		return $app['twig']->render('admin/titre/detail.twig', array(
				'titre' => $titre,
		));
	}
	
	/**
	 * Ajoute un titre
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddAction(Request $request, Application $app)
	{
		$titre = new \LarpManager\Entities\Titre();
	
		$form = $app['form.factory']->createBuilder(new TitreForm(), $titre)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$titre = $form->getData();
	
			$app['orm.em']->persist($titre);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Le titre a été ajouté');
			return $app->redirect($app['url_generator']->generate('titre.admin.detail',array('titre'=>$titre->getId())),301);
		}
	
		return $app['twig']->render('admin/titre/add.twig', array(
				'titre' => $titre,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour un titre
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateAction(Request $request, Application $app)
	{
		$titre = $request->get('titre');
	
		$form = $app['form.factory']->createBuilder(new TitreForm(), $titre)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$titre = $form->getData();
		
			$app['orm.em']->persist($titre);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Le titre a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('titre.admin.detail',array('titre'=>$titre->getId())),301);
		}
	
		return $app['twig']->render('admin/titre/update.twig', array(
				'titre' => $titre,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime un titre
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDeleteAction(Request $request, Application $app)
	{
		$titre = $request->get('titre');
	
		$form = $app['form.factory']->createBuilder(new TitreDeleteForm(), $titre)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$titre = $form->getData();
	
			$app['orm.em']->remove($titre);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Le titre a été suprimé');
			return $app->redirect($app['url_generator']->generate('titre.admin.list'),301);
		}
	
		return $app['twig']->render('admin/titre/delete.twig', array(
				'titre' => $titre,
				'form' => $form->createView(),
		));
	}
}