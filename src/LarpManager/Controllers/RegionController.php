<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use LarpManager\Form\RegionForm;


class RegionController
{
	/**
	 * @description affiche le tableau de bord de gestion des régions
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Region');
		$regions = $repo->findAll();
		return $app['twig']->render('region/index.twig', array('regions' => $regions));
	}

	/**
	 * @description affiche le formulaire d'ajout d'une région
	 */
	public function addAction(Request $request, Application $app)
	{
		$region = new \LarpManager\Entities\Region();
		
		$form = $app['form.factory']->createBuilder(new RegionForm(), $region)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$region = $form->getData();
			$region->setCreator($app['user']);
						
			$app['orm.em']->persist($region);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La région a été ajouté.');
			
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('region'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('region.add'),301);
			}
		}
		
		return $app['twig']->render('region/add.twig', array(
				'form' => $form->createView(),	
			));			
	}

	/**
	 * @description affiche le formulaire de modification d'une région
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$region = $app['orm.em']->find('\LarpManager\Entities\Region',$id);
				
		$form = $app['form.factory']->createBuilder(new RegionForm(), $region)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$region = $form->getData();
						
			if ($form->get('update')->isClicked())
			{
				$region->setUpdateDate(new \DateTime('NOW'));
				
				$app['orm.em']->persist($region);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La région a été mise à jour.');
				return $app->redirect($app['url_generator']->generate('region.detail',array('index' => $id)));
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($region);
				$app['orm.em']->flush();
			
				$app['session']->getFlashBag()->add('success', 'La région a été supprimée.');
				return $app->redirect($app['url_generator']->generate('region'));
			}
		}
		
		return $app['twig']->render('region/update.twig', array(
				'region' => $region,
				'form' => $form->createView(),
		));
	}
	

	/**
	 * @description affiche la détail d'une région
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');

		$region = $app['orm.em']->find('\LarpManager\Entities\Region',$id);

		if ( $region )
		{
			return $app['twig']->render('region/detail.twig', array('region' => $region));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'La région n\'a pas été trouvée.');
			return $app->redirect($app['url_generator']->generate('region'));
		}
	}
	
	public function detailExportAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	}
	
	public function exportAction(Request $request, Application $app)
	{
		
	}
}