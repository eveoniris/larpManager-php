<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

use Silex\Application;
use JasonGrimes\Paginator;
use LarpManager\Form\PersonnageReligionForm;
use LarpManager\Form\PersonnageOriginForm;
use LarpManager\Form\PersonnageFindForm;
use LarpManager\Form\PersonnageForm;
use LarpManager\Form\TriggerForm;
use LarpManager\Form\TriggerDeleteForm;
use LarpManager\Form\PersonnageUpdateForm;
use LarpManager\Form\PersonnageTransfertForm;
use LarpManager\Form\PersonnageUpdateRenommeForm;
use LarpManager\Form\PersonnageUpdateSortForm;
use LarpManager\Form\PersonnageUpdatePotionForm;
use LarpManager\Form\PersonnageUpdateDomaineForm;
use LarpManager\Form\PersonnageUpdateLangueForm;
use LarpManager\Form\PersonnageUpdatePriereForm;
use LarpManager\Form\PersonnageDeleteForm;
use LarpManager\Form\PersonnageXpForm;

/**
 * LarpManager\Controllers\PersonnageController
 *
 * @author kevin
 *
 */
class PersonnageController
{
	
	/**
	 * Page d'accueil de gestion des personnage
	 * @param Request $request
	 * @param Application $app
	 */
	public function accueilAction(Request $request, Application $app)
	{
		return $app['twig']->render('public/personnage/accueil.twig', array());
	}
	
