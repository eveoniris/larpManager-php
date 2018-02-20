<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use LarpManager\Form\PaysMinimalForm;
use LarpManager\Form\PaysForm;

class PaysController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Pays');
		$pays = $repo->findAll();
		return $app['twig']->render('pays/index.twig', array('listPays' => $pays));
	}

	/**
	 * @description affiche le formulaire d'ajout d'un pays
	 */
	public function addAction(Request $request, Application $app)
	{
		$pays = new \LarpManager\Entities\Pays();
		
		$form = $app['form.factory']->createBuilder(new PaysMinimalForm(), $pays)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$pays = $form->getData();
			$pays->setCreator($app['user']);
			
			$app['orm.em']->persist($pays);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'Le pays a été ajouté.');
			
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('pays'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('pays.add'),301);
			}
		}
		
		return $app['twig']->render('pays/add.twig', array(
				'form' => $form->createView(),	
			));			
	}

	/**
	 * @description affiche le formulaire de modification d'une chrono
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$pays = $app['orm.em']->find('\LarpManager\Entities\Pays',$id);
		
		$form = $app['form.factory']->createBuilder(new PaysForm(), $pays)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$pays = $form->getData();
			
			if ($form->get('update')->isClicked())
			{
				$pays->setUpdateDate(new \DateTime('NOW'));
				
				$app['orm.em']->persist($pays);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le pays a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($pays);
				$app['orm.em']->flush();
			
				$app['session']->getFlashBag()->add('success', 'Le pays a été supprimé.');
			}
				
			return $app->redirect($app['url_generator']->generate('pays'));
		}
		
		return $app['twig']->render('pays/update.twig', array(
				'pays' => $pays,
				'form' => $form->createView(),
		));
	}
	

	/**
	 * @description affiche la détail d'une chronologie
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');

		$pays = $app['orm.em']->find('\LarpManager\Entities\Pays',$id);

		if ( $pays )
		{
			return $app['twig']->render('pays/detail.twig', array('pays' => $pays));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le pays n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('pays'));
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