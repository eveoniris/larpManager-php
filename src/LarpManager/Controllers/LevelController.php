<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\LevelForm;

class LevelController
{
	/**
	 * Liste les niveaux
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Level');
		$levels = $repo->findAll();
		return $app['twig']->render('level/index.twig', array('levels' => $levels));
	}
	
	/**
	 * Ajoute un niveau
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$level = new \LarpManager\Entities\Level();
		
		$form = $app['form.factory']->createBuilder(new LevelForm(), $level)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$level = $form->getData();
		
			$app['orm.em']->persist($level);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'Le niveau a été ajouté.');
		
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('level'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('level.add'),301);
			}
		}
		
		return $app['twig']->render('level/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour un niveau
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$level = $app['orm.em']->find('\LarpManager\Entities\Level',$id);
		
		$form = $app['form.factory']->createBuilder(new LevelForm(), $level)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$level = $form->getData();
		
			if ($form->get('update')->isClicked())
			{
				$app['orm.em']->persist($level);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le niveau a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($level);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le niveau a été supprimé.');
			}
		
			return $app->redirect($app['url_generator']->generate('level'));
		}
				
		return $app['twig']->render('level/update.twig', array(
				'level' => $level,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Detail d'un niveau
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$level = $app['orm.em']->find('\LarpManager\Entities\Level',$id);
		
		if ( $level )
		{
			return $app['twig']->render('level/detail.twig', array('level' => $level));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'La niveau n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('level'));
		}
	}
	
}