	/**
	 * Page d'accueil de gestion des fiches personnages
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminFicheAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
		$groupes = $repo->findBy(array(),array('nom' => 'asc'));
		
		return $app['twig']->render('admin/personnage/fiches.twig', array(
				'groupes' => $groupes,
				
		));
	}
	
	public function adminMaterielAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$form = $app['form.factory']->createBuilder()
			->add('materiel','textarea', array(
					'required' => false,
					'data' => $personnage->getMateriel(),
			))
			->add('valider','submit', array('label' => 'Valider'))
			->getForm();

		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$personnage->setMateriel($data['materiel']);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/materiel.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Transfert d'un personnage à un autre utilisateur
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminTransfertAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$form = $app['form.factory']->createBuilder()
			->add('participant','entity', array(
					'required' => true,
					'label' => 'Nouveau propriétaire',
					'class' => 'LarpManager\Entities\Participant',
					'property' => 'userIdentity',
			))
			->add('transfert','submit', array('label' => 'Transferer'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$participant = $data['participant'];
			
			$oldParticipant = $personnage->getParticipant();
			
			// gestion de l'ancien personnage
			if ( $participant->getPersonnage() )
			{
				$oldPersonnage = $participant->getPersonnage();
				$oldPersonnage->setParticipantNull();	
			}
			
			$participant->setPersonnage($personnage);
			$personnage->setParticipant($participant);
						
			$app['orm.em']->persist($participant);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été transféré');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/transfert.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Liste des personnages
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminListAction(Request $request, Application $app)
	{
		$order_by = $request->get('order_by') ?: 'id';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		$criteria = array();
		
		$form = $app['form.factory']->createBuilder(new PersonnageFindForm())
			->add('find','submit', array('label' => 'Rechercher'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$type = $data['type'];
			$value = $data['value'];
			switch ($type){
				case 'nom':
					$criteria[] = "p.nom LIKE '%$value%'";
					break;
			}
		}
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Personnage');
		$personnages = $repo->findList(
				$criteria,
				array( 'by' =>  $order_by, 'dir' => $order_dir),
				$limit,
				$offset);
		
		$numResults = $repo->findCount($criteria);
		
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('personnage.admin.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
		
		return $app['twig']->render('admin/personnage/list.twig', array(
				'personnages' => $personnages,
				'paginator' => $paginator,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Affiche le détail d'un personnage (pour les orgas)
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDetailAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		return $app['twig']->render('admin/personnage/detail.twig', array('personnage' => $personnage));
	}
	
	/**
	 * Gestion des points d'expérience d'un personnage (pour les orgas)
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminXpAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$form = $app['form.factory']->createBuilder(new PersonnageXpForm(), array())
				->add('save','submit', array('label' => 'Sauvegarder'))
				->getForm();
		
		$form->handleRequest($request);
					
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			$xp = $personnage->getXp();
				
			$personnage->setXp($xp + $data['xp']);
				
			// historique
			$historique = new \LarpManager\Entities\ExperienceGain();
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setXpGain($data['xp']);
			$historique->setExplanation($data['explanation']);
			$historique->setPersonnage($personnage);
			
			$app['orm.em']->persist($personnage);
			$app['orm.em']->persist($historique);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Les points d\'expériences ont été ajoutés');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);			
		}
		
		return $app['twig']->render('admin/personnage/xp.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Ajout d'un personnage (orga seulement)
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddAction(Request $request, Application $app)
	{
		$personnage = new \LarpManager\Entities\Personnage();
		
		$participant = $request->get('participant');
		if ( ! $participant ) {
			$participant = $app['user']->getParticipant();
		}
		else {
			$participant = $app['orm.em']->getRepository('\LarpManager\Entities\Participant')->find($participant);
		}
		
		
		$form = $app['form.factory']->createBuilder(new PersonnageForm(), $personnage)
			->add('classe','entity', array(
					'label' =>  'Classes disponibles',
					'property' => 'label',
					'class' => 'LarpManager\Entities\Classe',
			))
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			$participant->setPersonnage($personnage);
			
			if ( $participant->getGroupe())
			{
				$personnage->setGroupe($participant->getGroupe());
			}
			
			$personnage->setXp($app['larp.manager']->getGnActif()->getXpCreation());
				
			// historique
			$historique = new \LarpManager\Entities\ExperienceGain();
			$historique->setExplanation("Création de votre personnage");
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setPersonnage($personnage);
			$historique->setXpGain($app['larp.manager']->getGnActif()->getXpCreation());
			$app['orm.em']->persist($historique);
			
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
			if ( $participant->getGroupe())
			{
				return $app->redirect($app['url_generator']->generate('groupe.detail', array('index' => $participant->getGroupe()->getId())),301);
			}
			else
			{
				return $app->redirect($app['url_generator']->generate('homepage'),301);
			}
		}
		
		return $app['twig']->render('admin/personnage/add.twig', array(
				'form' => $form->createView(),
				'participant' => $participant,
		));
	}
			
	/**
	 * Supression d'un personnage (orga seulement)
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDeleteAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$form = $app['form.factory']->createBuilder(new PersonnageDeleteForm(), $personnage)
			->add('delete','submit', array('label' => 'Supprimer'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$personnage = $form->getData();

			foreach ($personnage->getExperienceGains() as $xp)
			{
				$personnage->removeExperienceGain($xp);
				$app['orm.em']->remove($xp);
			}
			
			foreach ($personnage->getExperienceUsages() as $xp)
			{
				$personnage->removeExperienceUsage($xp);
				$app['orm.em']->remove($xp);
			}
			
			foreach ($personnage->getMembres() as $membre)
			{
				$personnage->removeMembre($membre);
				$app['orm.em']->remove($membre);
			}
			
			foreach ( $personnage->getPersonnagesReligions() as $personnagesReligions)
			{
				$personnage->removePersonnagesReligions($personnagesReligions);
				$app['orm.em']->remove($personnagesReligions);
			}
			
			foreach ($personnage->getPostulants() as $postulant)
			{
				$personnage->removePostulant($postulant);
				$app['orm.em']->remove($postulant);
			}
			
			foreach ($personnage->getPersonnageLangues() as $personnageLangue)
			{
				$personnage->removePersonnageLangues($personnageLangue);
				$app['orm.em']->remove($personnageLangue);
			}
			
			foreach ($personnage->getPersonnageTriggers() as $trigger)
			{
				$personnage->removePersonnageTrigger($trigger);
				$app['orm.em']->remove($trigger);
			}
			
			foreach ($personnage->getPersonnageBackgrounds() as $background)
			{
				$personnage->removePersonnageBackground($background);
				$app['orm.em']->remove($background);
			}
						
			$app['orm.em']->remove($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été supprimé.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		return $app['twig']->render('admin/personnage/delete.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage
		));
	}

	/**
	 * Affiche le détail d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		return $app['twig']->render('personnage/detail.twig', array('personnage' => $personnage));
	}
	
	/**
	 * Modification du personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
				
		$form = $app['form.factory']->createBuilder(new PersonnageUpdateForm(), $personnage)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/update.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage));
	}
	
	/**
	 * Ajoute un background au personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddBackgroundAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$background = new \LarpManager\Entities\PersonnageBackground();
		
		$background->setPersonnage($personnage);
		$background->setUser($app['user']);
		
		$form = $app['form.factory']->createBuilder(new PersonnageBackgroundForm(), $background)
			->add('visibility','choice', array(
					'required' => true,
					'label' =>  'Visibilité',
					'choices' => $app['larp.manager']->getPersonnageBackgroundVisibility(),
			))
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();

		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$background = $form->getData();
		
			$app['orm.em']->persist($background);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le background a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/addBackground.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'background' => $background,
		));
	}
		
	/**
	 * Modifie le background d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateBackgroundAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$background = $request->get('background');

		$form = $app['form.factory']->createBuilder(new PersonnageBackgroundForm(), $background)
			->add('visibility','choice', array(
					'required' => true,
					'label' =>  'Visibilité',
					'choices' => $app['larp.manager']->getPersonnageBackgroundVisibility(),
			))
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$background = $form->getData();
		
			$app['orm.em']->persist($background);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le background a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/updateBackground.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'background' => $background,
		));
	}
	
	/**
	 * Modification de la renommee du personnage
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateRenommeAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$form = $app['form.factory']->createBuilder(new PersonnageUpdateRenommeForm(), $personnage)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
				
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/updateRenomme.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage));
	}
	
	/**
	 * Ajoute un trigger 
	 */
	public function adminTriggerAddAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$trigger = new \LarpManager\Entities\PersonnageTrigger();
		$trigger->setPersonnage($personnage);
		$trigger->setDone(false);
		
		$form = $app['form.factory']->createBuilder(new TriggerForm(), $trigger)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$trigger = $form->getData();
		
