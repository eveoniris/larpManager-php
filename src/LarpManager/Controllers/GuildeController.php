<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

// Guildes des joueurs
// TODO : lister les postulants et membres a partir des guildes ?
class GuildeController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Guilde');
		$guildes = $repo->findAll();
		
		return $app['twig']->render('guilde/index.twig', array('guildes' => $guildes));
	}

	/**
	 * @description affiche le formulaire d'ajout d'une guilde
	 */
	public function addAction(Request $request, Application $app)
	{
		if ( $request->getMethod() === 'POST' )
		{
			$label = $request->get('label');
			$description = $request->get('description');
				
			$guilde = new \LarpManager\Entities\Guilde();
			
			$guilde->setLabel($label);
			$guilde->setDescription($description);

			$app['orm.em']->persist($guilde);
			$app['orm.em']->flush();

			return $app->redirect($app['url_generator']->generate('guilde_list'));
		}
		return $app['twig']->render('guilde/add.twig');
	}

	/**
	 * @description affiche le formulaire de modification d'une guilde
	 */
	public function modifyAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		$guilde = $app['orm.em']->find('\LarpManager\Entities\Guilde',$id);
		if(!$guilde)
		{
			return $app->redirect($app['url_generator']->generate('guilde_list'));
		}

		if ( $request->getMethod() === 'POST' )
		{
			$label = $request->get('label');
			$description = $request->get('description');
				
			$guilde->setLabel($label);
			$guilde->setDescription($description);
				
			$app['orm.em']->flush();

			return $app->redirect($app['url_generator']->generate('guilde_list'));
		}
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Guilde');
		return $app['twig']->render('guilde/modify.twig', array('guilde' => $guilde));
	}

	/**
	 * @description affiche le formulaire de suppression d'une guilde
	 */
	public function removeAction(Request $request, Application $app)
	{
		$id = $request->get('index');

		$guilde = $app['orm.em']->find('\LarpManager\Entities\Guilde',$id);

		if ( $guilde )
		{
			if ( $request->getMethod() === 'POST' )
			{
				$app['orm.em']->remove($guilde);
				$app['orm.em']->flush();
				return $app->redirect($app['url_generator']->generate('guilde_list'));
			}
			return $app['twig']->render('chronologie/remove.twig', array('guilde' => $guilde));
		}
		else
		{
			return $app->redirect($app['url_generator']->generate('guilde_list'));
		}
	}

	/**
	 * @description affiche la détail d'une guilde
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');

		$guilde = $app['orm.em']->find('\LarpManager\Entities\Guilde',$id);

		if ( $guilde )
		{
			return $app['twig']->render('guilde/detail.twig', array('guilde',$guilde));
		}
		else
		{
			return $app->redirect($app['url_generator']->generate('guilde_list'));
		}
	}
}