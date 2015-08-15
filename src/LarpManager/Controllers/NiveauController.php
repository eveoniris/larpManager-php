<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use LarpManager\Form\NiveauForm;

class NiveauController
{
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Niveau');
		$niveaux = $repo->findAll();
		return $app['twig']->render('niveau/index.twig', array('niveaux' => $niveaux));
	}
	
	public function addAction(Request $request, Application $app)
	{
		$niveau = new \LarpManager\Entities\Niveau();
		
		$form = $app['form.factory']->createBuilder(new NiveauForm(), $niveau)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$niveau = $form->getData();
		
			$app['orm.em']->persist($niveau);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'Le niveau a été ajouté.');
		
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('niveau'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('niveau.add'),301);
			}
		}
		
		return $app['twig']->render('niveau/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$niveau = $app['orm.em']->find('\LarpManager\Entities\Niveau',$id);
		
		$form = $app['form.factory']->createBuilder(new NiveauForm(), $niveau)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$niveau = $form->getData();
		
			if ($form->get('update')->isClicked())
			{
				$app['orm.em']->persist($niveau);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le niveau a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($niveau);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le niveau a été supprimé.');
			}
		
			return $app->redirect($app['url_generator']->generate('classe'));
		}
				
		return $app['twig']->render('niveau/update.twig', array(
				'niveau' => $niveau,
				'form' => $form->createView(),
		));
	}
	
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$niveau = $app['orm.em']->find('\LarpManager\Entities\Niveau',$id);
		
		if ( $niveau )
		{
			return $app['twig']->render('niveau/detail.twig', array('niveau' => $niveau));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'La niveau n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('niveau'));
		}
	}
	
	public function detailExportAction(Request $request, Application $app)
	{
	
	}
	
	public function exportAction(Request $request, Application $app)
	{
	
	}
}