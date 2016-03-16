<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\PersonnageSecondaireForm;
use LarpManager\Form\PersonnageSecondaireDeleteForm;
use LarpManager\Form\PersonnageSecondaireChoiceForm;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Controllers\PersonnageSecondaireController
 * 
 * @author kevin
 *
 */
class PersonnageSecondaireController
{
	/**
	 * Gestion du personnage secondaire
	 * @param Request $request
	 * @param Application $app
	 */
	public function accueilAction(Request $request, Application $app)
	{
		return $app['twig']->render('public/personnageSecondaire/accueil.twig', array());
	}
	
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
			return $app->redirect($app['url_generator']->generate('personnageSecondaire.list'),301);
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
		
		/**
		 *  Crée un tableau contenant les objets personnageSecondaireCompetences courants de la base de données
		 */
		$originalPersonnageSecondaireComptences = new ArrayCollection();
		foreach ($personnageSecondaire->getPersonnageSecondaireCompetences() as $personnageSecondaireCompetence)
		{
			$originalPersonnageSecondaireComptences->add($personnageSecondaireCompetence);
		}
		
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
			
			/**
			 *  supprime la relation entre le groupeClasse et le groupe
			 */
			foreach ($originalPersonnageSecondaireComptences as $personnageSecondaireCompetence) {
				if ($personnageSecondaire->getPersonnageSecondaireCompetences()->contains($personnageSecondaireCompetence) == false) 
				{
					$app['orm.em']->remove($personnageSecondaireCompetence);
				}
			}
			
			$app['orm.em']->persist($personnageSecondaire);
			$app['orm.em']->flush();
			$app['session']->getFlashBag()->add('success', 'Le personnage secondaire a été mis à jour.');
			return $app->redirect($app['url_generator']->generate('personnageSecondaire.list'));
		}
		
		return $app['twig']->render('admin/personnageSecondaire/update.twig', array(
				'personnageSecondaire' => $personnageSecondaire,
				'form' => $form->createView(),
				));
	}
	
	/**
	 * Mise à jour d'un personnage secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function deleteAction(Request $request, Application $app)
	{
		$personnageSecondaire = $request->get('personnageSecondaire');
		
		$form = $app['form.factory']->createBuilder(new PersonnageSecondaireDeleteForm(), $personnageSecondaire)
			->add('delete','submit', array('label' => 'Supprimer'))
			->getForm();
	
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnageSecondaire = $form->getData();
		
			foreach ( $personnageSecondaire->getPersonnageSecondaireCompetences() as $personnageSecondaireCompetence)
			{
				$app['orm.em']->remove($personnageSecondaireCompetence);
			}
			
			$app['orm.em']->remove($personnageSecondaire);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le personnage secondaire a été supprimé.');
			return $app->redirect($app['url_generator']->generate('personnageSecondaire.list'),301);
		}
			
		return $app['twig']->render('admin/personnageSecondaire/delete.twig', array(
				'personnageSecondaire' => $personnageSecondaire,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Choix du personnage secondaire par un joueur
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function choiceAction(Request $request, Application $app)
	{
		$participant = $app['user']->getParticipantByGn($app['larp.manager']->getGnActif());
		
		if ( $participant == null )
		{
			$app['session']->getFlashBag()->add('error','Vous devez au minimum participer à un GN pour pouvoir choisir un personnage secondaire.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\PersonnageSecondaire');
		$personnageSecondaires = $repo->findAll();
		
		$form = $app['form.factory']->createBuilder(new PersonnageSecondaireChoiceForm(), $participant)
			->add('choice','submit', array('label' => 'Enregistrer'))
			->getForm();
			
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$participant = $form->getData();
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage secondaire a été enregistré.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		return $app['twig']->render('public/personnageSecondaire/choice.twig', array(
				'participant' => $participant,
				'personnageSecondaires' => $personnageSecondaires,
				'form' => $form->createView(),
		));
			
	}
	
	/**
	 * Information sur les archétypes de personnages secondaires
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\PersonnageSecondaire');
		$personnageSecondaires = $repo->findAll();
		
		return $app['twig']->render('public/personnageSecondaire/list.twig', array(
				'personnageSecondaires' => $personnageSecondaires,
		));
	}
}