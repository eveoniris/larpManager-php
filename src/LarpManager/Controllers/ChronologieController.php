<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

use LarpManager\Form\ChronologieForm;

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
		
		$form = $app['form.factory']->createBuilder(new ChronologieForm(), $chronologie)
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
		$chrono = $app['orm.em']->find('\LarpManager\Entities\Chronologie',$id);
		if(!$chrono)
		{
			return $app->redirect($app['url_generator']->generate('chrono_list'));
		}
		
		if ( $request->getMethod() === 'POST' )
		{
			$date = $request->get('date');
			$paysId = $request->get('paysId');
			$description = $request->get('description');
			
			$chrono->setDate(new \DateTime($date));
			$pays = $app['orm.em']->find('\LarpManager\Entities\Pays',$paysId);
			if(!$pays)
			{
				return $app->redirect($app['url_generator']->generate('chrono_list'));
			}
				
			$chrono->setPays($pays);
			$chrono->setDescription($description);
			
			$app['orm.em']->flush();
	
			return $app->redirect($app['url_generator']->generate('chrono_list'));
		}
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Pays');
		$pays = $repo->findAll();
		$chrono->getPays();
		return $app['twig']->render('admin/chronologie/update.twig', array('chronologie' => $chrono, 'pays' => $pays));
	}
	
	/**
	 * @description affiche le formulaire de suppresion d'une chrono
	 */
	public function removeAction(Request $request, Application $app)
	{
		$id = $request->get('index');

		$chrono = $app['orm.em']->find('\LarpManager\Entities\Chronologie',$id);

		if ( $chrono )
		{
			if ( $request->getMethod() === 'POST' )
			{
				$app['orm.em']->remove($chrono);
				$app['orm.em']->flush();
				return $app->redirect($app['url_generator']->generate('chrono_list'));
			}
			$chrono->getPays();
			return $app['twig']->render('chronologie/remove.twig', array('chronologie' => $chrono));
		}
		else
		{
			return $app->redirect($app['url_generator']->generate('chrono_list'));
		}
	}
}