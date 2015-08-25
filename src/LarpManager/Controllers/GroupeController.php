<?php

namespace LarpManager\Controllers;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Doctrine\Common\Collections\ArrayCollection;

use LarpManager\Form\GroupeForm;
use LarpManager\Form\PersonnageForm;
use LarpManager\Form\Type\CompetenceType;

/**
 * Gestion des groupes
 * @author kevin
 */
class GroupeController
{
	/**
	 * Liste des groupes
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{	
		$groupes = $app['groupe.manager']->findAllOrderByNumero();
				
		return $app['twig']->render('groupe/index.twig', array(
				'groupes' => $groupes));
	}
	
	/**
	 * Page de gestion d'un groupe pour son responsable
	 * @param unknown $request
	 * @param unknown $app
	 */
	public function gestionAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe',$id);
		
		return $app['twig']->render('groupe/gestion.twig', array(
				'groupe' => $groupe));
	}
	
	/**
	 * Page de gestion d'un groupe pour un membre du groupe
	 * @param Request $request
	 * @param Application $app
	 */
	public function joueurAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe',$id);
		
		return $app['twig']->render('groupe/joueur.twig', array(
				'groupe' => $groupe));
	}
	
	/**
	 * Page de création d'un personnage (première étape, choix de la classe)
	 * @param Request $request
	 * @param Application $app
	 */
	public function personnageAddAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe',$id);
		
		// si le groupe n'a plus de place, refuser le personnage
		if (  ! $groupe->hasEnoughPlace() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, ce groupe ne contient plus de places disponibles');
			return $app->redirect($app['url_generator']->generate('groupe.joueur',array('index'=>$id)),301);
		}
		
		// si le joueur dispose déjà d'un personnage, refuser le personnage
		if ( $app['user']->getPersonnages()->count() > 0 ) 
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous disposez déjà d\'un personnage.');
			return $app->redirect($app['url_generator']->generate('groupe.joueur',array('index'=>$id)),301);
		}
		
		$personnage = new \LarpManager\Entities\Personnage();
				
		// j'ajoute içi certain champs du formulaires (les classes)
		// car j'ai besoin des informations du groupes pour les alimenter
		$form = $app['form.factory']->createBuilder(new PersonnageForm(), $personnage)
			->add('classe','entity', array(
					'label' =>  'Classe',
					'property' => 'label',
					'class' => 'LarpManager\Entities\Classe',
					'choices' => array_unique($groupe->getAvailableClasses()),
				))
			->add('save','submit', array('label' => 'Etape suivante'))
			->getForm();
			
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			$personnage->setUser($app['user']);
			$personnage->setGroupe($groupe);
			
			// ajoute les compétences au niveau 1 acquise à la création
			$repoNiveau = $app['orm.em']->getRepository('\LarpManager\Entities\Niveau');
			$niveau = $repoNiveau->findByNiveau(1);
			
			foreach ($personnage->getClasse()->getCompetenceCreations() as $competence)
			{
				$personnage->addCompetence($competence,$niveau);
			}
			
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush($personnage);
			
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé, étape suivante');
			return $app->redirect($app['url_generator']->generate('groupe.personnage.competence',array('index'=>$id),301));
		}
		
		$repoClasse = $app['orm.em']->getRepository('\LarpManager\Entities\Classe');
		$classes = $repoClasse->findAll();
		
		return $app['twig']->render('groupe/personnage/add.twig', array(
				'form' => $form->createView(),
				'groupe' => $groupe,
				'classes' => array_unique($groupe->getAvailableClasses()),
		));
	}
	
	/**
	 * Page de gestion des competence
	 * @param Request $request
	 * @param Application $app
	 */
	public function personnageCompetenceAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe',$id);
		
		$personnage = $app['user']->getPersonnage();
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous n\'avez pas encore de personnage. Créez un personnage avant de pouvoir lui affecter des compétences');
			return $app->redirect($app['url_generator']->generate('groupe.joueur',array('index'=>$id)),301);
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageCompetenceForm(), $personnage);
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush($personnage);
			
		}
		
		return $app['twig']->render('groupe/personnage/competence.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modification du nombre de place disponibles dans un groupe
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function placeAction(Request $request, Application $app)
	{
		$groupes = $app['groupe.manager']->findAllOrderByNumero();
		
		if ( $request->getMethod() == 'POST')
		{
			$newPlaces = $request->get('place');
				
			foreach( $groupes as $groupe )
			{
				if ( $groupe->getClasseOpen() != $newPlaces[$groupe->getId()])
				{
					$groupe->setClasseOpen($newPlaces[$groupe->getId()]);
					$app['orm.em']->persist($groupe);
				}
			}
			$app['orm.em']->flush();
		}
		
		return $app['twig']->render('groupe/place.twig', array(
				'groupes' => $groupes));
	}
	
	/**
	 * Ajout d'un groupe
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$groupe = new \LarpManager\Entities\Groupe();
		
		$form = $app['form.factory']->createBuilder(new GroupeForm(), $groupe)
			->add('save','submit', array('label' => 'Sauvegarder et fermer'))
			->add('save_continue','submit',array('label' => 'Sauvegarder et nouveau'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$groupe = $form->getData();
			$groupe->setCreator($app['user']);
			
			foreach ( $groupe->getGroupeClasses() as $groupeClasses)
			{
				$groupeClasses->setGroupe($groupe);
			}
			
			$app['orm.em']->persist($groupe);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le groupe été sauvegardé');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupe'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupe.add'),301);
			}
		}
		
		return $app['twig']->render('groupe/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * Modification d'un groupe
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe',$id);
		
		$originalGroupeClasses = new ArrayCollection();

		// Crée un tableau contenant les objets GroupeClasse courants de la base de données
		foreach ($groupe->getGroupeClasses() as $groupeClasse) 
		{
			$originalGroupeClasses->add($groupeClasse);
		}
				
		$form = $app['form.factory']->createBuilder(new GroupeForm(), $groupe)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$groupe = $form->getData();
			
			foreach ( $groupe->getGroupeClasses() as $groupeClasses)
			{
				$groupeClasses->setGroupe($groupe);
			}
			// supprime la relation entre le groupeClasse et le groupe
			foreach ($originalGroupeClasses as $groupeClasse) {
				if ($groupe->getGroupeClasses()->contains($groupeClasse) == false) {
					$app['orm.em']->remove($groupeClasse);
				}
			}
		
			if ($form->get('update')->isClicked())
			{
				$groupe->setUpdateDate(new \DateTime('NOW'));
				
				$app['orm.em']->persist($groupe);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le groupe a été mis à jour.');
				return $app->redirect($app['url_generator']->generate('groupe.detail', array('index' => $id)));
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($groupe);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le groupe a été supprimé.');
				return $app->redirect($app['url_generator']->generate('groupe'));
			}
		
			
		}
			
		return $app['twig']->render('groupe/update.twig', array(
				'groupe' => $groupe,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Affiche le détail d'un groupe
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe',$id);
		
		if ( $groupe )
		{
			return $app['twig']->render('groupe/detail.twig', array('groupe' => $groupe));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le groupe n\'a pas été trouvée.');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}
	}
	
	public function exportAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
		$groupes = $repo->findAll();
		
		
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=eveoniris_groupe_".date("Ymd").".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$output = fopen("php://output", "w");
		
		// header
		fputcsv($output,
					array(
					'nom',
					'description',
					'code',
					'creation_date'), ',');
		
		foreach ($groupes as $groupe)
		{
			fputcsv($output, $groupe->getExportValue(), ',');
		}
		
		fclose($output);
		exit();
	}
}