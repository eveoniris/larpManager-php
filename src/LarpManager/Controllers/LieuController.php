<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

use LarpManager\Entities\Lieu;
use LarpManager\Form\LieuForm;
use LarpManager\Form\LieuDeleteForm;

/**
 * LarpManager\Controllers\LieuController
 *
 * @author kevin
 *
 */
class LieuController
{
	/**
	 * Liste des lieux
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$lieux = $app['orm.em']->getRepository('\LarpManager\Entities\Lieu')->findAllOrderedByNom();
		
		return $app['twig']->render('admin/lieu/index.twig', array('lieux' => $lieux));
	}
	
	/**
	 * Ajouter un lieu
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new LieuForm(), new Lieu())
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$lieu = $form->getData();
				
			$app['orm.em']->persist($lieu);
			$app['orm.em']->flush($lieu);
				
			$app['session']->getFlashBag()->add('success', 'Le lieu a été ajouté.');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('lieu'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('lieu.add'),301);
			}
		}
			
		
		return $app['twig']->render('admin/lieu/add.twig', array(
				'form' => $form->createView()
		));
		
	}
	
	/**
	 * Détail d'un lieu
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app, Lieu $lieu)
	{		
		return $app['twig']->render('admin/lieu/detail.twig', array('lieu' => $lieu));
	}
	
	/**
	 * Mise à jour d'un lieu
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app, Lieu $lieu)
	{		
		return $app['twig']->render('admin/lieu/update.twig', array('lieu' => $lieu));	
	}
	
	/**
	 * Suppression d'un lieu
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function deleteAction(Request $request, Application $app, Lieu $lieu)
	{	
		return $app['twig']->render('admin/lieu/delete.twig', array('lieu' => $lieu));
	}
}