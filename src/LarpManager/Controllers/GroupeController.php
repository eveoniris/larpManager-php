<?php

namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Doctrine\Common\Collections\ArrayCollection;
use LarpManager\Form\GroupeForm;
use LarpManager\Form\PersonnageForm;


/**
 * LarpManager\Controllers\GroupeController
 *
 * @author kevin
 *
 */
class GroupeController
{
	/**
	 * Liste des groupes
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{	
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
		$groupes = $repo->findAllOrderByNumero();
				
		return $app['twig']->render('groupe/index.twig', array(
				'groupes' => $groupes));
	}
	
	/**
	 * Création d'un nouveau personnage dans un groupe
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function personnageAddAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe',$id);
		$joueur = $app['user']->getJoueur();
		
		// si le joueur dispose déjà d'un personnage, refuser le personnage
		if ( $joueur->getPersonnage() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous disposez déjà d\'un personnage.');
			return $app->redirect($app['url_generator']->generate('homepage',array('index'=>$id)),301);
		}
	
		// si le groupe n'a plus de place, refuser le personnage
		if (  ! $groupe->hasEnoughPlace() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, ce groupe ne contient plus de places disponibles');
			return $app->redirect($app['url_generator']->generate('homepage',array('index'=>$id)),301);
		}
	
		$personnage = new \LarpManager\Entities\Personnage();
	
		// j'ajoute içi certain champs du formulaires (les classes)
		// car j'ai besoin des informations du groupe pour les alimenter
		$form = $app['form.factory']->createBuilder(new PersonnageForm(), $personnage)
			->add('classe','entity', array(
				'label' =>  'Classes disponibles',
				'property' => 'label',
				'class' => 'LarpManager\Entities\Classe',
				'choices' => array_unique($groupe->getAvailableClasses()),
				))
			->add('save','submit', array('label' => 'Valider mon personnage'))
			->getForm();
			
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			$personnage->setGroupe($groupe);
				
			// Ajout des points d'expérience gagné à la création d'un personnage
			$personnage->setXp(10); // TODO il faudra utiliser par la suite les informations lié au gn
			// historique
			$historique = new \LarpManager\Entities\ExperienceGain();
			$historique->setExplanation("Création de votre personnage");
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setPersonnage($personnage);
			$historique->setXpGain(10); // TODO cf. precedent
			$app['orm.em']->persist($historique);
				
			$joueur->setPersonnage($personnage);
			
			// ajout des compétences acquises à la création
			foreach ($personnage->getClasse()->getCompetenceFamilyCreations() as $competenceFamily)
			{
				$firstCompetence = $competenceFamily->getFirstCompetence();
				if ( $firstCompetence )
				{
					$personnage->addCompetence($firstCompetence);
					$firstCompetence->addPersonnage($personnage);
					$app['orm.em']->persist($firstCompetence);
				}
			}
				
			// Ajout des points d'expérience gagné grace à l'age
			$xpAgeBonus = $personnage->getAge()->getBonus();
			if ( $xpAgeBonus )
			{
				$personnage->addXp($xpAgeBonus);
				$historique = new \LarpManager\Entities\ExperienceGain();
				$historique->setExplanation("Bonus lié à l'age");
				$historique->setOperationDate(new \Datetime('NOW'));
				$historique->setPersonnage($personnage);
				$historique->setXpGain($xpAgeBonus);
				$app['orm.em']->persist($historique);
			}
				
				
			$app['orm.em']->persist($personnage);
			$app['orm.em']->persist($joueur);
			$app['orm.em']->flush();
	
	
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('homepage',301));
		}
	
		return $app['twig']->render('groupe/personnage_add.twig', array(
			'form' => $form->createView(),
			'classes' => array_unique($groupe->getAvailableClasses()),
			'groupe' => $groupe,
		));
	}
	/**
	 * Page de gestion d'un groupe pour son responsable
	 * 
	 * @param Request $request
	 * @param Application $app
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
	 * 
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
	 * Modification du nombre de place disponibles dans un groupe
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function placeAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
		$groupes = $repo->findAllOrderByNumero();
		
		if ( $request->getMethod() == 'POST')
		{
			$newPlaces = $request->get('place');
			
			/**
			 * Pour tous les groupes
			 */
			foreach( $groupes as $groupe )
			{
				/**
				 * Met à jour uniquement si la valeur à changée
				 */
				if ( $groupe->getClasseOpen() != $newPlaces[$groupe->getId()])
				{
					$groupe->setClasseOpen($newPlaces[$groupe->getId()]);
					$app['orm.em']->persist($groupe);
				}
			}
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le nombre de place disponible a été mis à jour');
				
		}
		
		return $app['twig']->render('groupe/place.twig', array(
				'groupes' => $groupes));
	}
	
	/**
	 * Ajout d'un groupe
	 * 
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
			
			/**
			 * Pour toutes les classes du groupe
			 */
			foreach ( $groupe->getGroupeClasses() as $groupeClasses)
			{
				$groupeClasses->setGroupe($groupe);
			}
			
			/**
			 * Pour tous les gns du groupe
			 */
			foreach ( $groupe->getGns() as $gn )
			{
				$gn->addGroupe($groupe);
			}
			
			/**
			 * Création des topics associés à ce groupe
			 * un topic doit être créé par GN auquel ce groupe est inscrit
			 * @var \LarpManager\Entities\Topic $topic
			 */
			$topic = new \LarpManager\Entities\Topic();
			$topic->setTitle($groupe->getNom());
			$topic->setDescription($groupe->getDescription());
			$topic->setUser($app['user']);
				
			$groupe->setTopic($topic);
			
			$app['orm.em']->persist($groupe);
			$app['orm.em']->flush();
			
			// défini les droits d'accés à ce forum
			// (les membres du groupe ont le droit d'accéder à ce forum)
			$topic->setRight('GROUPE_MEMBER');
			$topic->setObjectId($groupe->getId());
			
			$app['orm.em']->persist($topic);
			$app['orm.em']->persist($groupe);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le groupe été sauvegardé');
			
			/**
			 * Si l'utilisateur a cliqué sur "save", renvoi vers la liste des groupes
			 * Si l'utilisateur a cliqué sur "save_continue", renvoi vers un nouveau formulaire d'ajout 
			 */
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
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe',$id);
		
		$originalGroupeClasses = new ArrayCollection();
		$originalGns = new ArrayCollection();

		/**
		 *  Crée un tableau contenant les objets GroupeClasse courants de la base de données
		 */
		foreach ($groupe->getGroupeClasses() as $groupeClasse) 
		{
			$originalGroupeClasses->add($groupeClasse);
		}
		
		/** 
		 * Crée un tableau contenant les gns auquel ce groupe participe
		 */
		foreach ( $groupe->getGns() as $gn)
		{
			$originalGns->add($gn);
		}
			
		
		$form = $app['form.factory']->createBuilder(new GroupeForm(), $groupe)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$groupe = $form->getData();

			/**
			 * Pour toutes les classes du groupe
			 */
			foreach ( $groupe->getGroupeClasses() as $groupeClasses)
			{
				$groupeClasses->setGroupe($groupe);
			}		
				
			/**
			 *  supprime la relation entre le groupeClasse et le groupe
			 */
			foreach ($originalGroupeClasses as $groupeClasse) {
				if ($groupe->getGroupeClasses()->contains($groupeClasse) == false) {
					$app['orm.em']->remove($groupeClasse);
				}
			}
			
			/**
			 * Pour tous les gns du groupe
			 */
			foreach ( $groupe->getGns() as $gn )
			{
				$gn->addGroupe($groupe);
			}
			
			foreach ($originalGns as $gn)
			{
				if ( $groupe->getGns()->contains($gn) == false)
					$gn->removeGroupe($groupe);
			}
			
			
			/**
			 * Si l'utilisateur a cliquer sur "update", on met à jour le groupe
			 * Si l'utilisateur a cliquer sur "delete", on supprime le groupe
			 */
			if ($form->get('update')->isClicked())
			{				
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
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe',$id);
		
		/**
		 * Si le groupe existe, on affiche son détail
		 * Sinon on envoi une erreur
		 */
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
	
	/**
	 * Exportation de la liste des groupes au format CSV
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
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