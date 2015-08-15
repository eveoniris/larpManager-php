<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class ClasseController
{
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Classe');
		$classes = $repo->findAll();
		return $app['twig']->render('classe/index.twig', array('classes' => $classes));
	}
	
	public function addAction(Request $request, Application $app)
	{
	
	}
	
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$classe = $app['orm.em']->find('\LarpManager\Entities\Classe',$id);
		
		$form = $app['form.factory']->createBuilder(new ClasseForm(), $classe)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
		->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$classe = $form->getData();
		
			if ($form->get('update')->isClicked())
			{		
				$app['orm.em']->persist($classe);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La classe a été mise à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($classe);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'La classe a été supprimée.');
			}
		
			return $app->redirect($app['url_generator']->generate('classe'));
		}
			
		return $app['twig']->render('classe/update.twig', array(
				'classe' => $classe,
				'form' => $form->createView(),
		));
	}
	
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$classe = $app['orm.em']->find('\LarpManager\Entities\Classe',$id);
		
		if ( $classe )
		{
			return $app['twig']->render('classe/detail.twig', array('classe' => $classe));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'La classe n\'a pas été trouvée.');
			return $app->redirect($app['url_generator']->generate('classe'));
		}
	}
	
	public function detailExportAction(Request $request, Application $app)
	{
	
	}
	
	public function exportAction(Request $request, Application $app)
	{
	
	}
}