<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

use LarpManager\Form\ChronologieForm;
use LarpManager\Form\ChronologieRemoveForm;

/**
 * LarpManager\Controllers\ChronologieController
 *
 */
class ChronologieController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Chronologie');
		$chronologies = $repo->findAll();
		
		return $app['twig']->render('admin/chronologie/index.twig', array('chronologies' => $chronologies));
	}

	/**
	 * @description affiche le formulaire d'ajout d'une chrono
	 */
	public function addAction(Request $request, Application $app)
	{
		$chronologie = new \LarpManager\Entities\Chronologie();
		
		// Un territoire peut avoir été passé en paramètre
		$territoireId = $request->get('territoire');
		
		if ( $territoireId )
		{
			$territoire = $app['orm.em']->find('\LarpManager\Entities\Territoire', $territoireId);
			if ( $territoire )
			{
				$chronologie->setTerritoire($territoire);
			}
		}
		
		$form = $app['form.factory']->createBuilder(new ChronologieForm(), $chronologie)
			->add('visibilite','choice', array(
					'required' => true,
					'label' =>  'Visibilité',
					'choices' => $app['larp.manager']->getChronologieVisibility(),
			))
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$chronologie = $form->getData();
				
			$app['orm.em']->persist($chronologie);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'L\'événement a été ajouté.');
			return $app->redirect($app['url_generator']->generate('chronologie'));
		}
		
		return $app['twig']->render('admin/chronologie/add.twig', array(
				'form' => $form->createView()
		));
	}

	/**
	 * @description affiche le formulaire de modification d'une chrono
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$chronologie = $app['orm.em']->find('\LarpManager\Entities\Chronologie',$id);
		if ( !$chronologie )
		{
			return $app->redirect($app['url_generator']->generate('chronologie'));
		}
		
		$form = $app['form.factory']->createBuilder(new ChronologieForm(), $chronologie)
			->add('visibilite','choice', array(
					'required' => true,
					'label' =>  'Visibilité',
					'choices' => $app['larp.manager']->getChronologieVisibility(),
			))
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$chronologie = $form->getData();
				
			$app['orm.em']->persist($chronologie);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'L\'événement a été mis à jour.');
			return $app->redirect($app['url_generator']->generate('chronologie'));
		}
		
		return $app['twig']->render('admin/chronologie/update.twig', array(
				'form' => $form->createView(),
				'chronologie' => $chronologie
		));
	}
	
	/**
	 * @description affiche le formulaire de suppresion d'une chrono
	 */
	public function removeAction(Request $request, Application $app)
	{
		$id = $request->get('index');

		$chronologie = $app['orm.em']->find('\LarpManager\Entities\Chronologie',$id);
		if ( !$chronologie )
		{
			return $app->redirect($app['url_generator']->generate('chronologie'));
		}
		
		$form = $app['form.factory']->createBuilder(new ChronologieRemoveForm(), $chronologie)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$chronologie = $form->getData();
			
			$app['orm.em']->remove($chronologie);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'L\'événement a été supprimé.');
			return $app->redirect($app['url_generator']->generate('chronologie'));
		}
		
		return $app['twig']->render('admin/chronologie/remove.twig', array(
				'chronologie' => $chronologie,
				'form' => $form->createView(),
		));
		
	}
}