<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\AnnonceForm;

/**
 * LarpManager\Controllers\AnnonceController
 *
 * @author kevin
 *
 */
class AnnonceController
{
	/**
	 * Présentation des annonces
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Annonce');
		$annonces = $repo->findAll();
		return $app['twig']->render('annonce/index.twig', array('annonces' => $annonces));
	}
	
	/**
	 * Ajout d'une annonce
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$annonce = new \LarpManager\Entities\Annonce();
		
		$form = $app['form.factory']->createBuilder(new AnnonceForm(), $annonce)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$annonce = $form->getData();
				
			$app['orm.em']->persist($annonce);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'L\'annonce a été ajoutée.');
		
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('annonce'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('annonce.add'),301);
			}
		}
		
		return $app['twig']->render('annonce/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Mise à jour d'une classe
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$annonce = $app['orm.em']->find('\LarpManager\Entities\Annonce',$id);
		
		$form = $app['form.factory']->createBuilder(new AnnonceForm(), $annonce)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$annonce = $form->getData();

			if ($form->get('update')->isClicked())
			{
				$annonce->setUpdateDate(new \Datetime('NOW'));
				
				$app['orm.em']->persist($annonce);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'L\annonce a été mise à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($annonce);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'L\'annonce a été supprimée.');
			}
		
			return $app->redirect($app['url_generator']->generate('annonce'));
		}
			
		return $app['twig']->render('annonce/update.twig', array(
				'annonce' => $annonce,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Détail d'une annonce
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$annonce = $app['orm.em']->find('\LarpManager\Entities\Annonce',$id);
		
		if ( $annonce )
		{
			return $app['twig']->render('annonce/detail.twig', array('annonce' => $annonce));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'L\'annonce n\'a pas été trouvée.');
			return $app->redirect($app['url_generator']->generate('annonce'));
		}
	}

}