			$app['orm.em']->persist($trigger);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le déclencheur a été ajouté.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/addTrigger.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage));
	}
		
	/**
	 * Supprime un trigger
	 */
	public function adminTriggerDeleteAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$trigger = $request->get('trigger');
		
		$form = $app['form.factory']->createBuilder(new TriggerDeleteForm(), $trigger)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$trigger = $form->getData();
		
			$app['orm.em']->remove($trigger);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le déclencheur a été supprimé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/deleteTrigger.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'trigger' => $trigger,
		));
	}		
	
	/**
	 * Modifie la liste des domaines de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateDomaineAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$domaines = $app['orm.em']->getRepository('LarpManager\Entities\Domaine')->findAll();
		
		$originalDomaines = new ArrayCollection();
		foreach ( $personnage->getDomaines() as $domaine)
		{
			$originalDomaines[] = $domaine;
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageUpdateDomaineForm(), $personnage)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
			
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			foreach($personnage->getDomaines() as $domaine)
			{
				if ( ! $originalDomaines->contains($domaine))
				{
					$domaine->addPersonnage($personnage);
				}
			}
			
			foreach ( $originalDomaines as $domaine)
			{
				if ( ! $personnage->getDomaines()->contains($domaine))
				{
					$domaine->removePersonnage($personnage);
				}
			}
	
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/updateDomaine.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
		
	}
	
	/**
	 * Modifie la liste des langues
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateLangueAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
				
		$langues = $app['orm.em']->getRepository('LarpManager\Entities\Langue')->findAll();
			
		$originalLanguages = array();
		foreach ( $personnage->getLanguages() as $languages)
		{
			$originalLanguages[] = $languages;
		}
		
		$form = $app['form.factory']->createBuilder()
			->add('langues','entity', array(
					'required' => true,
					'label' => 'Choisissez les languages',
					'multiple' => true,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Langue',
					'choices' => $langues,
					'choice_label' => 'label',
					'data' => $originalLanguages,
			))
			->add('save','submit', array('label' => 'Valider vos modifications'))
			->getForm();

		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$langues = $data['langues'];

			// pour toutes les nouvelles langues
			foreach( $langues as $langue)
			{
				if ( ! $personnage->isKnownLanguage($langue))
				{
					$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
					$personnageLangue->setPersonnage($personnage);
					$personnageLangue->setLangue($langue);
					$personnageLangue->setSource('ADMIN');
					$app['orm.em']->persist($personnageLangue);
				}
			}
			
			if ( count($langues) == 0 )
			{
				foreach( $personnage->getLanguages() as $langue)
				{
					$personnageLangue = $personnage->getPersonnageLangue($langue);
					$app['orm.em']->remove($personnageLangue);
				}
			}
			else 
			{
				foreach( $personnage->getLanguages() as $langue)
				{
					$found = false;
					foreach ( $langues as $l)
					{
						if ($l == $langue) $found = true;
					}
					
					if ( ! $found )
					{
						$personnageLangue = $personnage->getPersonnageLangue($langue);
						$app['orm.em']->remove($personnageLangue);
					}
				}
			}
			
				
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
	
		return $app['twig']->render('admin/personnage/updateLangue.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Modifie la liste des prières
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdatePriereAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
			
		$originalPrieres = new ArrayCollection();
		foreach ( $personnage->getPrieres() as $priere)
		{
			$originalPrieres[] = $priere;
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageUpdatePriereForm(), $personnage)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			foreach($personnage->getPrieres() as $priere)
			{
				if ( ! $originalPrieres->contains($priere))
				{
					$priere->addPersonnage($personnage);
				}
			}
			
			foreach ( $originalPrieres as $priere)
			{
				if ( ! $personnage->getPrieres()->contains($priere))
				{
					$priere->removePersonnage($personnage);
				}
			}
	
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
	
		return $app['twig']->render('admin/personnage/updatePriere.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	
	
	/**
	 * Modifie la liste des sorts
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateSortAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
			
		$form = $app['form.factory']->createBuilder(new PersonnageUpdateSortForm(), $personnage)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/updateSort.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Modifie la liste des potions
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdatePotionAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
			
		$form = $app['form.factory']->createBuilder(new PersonnageUpdatePotionForm(), $personnage)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
				
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
	
		return $app['twig']->render('admin/personnage/updatePotion.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Ajoute une religion à un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddReligionAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		// refuser la demande si le personnage est Fanatique
		if ( $personnage->isFanatique() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, le personnage êtes un Fanatique, il vous est impossible de choisir une nouvelle religion. (supprimer la religion fanatique qu\'il possède avant)' );
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		$personnageReligion = new \LarpManager\Entities\PersonnagesReligions();
		$personnageReligion->setPersonnage($personnage);
		
		// ne proposer que les religions que le personnage ne pratique pas déjà ...
		$availableReligions = $app['personnage.manager']->getAvailableReligions($personnage);
		
		if ( $availableReligions->count() == 0 )
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'y a plus de religion disponibles');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		// construit le tableau de choix
		$choices = array();
		foreach ( $availableReligions as $religion)
		{
			$choices[] = $religion;
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageReligionForm(), $personnageReligion)
			->add('religion','entity', array(
					'required' => true,
					'label' => 'Votre religion',
					'class' => 'LarpManager\Entities\Religion',
					'choices' => $availableReligions,
					'property' => 'label',
			))
			->add('save','submit', array('label' => 'Valider votre religion'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$personnageReligion = $form->getData();
			
			// supprimer toutes les autres religions si l'utilisateur à choisi fanatique
			// n'autoriser que un Fervent que si l'utilisateur n'a pas encore Fervent.
			if ( $personnageReligion->getReligionLevel()->getIndex() == 3 )
			{
				$personnagesReligions = $personnage->getPersonnagesReligions();
				foreach ( $personnagesReligions as $oldReligion)
				{
					$app['orm.em']->remove($oldReligion);
				}
			}
			else if ( $personnageReligion->getReligionLevel()->getIndex() == 2 )
			{
				if ( $personnage->isFervent() )
				{
					$app['session']->getFlashBag()->add('error','Désolé, vous êtes déjà Fervent d\'une autre religion, il vous est impossible de choisir une nouvelle religion en tant que Fervent. Veuillez contacter votre orga en cas de problème.');
					return $app->redirect($app['url_generator']->generate('homepage'),301);
				}
			}
						
			$app['orm.em']->persist($personnageReligion);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/addReligion.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage));
		
	}
	
	/**
	 * Retire une religion d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminRemoveReligionAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$personnageReligion = $request->get('personnageReligion');
		
		$form = $app['form.factory']->createBuilder()
			->add('save','submit', array('label' => 'Retirer la religion'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			$app['orm.em']->remove($personnageReligion);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/removeReligion.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'personnageReligion'=> $personnageReligion,
		));
	}
	
	/**
	 * Retire une langue d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminRemoveLangueAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$personnageLangue = $request->get('personnageLangue');
		
		$form = $app['form.factory']->createBuilder()
			->add('save','submit', array('label' => 'Retirer la langue'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
				
			$app['orm.em']->remove($personnageLangue);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/removeLangue.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'personnageLangue'=> $personnageLangue,
		));
	}
	
	/**
	 * Modifie l'origine d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateOriginAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$oldOrigine = $personnage->getTerritoire();
		
		$form = $app['form.factory']->createBuilder(new PersonnageOriginForm(), $personnage)
			->add('save','submit', array('label' => 'Valider l\'origine du personnage'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			// le personnage doit perdre les langues de son ancienne origine
			// et récupérer les langue de sa nouvelle origine
			foreach( $personnage->getPersonnageLangues() as $personnageLangue )
			{
				if ($personnageLangue->getSource() == 'ORIGINE')
				{
					$personnage->removePersonnageLangues($personnageLangue);
					$app['orm.em']->remove($personnageLangue);
				}
			}			
			$newOrigine = $personnage->getTerritoire();
			foreach ( $newOrigine->getLangues() as $langue )
			{
				$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
				$personnageLangue->setPersonnage($personnage);
				$personnageLangue->setSource('ORIGINE');
				$personnageLangue->setLangue($langue);
				
				$app['orm.em']->persist($personnageLangue);
				$personnage->addPersonnageLangues($personnageLangue);
			}
						
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		return $app['twig']->render('admin/personnage/updateOrigine.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Ajoute une religion au personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addReligionAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		if ( $personnage->getGroupe()->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'est plus possible de modifier ce personnage. Le groupe est verouillé. Contacter votre scénariste si vous pensez que cela est une erreur');
			return $app->redirect($app['url_generator']->generate('homepage',array('index'=>$id)),301);
		}
		
		
		// refuser la demande si le personnage est Fanatique
		if ( $personnage->isFanatique() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous êtes un Fanatique, il vous est impossible de choisir une nouvelle religion. Veuillez contacter votre orga en cas de problème.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		$personnageReligion = new \LarpManager\Entities\PersonnagesReligions();
		$personnageReligion->setPersonnage($personnage);
		
		// ne proposer que les religions que le personnage ne pratique pas déjà ...
		$availableReligions = $app['personnage.manager']->getAvailableReligions($personnage);
		
		if ( $availableReligions->count() == 0 )
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'y a plus de religion disponibles ( Sérieusement ? vous êtes éclectique, c\'est bien, mais ... faudrait savoir ce que vous voulez non ? L\'heure n\'est-il pas venu de faire un choix parmi tous ces dieux ?)');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		// construit le tableau de choix
		$choices = array();
		foreach ( $availableReligions as $religion)
		{
			$choices[] = $religion;
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageReligionForm(), $personnageReligion)
			->add('religion','entity', array(
					'required' => true,
					'label' => 'Votre religion',
					'class' => 'LarpManager\Entities\Religion',
					'choices' => $availableReligions,
					'property' => 'label',
			))
			->add('save','submit', array('label' => 'Valider votre religion'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnageReligion = $form->getData();
			
			// supprimer toutes les autres religions si l'utilisateur à choisi fanatique
			// n'autoriser que un Fervent que si l'utilisateur n'a pas encore Fervent.
			if ( $personnageReligion->getReligionLevel()->getIndex() == 3 )
			{
				$personnagesReligions = $personnage->getPersonnagesReligions();
				foreach ( $personnagesReligions as $oldReligion)
				{
					$app['orm.em']->remove($oldReligion);
				}
			}
			else if ( $personnageReligion->getReligionLevel()->getIndex() == 2 )
			{
				if ( $personnage->isFervent() )
				{
					$app['session']->getFlashBag()->add('error','Désolé, vous êtes déjà Fervent d\'une autre religion, il vous est impossible de choisir une nouvelle religion en tant que Fervent. Veuillez contacter votre orga en cas de problème.');
					return $app->redirect($app['url_generator']->generate('homepage'),301);
				}
			}
						
			$app['orm.em']->persist($personnageReligion);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage'),301);
		}
		
		return $app['twig']->render('public/personnage/religion_add.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Mise à jour de l'origine d'un personnage.
	 * Impossible si le personnage dispose déjà d'une origine.
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateOriginAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		if ( $personnage->getGroupe()->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'est plus possible de modifier ce personnage. Le groupe est verouillé. Contacter votre scénariste si vous pensez que cela est une erreur');
			return $app->redirect($app['url_generator']->generate('homepage',array('index'=>$id)),301);
		}
		
		if ( $personnage->getTerritoire() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'est pas possible de modifier votre origine. Veuillez contacter votre orga pour exposer votre problème.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageOriginForm(), $personnage)
			->add('save','submit', array('label' => 'Valider votre origine'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage'),301);
		}
		
		return $app['twig']->render('public/personnage/origin_add.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	
	/**
	 * Retire la dernière compétence acquise par un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function removeCompetenceAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$lastCompetence = $app['personnage.manager']->getLastCompetence($personnage);
		
		if ( ! $lastCompetence ) {
			$app['session']->getFlashBag()->add('error','Désolé, le personnage n\'a pas encore acquis de compétences');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		$form = $app['form.factory']->createBuilder()
			->add('save','submit', array('label' => 'Retirer la compétence'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			$cout = $app['personnage.manager']->getCompetenceCout($personnage, $lastCompetence);
			$xp = $personnage->getXp();
			
			$personnage->setXp($xp + $cout);
			$personnage->removeCompetence($lastCompetence);
			$lastCompetence->removePersonnage($personnage);
			
			// cas special noblesse
			// noblesse apprentit +2 renomme
			// noblesse initie  +3 renomme
			// noblesse expert +2 renomme
			// TODO : trouver un moyen pour ne pas implémenter les règles spéciales de ce type dans le code.
			if ( $lastCompetence->getCompetenceFamily()->getLabel() == "Noblesse")
			{
				switch ($lastCompetence->getLevel()->getId())
				{
					case 1:
						$personnage->removeRenomme(2);
						break;
					case 2:
						$personnage->removeRenomme(3);
						break;
					case 3:
						$personnage->removeRenomme(2);
						break;
				}
			}
			
			// historique
			$historique = new \LarpManager\Entities\ExperienceGain();
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setXpGain($cout);
			$historique->setExplanation('Suppression de la compétence ' . $lastCompetence->getLabel());
			$historique->setPersonnage($personnage);
				
			$app['orm.em']->persist($lastCompetence);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->persist($historique);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','La compétence a été retirée');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);			
		}
		
		return $app['twig']->render('admin/personnage/removeCompetence.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'competence' =>  $lastCompetence,
		));
		
	}
	
	public function adminAddCompetenceAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$availableCompetences = $app['personnage.manager']->getAvailableCompetences($personnage);
		
		if ( $availableCompetences->count() == 0 )
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'y a plus de compétence disponible.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		// construit le tableau de choix
		$choices = array();
		foreach ( $availableCompetences as $competence)
		{
			$choices[$competence->getId()] = $competence->getLabel() . ' (cout : '.$app['personnage.manager']->getCompetenceCout($personnage, $competence).' xp)';
		}
		
		$form = $app['form.factory']->createBuilder()
			->add('competenceId','choice', array(
					'label' =>  'Choisissez une nouvelle compétence',
					'choices' => $choices,
			))
			->add('save','submit', array('label' => 'Valider la compétence'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$data = $form->getData();
				
			$competenceId = $data['competenceId'];
			$competence = $app['orm.em']->find('\LarpManager\Entities\Competence', $competenceId);
			
			$cout = $app['personnage.manager']->getCompetenceCout($personnage, $competence);
			$xp = $personnage->getXp();
				
			if ( $xp - $cout < 0 )
			{
				$app['session']->getFlashBag()->add('error','Vos n\'avez pas suffisement de point d\'expérience pour acquérir cette compétence.');
				return $app->redirect($app['url_generator']->generate('homepage'),301);
			}
			$personnage->setXp($xp - $cout);
			$personnage->addCompetence($competence);
			$competence->addPersonnage($personnage);
			
			// historique
			$historique = new \LarpManager\Entities\ExperienceUsage();
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setXpUse($cout);
			$historique->setCompetence($competence);
			$historique->setPersonnage($personnage);
				
			$app['orm.em']->persist($competence);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->persist($historique);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail', array('personnage' => $personnage->getId())),301);
		}
			
		return $app['twig']->render('admin/personnage/competence.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'competences' =>  $availableCompetences,
		));
	}
	
	/**
	 * Ajoute une compétence au personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addCompetenceAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		if ( $personnage->getGroupe()->getLock() == true)
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'est plus possible de modifier ce personnage. Le groupe est verouillé. Contacter votre scénariste si vous pensez que cela est une erreur');
			return $app->redirect($app['url_generator']->generate('homepage',array('index'=>$id)),301);
		}
		
		$availableCompetences = $app['personnage.manager']->getAvailableCompetences($personnage);
		
		if ( $availableCompetences->count() == 0 )
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'y a plus de compétence disponible.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		// construit le tableau de choix
		$choices = array();
		foreach ( $availableCompetences as $competence)
		{
			$choices[$competence->getId()] = $competence->getLabel() . ' (cout : '.$app['personnage.manager']->getCompetenceCout($personnage, $competence).' xp)';
		}
		
		$form = $app['form.factory']->createBuilder()
					->add('competenceId','choice', array(
							'label' =>  'Choisissez une nouvelle compétence',
							'choices' => $choices,
					))
					->add('save','submit', array('label' => 'Valider la compétence'))
					->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			$competenceId = $data['competenceId'];
			$competence = $app['orm.em']->find('\LarpManager\Entities\Competence', $competenceId);
						
			$cout = $app['personnage.manager']->getCompetenceCout($personnage, $competence);
			$xp = $personnage->getXp();
			
			if ( $xp - $cout < 0 )
			{
				$app['session']->getFlashBag()->add('error','Vos n\'avez pas suffisement de point d\'expérience pour acquérir cette compétence.');
				return $app->redirect($app['url_generator']->generate('homepage'),301);
			}
			$personnage->setXp($xp - $cout);
			$personnage->addCompetence($competence);
			$competence->addPersonnage($personnage);
			
			// cas special noblesse
			// noblesse apprentit +2 renomme
			// noblesse initie  +3 renomme
			// noblesse expert +2 renomme
			// TODO : trouver un moyen pour ne pas implémenter les règles spéciales de ce type dans le code.
			if ( $competence->getCompetenceFamily()->getLabel() == "Noblesse")
			{
				switch ($competence->getLevel()->getId())
				{
					case 1:
						$personnage->addRenomme(2);
						break;
					case 2:
						$personnage->addRenomme(3);
						break;
					case 3:
						$personnage->addRenomme(2);
						break;
				}
			}
			
			// cas special prêtrise
			if ( $competence->getCompetenceFamily()->getLabel() == "Prêtrise")
			{
				// le personnage doit avoir une religion au niveau fervent ou fanatique
				if ( $personnage->isFervent() || $personnage->isFanatique() )
				{
					// ajoute toutes les prières de niveau de sa compétence liés aux sphère de sa religion fervente ou fanatique
					$religion = $personnage->getMainReligion();
					foreach ( $religion->getSpheres() as $sphere)
					{
						foreach ( $sphere->getPrieres() as $priere)
						{
							if ( $priere->getNiveau() == $competence->getLevel()->getId() )
							{
								$priere->addPersonnage($personnage);
								$personnage->addPriere($priere);
							}
						}
					}
				}
				else
				{
					$app['session']->getFlashBag()->add('error','Pour obtenir la compétence Prêtrise, vous devez être FERVENT ou FANATIQUE');
					return $app->redirect($app['url_generator']->generate('homepage'),301);
				}
			}
			
			
			// cas special alchimie
			if ( $competence->getCompetenceFamily()->getLabel() == "Alchimie")
			{
				switch ($competence->getLevel()->getId())
				{
					case 1: // le personnage doit choisir 2 potions de niveau apprenti 
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('ALCHIMIE APPRENTI');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('ALCHIMIE APPRENTI');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						break;
					case 2: // le personnage doit choisir 1 potion de niveau initie et 1 potion de niveau apprenti
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('ALCHIMIE INITIE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('ALCHIMIE APPRENTI');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						break;
					case 3: // le personnage doit choisir 1 potion de niveau expert
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('ALCHIMIE EXPERT');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						break;
					case 4: // le personnage doit choisir 1 potion de niveau maitre
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('ALCHIMIE MAITRE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						break;
				}
			}
			
			// cas special magie
			if ( $competence->getCompetenceFamily()->getLabel() == "Magie")
			{
				switch ($competence->getLevel()->getId())
				{
					case 1: // le personnage doit choisir un domaine de magie
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('MAGIE APPRENTI');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						
						// il obtient aussi la possibilité de choisir un sort de niveau 1
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('MAGIE APPRENTI SORT');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						break;
					case 2:
						// il obtient aussi la possibilité de choisir un sort de niveau 2
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('MAGIE INITIE SORT');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						break;
					case 3: // le personnage peut choisir un nouveau domaine de magie
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('MAGIE EXPERT');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						
						// il obtient aussi la possibilité de choisir un sort de niveau 3
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('MAGIE EXPERT SORT');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
					case 4:
						break;
						// il obtient aussi la possibilité de choisir un sort de niveau 4
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('MAGIE MAITRE SORT');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
				}
			}
			
			// cas special littérature
			if ( $competence->getCompetenceFamily()->getLabel() == "Littérature")
			{
				switch ($competence->getLevel()->getId())
				{
					case 1: // le personnage obtient toutes les langues "très répandus"
						$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Langue');
						$langues = $repo->findAll();
						
						foreach ( $langues as $langue)
						{
							if ( $langue->getDiffusion() == 2 )
							{
								if ( ! $personnage->isKnownLanguage($langue) )
								{
									$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
									$personnageLangue->setPersonnage($personnage);
									$personnageLangue->setLangue($langue);
									$personnageLangue->setSource('LITTERATURE APPRENTI');
									
									$app['orm.em']->persist($personnageLangue);
								}
							}
						}
						break;
					case 2: // le personnage peux choisir trois languages supplémentaire (sauf parmi les anciens)
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LITTERATURE INITIE');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						break;
					case 3: // le personnage peux choisir trois languages supplémentaire (dont un ancien)
						$trigger = new \LarpManager\Entities\PersonnageTrigger();
						$trigger->setPersonnage($personnage);
						$trigger->setTag('LITTERATURE EXPERT');
						$trigger->setDone(false);
						$app['orm.em']->persist($trigger);
						break;
					case 4: // le personnage obtient tous les languages courants
						$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Langue');
						$langues = $repo->findAll();
						
						foreach ( $langues as $langue)
						{
							if ( $langue->getDiffusion() > 0 )
							{
								if ( ! $personnage->isKnownLanguage($langue) )
								{
									$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
									$personnageLangue->setPersonnage($personnage);
									$personnageLangue->setLangue($langue);
									$personnageLangue->setSource('LITTERATURE MAITRE');
										
									$app['orm.em']->persist($personnageLangue);
								}
							}
						}
						break;
				}
			}
			
			// historique
			$historique = new \LarpManager\Entities\ExperienceUsage();
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setXpUse($cout);
			$historique->setCompetence($competence);
			$historique->setPersonnage($personnage);
			
			$app['orm.em']->persist($competence);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->persist($historique);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage'),301);
		}
		
		return $app['twig']->render('personnage/competence.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'competences' =>  $availableCompetences,
		));
	}
	
	/**
	 * Choix d'une nouvelle potion
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$niveau = $request->get('niveau');
	
		if ( ! $personnage->hasTrigger('ALCHIMIE APPRENTI')
				&& ! $personnage->hasTrigger('ALCHIMIE INITIE')
				&& ! $personnage->hasTrigger('ALCHIMIE EXPERT')
				&& ! $personnage->hasTrigger('ALCHIMIE MAITRE') )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous ne pouvez pas choisir de potions supplémentaires.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Potion');
		$potions = $repo->findByNiveau($niveau);
		
		$form = $app['form.factory']->createBuilder()
			->add('potions','entity', array(
					'required' => true,
					'label' => 'Choisissez votre potion',
					'multiple' => false,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Potion',
					'choices' => $potions,
					'choice_label' => 'fullLabel'
			))
			->add('save','submit', array('label' => 'Valider votre potion'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$potion = $data['potions'];
		
			// Ajout de la potion au personnage
			$personnage->addPotion($potion);
			$app['orm.em']->persist($personnage);
		
			// suppression du trigger
			switch( $niveau)
			{
				case 1:
					$trigger = $personnage->getTrigger('ALCHIMIE APPRENTI');
					$app['orm.em']->remove($trigger);
					break;
				case 2:
					$trigger = $personnage->getTrigger('ALCHIMIE INITIE');
					$app['orm.em']->remove($trigger);
					break;
				case  3:
					$trigger = $personnage->getTrigger('ALCHIMIE EXPERT');
					$app['orm.em']->remove($trigger);
					break;
				case  4:
					$trigger = $personnage->getTrigger('ALCHIMIE MAITRE');
					$app['orm.em']->remove($trigger);
					break;
			}
		
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Vos modifications ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('personnage'),301);
		}
		
		return $app['twig']->render('personnage/potion.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'potions' => $potions,
				'niveau' => $niveau,
		));
	}
	
	/**
	 * Choix d'un nouveau sortilège
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$niveau = $request->get('niveau');
		
		if ( ! $personnage->hasTrigger('MAGIE APPRENTI SORT')
			&& ! $personnage->hasTrigger('MAGIE INITIE SORT') 
			&& ! $personnage->hasTrigger('MAGIE EXPERT SORT') 
			&& ! $personnage->hasTrigger('MAGIE MAITRE SORT') )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous ne pouvez pas choisir de sortilèges supplémentaires.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Sort');
		$sorts = $repo->findByNiveau($niveau);
		
		$form = $app['form.factory']->createBuilder()
			->add('sorts','entity', array(
				'required' => true,
				'label' => 'Choisissez votre sortilège',
				'multiple' => false,
				'expanded' => true,
				'class' => 'LarpManager\Entities\Sort',
				'choices' => $sorts,
				'choice_label' => 'label'
			))
			->add('save','submit', array('label' => 'Valider votre sortilège'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$sort = $data['sorts'];
				
			// Ajout du domaine de magie au personnage
			$personnage->addSort($sort);
			$app['orm.em']->persist($personnage);
				
			// suppression du trigger
			switch( $niveau)
			{
				case 1:
					$trigger = $personnage->getTrigger('MAGIE APPRENTI SORT');
					$app['orm.em']->remove($trigger);
					break;
				case 2:
					$trigger = $personnage->getTrigger('MAGIE INITIE SORT');
					$app['orm.em']->remove($trigger);
					break;
				case  3:
					$trigger = $personnage->getTrigger('MAGIE EXPERT SORT');
					$app['orm.em']->remove($trigger);
					break;
				case  4:
					$trigger = $personnage->getTrigger('MAGIE MAITRE SORT');
					$app['orm.em']->remove($trigger);
					break;
			}
						
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Vos modifications ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('personnage'),301);
		}
		
		return $app['twig']->render('personnage/sort.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'sorts' => $sorts,
				'niveau' => $niveau,
		));
	}
	
	/**
	 * Choix d'un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineMagieAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		if ( ! $personnage->hasTrigger('MAGIE APPRENTI') 
			&& ! $personnage->hasTrigger('MAGIE EXPERT') )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous ne pouvez pas choisir de domaine de magie supplémentaire.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Domaine');
		$domaines = $repo->findAll();
		
		$form = $app['form.factory']->createBuilder()
			->add('domaines','entity', array(
				'required' => true,
				'label' => 'Choisissez votre domaine de magie',
				'multiple' => false,
				'expanded' => true,
				'class' => 'LarpManager\Entities\Domaine',
				'choices' => $domaines,
				'choice_label' => 'label'
			))
			->add('save','submit', array('label' => 'Valider votre domaine de magie'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$domaine = $data['domaines'];
			
			// Ajout du domaine de magie au personnage
			$personnage->addDomaine($domaine);
			$app['orm.em']->persist($personnage);
			
			// suppression du trigger
			if ( $personnage->hasTrigger('MAGIE APPRENTI') )
			{
				$trigger = $personnage->getTrigger('MAGIE APPRENTI');
				$app['orm.em']->remove($trigger);
			}
			if ( $personnage->hasTrigger('MAGIE EXPERT') )
			{
				$trigger = $personnage->getTrigger('MAGIE EXPERT');
				$app['orm.em']->remove($trigger);
			}
				
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Vos modifications ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('personnage'),301);
			
		}

		$domaines = $app['orm.em']->getRepository('\LarpManager\Entities\Domaine')->findAll();
		
		return $app['twig']->render('personnage/domaineMagie.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'domaines' => $domaines,
		));
	}
	
	public function litteratureInitieAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$availableLangues = $app['personnage.manager']->getAvailableLangues($personnage, 1);
		
		$form = $app['form.factory']->createBuilder()
			->add('langues','entity', array(
				'required' => true,
				'label' => 'Choisissez vos nouvelles langues',
				'multiple' => true,
				'expanded' => true,
				'class' => 'LarpManager\Entities\Langue',
				'choices' => $availableLangues,
				'choice_label' => 'fullDescription'
			))
			->add('save','submit', array('label' => 'Valider vos nouvelles langues'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$langues = $data['langues'];

			// si le personnage a plus de trois langues, refuser
			if ( count($langues) > 3 )
			{
				$app['session']->getFlashBag()->add('error','Vous ne pouvez choisir que 3 langues au maximum.');
				return $app->redirect($app['url_generator']->generate('personnage'),301);
			}
			foreach ( $langues as $langueId)
			{
				$langue = $app['orm.em']->find('\LarpManager\Entities\Langue', $langueId);
				$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
				$personnageLangue->setPersonnage($personnage);
				$personnageLangue->setLangue($langue);
				$personnageLangue->setSource('LITTERATURE INITIE');
				$app['orm.em']->persist($personnageLangue);
			}
			
			// suppression du trigger
			$trigger = $personnage->getTrigger('LITTERATURE INITIE');
			$app['orm.em']->remove($trigger);
			
			$app['orm.em']->flush();

			$app['session']->getFlashBag()->add('success','Vos modifications ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('personnage'),301);
		}
		
		return $app['twig']->render('personnage/litteratureInitie.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	public function litteratureExpertAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$availableLangues = $app['personnage.manager']->getAvailableLangues($personnage, 0);
		
		$form = $app['form.factory']->createBuilder()
			->add('langues','entity', array(
				'required' => true,
				'label' => 'Choisissez vos nouvelles langues',
				'multiple' => true,
				'expanded' => true,
				'class' => 'LarpManager\Entities\Langue',
				'choices' => $availableLangues,
				'choice_label' => 'fullDescription'
			))
			->add('save','submit', array('label' => 'Valider vos nouvelles langues'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$langues = $data['langues'];

			// si le personnage a plus de trois langues, refuser
			if ( count($langues) > 3 )
			{
				$app['session']->getFlashBag()->add('error','Vous ne pouvez choisir que 3 langues au maximum.');
				return $app->redirect($app['url_generator']->generate('personnage'),301);
			}
			foreach ( $langues as $langueId)
			{
				$langue = $app['orm.em']->find('\LarpManager\Entities\Langue', $langueId);
				$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
				$personnageLangue->setPersonnage($personnage);
				$personnageLangue->setLangue($langue);
				$personnageLangue->setSource('LITTERATURE EXPERT');
				$app['orm.em']->persist($personnageLangue);
			}
			
			// suppression du trigger
			$trigger = $personnage->getTrigger('LITTERATURE EXPERT');
			$app['orm.em']->remove($trigger);
			
			$app['orm.em']->flush();

			$app['session']->getFlashBag()->add('success','Vos modifications ont été enregistrées.');
			return $app->redirect($app['url_generator']->generate('personnage'),301);
		}
		
		return $app['twig']->render('personnage/litteratureExpert.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Exporte la fiche d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function exportAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$app['pdf.manager']->AddPage();
		$app['pdf.manager']->SetFont('Arial','B',16);
		$app['pdf.manager']->Cell(40,10,$personnage->getNom());
		
		header("Content-Type: application/pdf");
		header("Content-Disposition: attachment; filename=".$personnage->getNom()."_".date("Ymd").".pdf");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$output = fopen("php://output", "w");
		fputs($app['pdf.manager']->Output($personnage->getNom()."_".date("Ymd").".pdf",'I'));
		fclose($output);
		exit();
		
	}
}