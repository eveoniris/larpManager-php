<?php

namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Doctrine\Common\Collections\ArrayCollection;
use JasonGrimes\Paginator;
use LarpManager\Form\GroupeForm;
use LarpManager\Form\PersonnageForm;
use LarpManager\Form\FindGroupeForm;



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
	public function listAction(Request $request, Application $app)
	{
		$order_by = $request->get('order_by') ?: 'numero';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		
		$criteria = array();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
		$groupes = $repo->findBy(
				$criteria,
				array( $order_by => $order_dir),
				$limit,
				$offset);
		
		$numResults = $repo->findCount($criteria);
		
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('groupe.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
		
		return $app['twig']->render('admin/groupe/list.twig', array(
				'groupes' => $groupes,
				'paginator' => $paginator,
		));
	}
	
	/**
	 * Recherche d'un groupe
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function searchAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new FindGroupeForm(), array())
			->add('submit','submit', array('label' => 'Rechercher'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
				
			$type = $data['type'];
			$search = $data['search'];
	
			$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
				
			$groupes = null;
				
			switch ($type)
			{
				case 'label' :
					$groupes = $repo->findByName($search);
					break;
				case 'numero' :
					$groupes = $repo->findByNumero($search);
					break;
			}
				
			if ( $joueurs != null )
			{
				if ( count($joueurs) == 1 )
				{
					$app['session']->getFlashBag()->add('success', 'Le joueur a été trouvé.');
					return $app->redirect($app['url_generator']->generate('joueur.detail', array('index'=> $joueurs[0])));
				}
				else
				{
					$app['session']->getFlashBag()->add('success', 'Il y a plusieurs résultats à votre recherche.');
					return $app['twig']->render('joueur/search_result.twig', array(
							'joueurs' => $joueurs,
					));
				}
			}
				
			$app['session']->getFlashBag()->add('error', 'Désolé, le joueur n\'a pas été trouvé.');
		}
	
		return $app['twig']->render('joueur/search.twig', array(
				'form' => $form->createView(),
		));
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
		$participant = $app['user']->getParticipantByGroupe($groupe);
		
		// si le joueur dispose déjà d'un personnage, refuser le personnage
		if ( $participant->getPersonnage() )
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
		
		// si le groupe n'a plus de classe disponible, refuser le personnage
		if (  ! $groupe->hasEnoughClasse() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, ce groupe ne contient plus de classes disponibles');
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
			$personnage->setXp($app['larp.manager']->getGnActif()->getXpCreation());
			
			// historique
			$historique = new \LarpManager\Entities\ExperienceGain();
			$historique->setExplanation("Création de votre personnage");
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setPersonnage($personnage);
			$historique->setXpGain($app['larp.manager']->getGnActif()->getXpCreation());
			$app['orm.em']->persist($historique);
				
			$participant->setPersonnage($personnage);
			
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
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
	
	
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
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
		$id = $request->get('index');
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe', $id);
				
		if ( $request->getMethod() == 'POST')
		{
			$newPlaces = $request->get('place');
			
			/**
			 * Met à jour uniquement si la valeur à changé
			 */
			if ( $groupe->getClasseOpen() != $newPlaces)
			{
				$groupe->setClasseOpen($newPlaces);
				$app['orm.em']->persist($groupe);
			}
		
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le nombre de place disponible a été mis à jour');
			return $app->redirect($app['url_generator']->generate('groupe.list'),301);
		}
		
		return $app['twig']->render('admin/groupe/place.twig', array(
				'groupe' => $groupe));
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
							
			$app['orm.em']->persist($groupe);
			$app['orm.em']->flush();
			
			// défini les droits d'accés à ce forum
			// (les membres du groupe ont le droit d'accéder à ce forum)
			$topic->setRight('GROUPE_MEMBER');
			$topic->setObjectId($groupe->getId());
			$groupe->setTopic($topic);
			
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
				return $app->redirect($app['url_generator']->generate('groupe.list'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupe.add'),301);
			}
		}
		
		return $app['twig']->render('admin/groupe/add.twig', array('form' => $form->createView()));
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
				if ( $gn->getGroupes()->contains($groupe) == false)
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
				return $app->redirect($app['url_generator']->generate('groupe.list'));
			}
		
			
		}
			
		return $app['twig']->render('admin/groupe/update.twig', array(
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
			return $app['twig']->render('admin/groupe/detail.twig', array('groupe' => $groupe));
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