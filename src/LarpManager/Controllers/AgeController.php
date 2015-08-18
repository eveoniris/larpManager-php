<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

use LarpManager\Form\AgeForm;

class AgeController
{
	/**
	 * Présentation des ages
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Age');
		$ages = $repo->findAll();
		return $app['twig']->render('age/index.twig', array('ages' => $ages));
	}
	
	/**
	 * Ajout d'un age
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$age = new \LarpManager\Entities\Age();
	
		$form = $app['form.factory']->createBuilder(new AgeForm(), $age)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$age = $form->getData();
				
			$app['orm.em']->persist($age);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'L\'age a été ajouté.');
	
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('age'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('age.add'),301);
			}
		}
	
		return $app['twig']->render('age/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Detail d'un age
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$age = $app['orm.em']->find('\LarpManager\Entities\Age',$id);
	
		if ( $age )
		{
			return $app['twig']->render('age/detail.twig', array('age' => $age));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'L\'age n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('age'));
		}	
	}
	
	/**
	 * Met à jour une compétence
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$age = $app['orm.em']->find('\LarpManager\Entities\Age',$id);
	
		$form = $app['form.factory']->createBuilder(new AgeForm(), $age)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$age = $form->getData();
	
			if ($form->get('update')->isClicked())
			{	
				$app['orm.em']->persist($age);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'L\'age a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($age);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'L\'age a été supprimé.');
			}
	
			return $app->redirect($app['url_generator']->generate('age'));
		}
	
		return $app['twig']->render('age/update.twig', array(
				'age' => $age,
				'form' => $form->createView(),
		));
	}
}