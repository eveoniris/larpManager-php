<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\PersonnageSecondaireForm;

/**
 * LarpManager\Controllers\PersonnageSecondaireController
 * 
 * @author kevin
 *
 */
class PersonnageSecondaireController
{
	/**
	 * affiche la liste des personnages secondaires
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\PersonnageSecondaire');
		$personnageSecondaires = $repo->findAll();
	
		return $app['twig']->render('admin/personnageSecondaire/index.twig', array('personnageSecondaires' => $personnageSecondaires));
	}
	
	/**
	 * Detail d'un personnage secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$personnageSecondaire = $request->get('personnageSecondaire');
		return $app['twig']->render('admin/personnageSecondaire/detail.twig', array('personnageSecondaire' => $personnageSecondaire));
	}
	
	/**
	 * Ajout d'un personnage secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$personnageSecondaire = new \LarpManager\Entities\PersonnageSecondaire();
		
		$form = $app['form.factory']->createBuilder(new PersonnageSecondaireForm(), $personnageSecondaire)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnageSecondaire = $form->getData();

			/**
			 * Pour toutes les compétences de la classe
			 */
			foreach ( $personnageSecondaire->getPersonnageSecondaireCompetences() as $personnageSecondaireCompetence)
			{
				$personnageSecondaireCompetence->setPersonnageSecondaire($personnageSecondaire);
			}
			
			$app['orm.em']->persist($personnageSecondaire);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage secondaire été sauvegardé');
			return $app->redirect($app['url_generator']->generate('personnageSecondaire'),301);
		}
			
		return $app['twig']->render('admin/personnageSecondaire/add.twig', array(
				'form' => $form->createView(),
				));
	}
	
	/**
	 * Mise à jour d'un personnage secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$personnageSecondaire = $request->get('personnageSecondaire');
		$form = $app['form.factory']->createBuilder(new PersonnageSecondaireForm(), $personnageSecondaire)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->add('delete','submit', array('label' => 'Supprimer'))
			->getForm();
		
		return $app['twig']->render('admin/personnageSecondaire/update.twig', array(
				'personnageSecondaire' => $personnageSecondaire,
				'form' => $form->createView(),
				));
	}
}