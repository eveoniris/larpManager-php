<?php

namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Doctrine\Common\Collections\ArrayCollection;
use JasonGrimes\Paginator;
use LarpManager\Form\GroupeForm;
use LarpManager\Form\PersonnageForm;
use LarpManager\Form\GroupFindForm;
use LarpManager\Form\BackgroundForm;
use LarpManager\Form\RequestAllianceForm;
use LarpManager\Form\AcceptAllianceForm;
use LarpManager\Form\CancelRequestedAllianceForm;
use LarpManager\Form\RefuseAllianceForm;
use LarpManager\Form\BreakAllianceForm;
use LarpManager\Form\DeclareWarForm;
use LarpManager\Form\RequestPeaceForm;
use LarpManager\Form\AcceptPeaceForm;
use LarpManager\Form\RefusePeaceForm;
use LarpManager\Form\CancelRequestedPeaceForm;
use LarpManager\Form\GroupeInscriptionForm;


/**
 * LarpManager\Controllers\GroupeController
 *
 * @author kevin
 *
 */
class GroupeController
{
	/**
	 * Page d'accueil de gestion des groupes
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function accueilAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new GroupeInscriptionForm(), array())
			->add('subscribe','submit', array('label' => 'S\'inscrire'))
			->getForm();
		
		return $app['twig']->render('public/groupe/accueil.twig', array(
				'form' => $form->createView()
		));
	}
	
	/**
	 * Visualisation des liens entre groupes
	 * @param Request $request
	 * @param Application $app
	 */
	public function diplomatieAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
		$groupes = $repo->findAll();
		
		return $app['twig']->render('admin/diplomatie.twig', array(
				'groupes' => $groupes
		));
	}
	
	/**
	 * Demander une nouvelle alliance
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function requestAllianceAction(Request $request, Application $app)
	{
		$groupe = $request->get('groupe');
		
		// un groupe ne peux pas avoir plus de 3 alliances
		if ( $groupe->getAlliances()->count() >= 3 )
		{
			$app['session']->getFlashBag()->add('error', 'Désolé, vous avez déjà 3 alliances, ce qui est le maximum possible.');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}
		
		// un groupe ne peux pas avoir plus d'alliances que d'ennemis
		if ( $groupe->getEnnemies()->count() - $groupe->getAlliances()->count() <= 0 )
		{
			$app['session']->getFlashBag()->add('error', 'Désolé, vous n\'avez pas suffisement d\'ennemis pour pouvoir vous choisir un allié.');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}
		
		$alliance = new \LarpManager\Entities\GroupeAllie();
		$alliance->setGroupe($groupe);
		
		$form = $app['form.factory']->createBuilder(new RequestAllianceForm(), $alliance)
			->add('send','submit', array('label' => 'Envoyer'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ($request->isMethod('POST')) {
			
			$alliance = $form->getData();
			$alliance->setGroupeAccepted(true);
			$alliance->setGroupeAllieAccepted(false);
			
			// vérification des conditions pour le groupe choisi
			$requestedGroupe = $alliance->getRequestedGroupe();
			if ( $requestedGroupe == $groupe)
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, vous ne pouvez pas choisir votre propre groupe pour faire une alliance ...');
				return $app->redirect($app['url_generator']->generate('groupe'));
			}
			
			if ( $groupe->isAllyTo($requestedGroupe))
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, vous êtes déjà allié avec ce groupe');
				return $app->redirect($app['url_generator']->generate('groupe'));
			}
			
			if ( $groupe->isEnemyTo($requestedGroupe))
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, vous êtes ennemi avec ce groupe. Impossible de faire une alliance, faites d\'abord la paix !');
				return $app->redirect($app['url_generator']->generate('groupe'));
			}
			
			if ( $requestedGroupe->getAlliances()->count() >= 3 )
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, le groupe demandé dispose déjà de 3 alliances, ce qui est le maximum possible.');
				return $app->redirect($app['url_generator']->generate('groupe'));
			}
			
			if ( $requestedGroupe->getEnnemies()->count() - $requestedGroupe->getAlliances()->count() <= 0 )
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, le groupe demandé n\'a pas suffisement d\'ennemis pour pouvoir obtenir un allié supplémentaire.');
				return $app->redirect($app['url_generator']->generate('groupe'));
			}
			
			$app['orm.em']->persist($alliance);
			$app['orm.em']->flush();
			
			$app['user.mailer']->sendRequestAlliance($alliance);
			
			$app['session']->getFlashBag()->add('success', 'Votre demande a été envoyé.');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}
		
		return $app['twig']->render('public/groupe/requestAlliance.twig', array(
				'groupe' => $groupe,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Annuler une demande d'alliance
	 * @param Request $request
	 * @param Application $app
	 */
	public function cancelRequestedAllianceAction(Request $request, Application $app)
	{
		$groupe = $request->get('groupe');
		$alliance = $request->get('alliance');
		
		$form = $app['form.factory']->createBuilder(new CancelRequestedAllianceForm(), $alliance)
			->add('send','submit', array('label' => 'Oui, j\'annule ma demande'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ($request->isMethod('POST')) {
			
			$alliance = $form->getData();
			
			$app['orm.em']->remove($alliance);
			$app['orm.em']->flush();
			
			$app['user.mailer']->sendCancelAlliance($alliance);
			
			$app['session']->getFlashBag()->add('success', 'Votre demande d\'alliance a été annulée.');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}
		
		return $app['twig']->render('public/groupe/cancelAlliance.twig', array(
				'alliance' => $alliance,
				'groupe' => $groupe,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Accepter une alliance
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function acceptAllianceAction(Request $request, Application $app)
	{
		$groupe = $request->get('groupe');
		$alliance = $request->get('alliance');
		
		$form = $app['form.factory']->createBuilder(new AcceptAllianceForm(), $alliance)
			->add('send','submit', array('label' => 'Envoyer'))
			->getForm();

		$form->handleRequest($request);
		
		if ($request->isMethod('POST')) {
		
			$alliance = $form->getData();
			
			$alliance->setGroupeAllieAccepted(true);
			$app['orm.em']->persist($alliance);
			$app['orm.em']->flush();
		
			$app['user.mailer']->sendAcceptAlliance($alliance);
		
			$app['session']->getFlashBag()->add('success', 'Vous avez accepté la proposition d\'alliance.');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}
			
		return $app['twig']->render('public/groupe/acceptAlliance.twig', array(
				'alliance' => $alliance,
				'groupe' => $groupe,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Refuser une alliance
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function refuseAllianceAction(Request $request, Application $app)
	{
		$groupe = $request->get('groupe');
		$alliance = $request->get('alliance');
		
		$form = $app['form.factory']->createBuilder(new RefuseAllianceForm(), $alliance)
			->add('send','submit', array('label' => 'Envoyer'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ($request->isMethod('POST')) {
				
			$alliance = $form->getData();
				
			$app['orm.em']->remove($alliance);
			$app['orm.em']->flush();
				
			$app['user.mailer']->sendRefuseAlliance($alliance);
				
			$app['session']->getFlashBag()->add('success', 'Vous avez refusé la proposition d\'alliance.');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}
			
		return $app['twig']->render('public/groupe/refuseAlliance.twig', array(
				'alliance' => $alliance,
				'groupe' => $groupe,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Briser une alliance
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function breakAllianceAction(Request $request, Application $app)
	{
		$groupe = $request->get('groupe');
		$alliance = $request->get('alliance');
		
		$form = $app['form.factory']->createBuilder(new BreakAllianceForm(), $alliance)
			->add('send','submit', array('label' => 'Envoyer'))
			->getForm();

		$form->handleRequest($request);
		
		if ($request->isMethod('POST')) {
		
			$alliance = $form->getData();
						
		
			$app['orm.em']->remove($alliance);
			$app['orm.em']->flush();
		
			if  ( $alliance->getGroupe() == $groupe )
			{
				$app['user.mailer']->sendBreakAlliance($alliance, $alliance->getRequestedGroupe());
			}
			else
			{
				$app['user.mailer']->sendBreakAlliance($alliance, $groupe);
			}
			
		
			$app['session']->getFlashBag()->add('success', 'Vous avez brisé une alliance.');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}
			
		return $app['twig']->render('public/groupe/breakAlliance.twig', array(
				'alliance' => $alliance,
				'groupe' => $groupe,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Déclarer la guerre
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function declareWarAction(Request $request, Application $app)
	{
		$groupe = $request->get('groupe');
		
		// un groupe ne peux pas faire de déclaration de guerre si il a 3 ou plus ennemis
		if ( $groupe->getEnnemies()->count() >= 3 )
		{
			$app['session']->getFlashBag()->add('error', 'Désolé, vous avez déjà 3 ennemis ou plus, impossible de faire une nouvelle déclaration de guerre .');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}
		
		$war = new \LarpManager\Entities\GroupeEnemy();
		$war->setGroupe($groupe);
		$war->setGroupePeace(false);
		$war->setGroupeEnemyPeace(false);
		
		$form = $app['form.factory']->createBuilder(new DeclareWarForm(), $war)
			->add('send','submit', array('label' => 'Envoyer'))
			->getForm();
		
		if ($request->isMethod('POST')) {
			
				$form->handleRequest($request);
					
				$war = $form->getData();
				$war->setGroupePeace(false);
				$war->setGroupeEnemyPeace(false);
				
				// vérification des conditions pour le groupe choisi
				$requestedGroupe = $war->getRequestedGroupe();
				if ( $requestedGroupe == $groupe)
				{
					$app['session']->getFlashBag()->add('error', 'Désolé, vous ne pouvez pas choisir votre propre groupe comme ennemi ...');
					return $app->redirect($app['url_generator']->generate('groupe'));
				}
				
				if ( $groupe->isEnemyTo($requestedGroupe))
				{
					$app['session']->getFlashBag()->add('error', 'Désolé, vous êtes déjà en guerre avec ce groupe');
					return $app->redirect($app['url_generator']->generate('groupe'));
				}
					
				if ( $requestedGroupe->getEnnemies()->count() >= 5 )
				{
					$app['session']->getFlashBag()->add('error', 'Désolé, le groupe demandé dispose déjà de 5 ennemis, ce qui est le maximum possible.');
					return $app->redirect($app['url_generator']->generate('groupe'));
				}
				
				if ( $groupe->isEnemyTo($requestedGroupe))
				{
					$app['session']->getFlashBag()->add('error', 'Désolé, vous êtes déjà allié avec ce groupe');
					return $app->redirect($app['url_generator']->generate('groupe'));
				}
				
				$app['orm.em']->persist($war);
				$app['orm.em']->flush();
					
				$app['user.mailer']->sendDeclareWar($war);
					
				$app['session']->getFlashBag()->add('success', 'Votre déclaration de guerre vient d\'être envoyé.');
				return $app->redirect($app['url_generator']->generate('groupe'));
				
		}
			
		return $app['twig']->render('public/groupe/declareWar.twig', array(
				'groupe' => $groupe,
				'form' => $form->createView()
		));
	}

	/**
	 * Demander la paix
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function requestPeaceAction(Request $request, Application $app)
	{
		$groupe = $request->get('groupe');
		$war = $request->get('enemy');
		
		$form = $app['form.factory']->createBuilder(new RequestPeaceForm(), $war)
			->add('send','submit', array('label' => 'Envoyer'))
			->getForm();
		
		if ($request->isMethod('POST')) {
				
			$form->handleRequest($request);
			$war = $form->getData();
			
			if ( $groupe == $war->getGroupe() )
			{
				$war->setGroupePeace(true);
			}
			else
			{
				$war->setGroupeEnemyPeace(true);
			}
			
			$app['orm.em']->persist($war);
			$app['orm.em']->flush();
			
			$app['user.mailer']->sendRequestPeace($war, $groupe);
			
			$app['session']->getFlashBag()->add('success', 'Votre demande de paix vient d\'être envoyé.');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}
		
		return $app['twig']->render('public/groupe/requestPeace.twig', array(
				'war' => $war,
				'groupe' => $groupe,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Accepter la paix
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function acceptPeaceAction(Request $request, Application $app)
	{
		$groupe = $request->get('groupe');
		$war = $request->get('enemy');
		
	
		$form = $app['form.factory']->createBuilder(new AcceptPeaceForm(), $war)
			->add('send','submit', array('label' => 'Envoyer'))
			->getForm();
	
		if ($request->isMethod('POST')) {
		
			$form->handleRequest($request);
			$war = $form->getData();
				
			if ( $groupe == $war->getGroupe() )
			{
				$war->setGroupePeace(true);
			}
			else
			{
				$war->setGroupeEnemyPeace(true);
			}
			
			$app['orm.em']->persist($war);
			$app['orm.em']->flush();
				
			$app['user.mailer']->sendAcceptPeace($war, $groupe);
			
			$app['session']->getFlashBag()->add('success', 'Vous avez fait la paix !');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}	
			
		return $app['twig']->render('public/groupe/acceptPeace.twig', array(
				'war' => $war,
				'groupe' => $groupe,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Refuser la paix
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function refusePeaceAction(Request $request, Application $app)
	{
		$groupe = $request->get('groupe');
		$war = $request->get('enemy');
		
		$form = $app['form.factory']->createBuilder(new RefusePeaceForm(), $war)
			->add('send','submit', array('label' => 'Envoyer'))
			->getForm();
		
		if ($request->isMethod('POST')) {
		
			$form->handleRequest($request);
			$war = $form->getData();
		
			$war->setGroupePeace(false);
			$war->setGroupeEnemyPeace(false);

			$app['orm.em']->persist($war);
			$app['orm.em']->flush();
		
			$app['user.mailer']->sendRefusePeace($war, $groupe);
			
			$app['session']->getFlashBag()->add('success', 'Vous avez refusé la proposition de paix.');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}
			
		return $app['twig']->render('public/groupe/refusePeace.twig', array(
				'war' => $war,
				'groupe' => $groupe,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Annuler la demande de paix
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function cancelRequestedPeaceAction(Request $request, Application $app)
	{
		$groupe = $request->get('groupe');
		$war = $request->get('enemy');
	
		$form = $app['form.factory']->createBuilder(new CancelRequestedPeaceForm(), $war)
			->add('send','submit', array('label' => 'Envoyer'))
			->getForm();
	
		if ($request->isMethod('POST')) {
	
			$form->handleRequest($request);
			$war = $form->getData();
	
			if ( $groupe == $war->getGroupe() )
			{
				$war->setGroupePeace(false);
			}
			else
			{
				$war->setGroupeEnemyPeace(false);
			}
						
			$app['orm.em']->persist($war);
			$app['orm.em']->flush();
	
			$app['user.mailer']->sendRefusePeace($war, $groupe);
				
			$app['session']->getFlashBag()->add('success', 'Vous avez annulé votre proposition de paix.');
			return $app->redirect($app['url_generator']->generate('groupe'));
		}
			
		return $app['twig']->render('public/groupe/cancelPeace.twig', array(
				'war' => $war,
				'groupe' => $groupe,
				'form' => $form->createView()
		));
	}
	
	
	
	/**
	 * Liste des groupes
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminListAction(Request $request, Application $app)
	{
		$order_by = $request->get('order_by') ?: 'numero';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		
		$form = $app['form.factory']->createBuilder(new GroupFindForm())
			->add('find','submit', array('label' => 'Rechercher'))
			->getForm();
		
		$criteria = array();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
		$groupes = $repo->findBy(
				$criteria,
				array( $order_by => $order_dir),
				$limit,
				$offset);
		
		$numResults = $repo->findCount($criteria);
		
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('groupe.admin.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
		
		return $app['twig']->render('admin/groupe/list.twig', array(
				'form' => $form->createView(),
				'groupes' => $groupes,
				'paginator' => $paginator,
		));
	}
	
	/**
	 * Retirer un participant du groupe
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminParticipantRemoveAction(Request $request, Application $app)
	{
		$participantId = $request->get('participant');
		$groupeId = $request->get('groupe');
		
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe',$groupeId);
		$participant = $app['orm.em']->find('\LarpManager\Entities\Participant',$participantId);

		
		if ($request->isMethod('POST')) {
			
			$personnage = $participant->getPersonnage();
			if ( $personnage )
			{
				if ( $personnage->getGroupe() == $groupe)
				{
					$personnage->removeGroupe($groupe);
				}
				$app['orm.em']->persist($personnage);
			}

			$participant->removeGroupe($groupe);
			$app['orm.em']->persist($participant);
			$app['orm.em']->persist($groupe);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le participant a été retiré du groupe.');
			return $app->redirect($app['url_generator']->generate('groupe.detail', array('index'=> $groupe->getId())));
		}
				
		return $app['twig']->render('admin/groupe/removeParticipant.twig', array(
				'groupe' => $groupe,
				'participant' => $participant,
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
			return $app->redirect($app['url_generator']->generate('groupe.admin.list'),301);
		}
		
		return $app['twig']->render('admin/groupe/place.twig', array(
				'groupe' => $groupe));
	}
	
	/**
	 * Ajout d'un background à un groupe
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addBackgroundAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe', $id);
		
		$background = new \LarpManager\Entities\Background();
		$background->setGroupe($groupe);
		
		$form = $app['form.factory']->createBuilder(new BackgroundForm(), $background)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$app['session']->getFlashBag()->add('success','Le background du groupe a été créé');
			return $app->redirect($app['url_generator']->generate('groupe.admin.detail', array('index'=> $groupe->getId()) ),301);
		}
		
		return $app['twig']->render('admin/groupe/background/add.twig', array(
				'groupe' => $groupe,
				'form' => $form->createView(),
			));
	}
	
	/**
	 * Mise à jour du background d'un groupe
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateBackgroundAction(Request $request, Application $app)
	{
		
		$id = $request->get('index');
		$groupe = $app['orm.em']->find('\LarpManager\Entities\Groupe', $id);
		
		$form = $app['form.factory']->createBuilder(new BackgroundForm(), $groupe->getBackground())
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$background = $form->getData();
			
			$app['orm.em']->persist($background);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le background du groupe a été mis à jour');
			return $app->redirect($app['url_generator']->generate('groupe.admin.detail', array('index'=> $groupe->getId()) ),301);
		}
		
		return $app['twig']->render('admin/groupe/background/update.twig', array(
				'groupe' => $groupe,
				'form' => $form->createView(),
		));
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
			->add('responsable', 'entity', array(
					'label' => 'Responsable',
					'required' => false,
					'property' => 'username',
					'class' => 'LarpManager\Entities\User',
					'choices' => $choices,
					'empty_data'  => null,
			))
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
			// défini les droits d'accés à ce forum
			// (les membres du groupe ont le droit d'accéder à ce forum)
			$topic->setRight('GROUPE_MEMBER');
			$topic->setTopic($app['larp.manager']->findTopic('TOPIC_GROUPE'));
			
			$groupe->setTopic($topic);
			
			$app['orm.em']->persist($topic);
			$app['orm.em']->persist($groupe);
			$app['orm.em']->flush();
			
			$topic->setObjectId($groupe->getId());
			$app['orm.em']->persist($topic);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le groupe été sauvegardé');
			
			/**
			 * Si l'utilisateur a cliqué sur "save", renvoi vers la liste des groupes
			 * Si l'utilisateur a cliqué sur "save_continue", renvoi vers un nouveau formulaire d'ajout 
			 */
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupe.admin.list'),301);
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
		$originalTerritoires = new ArrayCollection();

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
		
		/**
		 * Crée un tableau contenant les territoires que ce groupe posséde
		 */
		foreach ( $groupe->getTerritoires() as $territoire)
		{
			$originalTerritoires->add($territoire);
		}
		
		/**
		 * Construit la tableau pour le choix du responsable
		 * @var Array $choices
		 */
		$choices = array();
		foreach ( $groupe->getParticipants() as $participant )
		{
			$choices[] = $participant->getUser();
		}
		
		$form = $app['form.factory']->createBuilder(new GroupeForm(), $groupe)
			->add('responsable', 'entity', array(
				'label' => 'Responsable',
				'required' => false,
				'property' => 'username',
				'class' => 'LarpManager\Entities\User',
				'choices' => $choices,
				'empty_data'  => null,
				))
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
			 * Pour tous les territoire du groupe
			 */
			foreach ( $groupe->getTerritoires() as $territoire )
			{
				$territoire->setGroupe($groupe);
			}
				
			foreach ($originalTerritoires as $territoire)
			{
				if ( $groupe->getTerritoires()->contains($territoire) == false)
					$territoire->setGroupe(null);
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
				// supprime le lien entre les personnages et le groupe
				foreach ( $groupe->getPersonnages() as $personnage)
				{
					$personnage->setGroupe(null);
					$app['orm.em']->persist($personnage);
				}
				
				// supprime le lien entre les participants et le groupe
				foreach ( $groupe->getParticipants() as $participant)
				{
					$participant->setGroupe(null);
					$app['orm.em']->persist($participant);
				}
				
				// supprime la relation entre le groupeClasse et le groupe
				foreach ($groupe->getGroupeClasses() as $groupeClasse) {
					$app['orm.em']->remove($groupeClasse);
				}
				
				// supprime la relation entre les territoires et le groupe
				foreach ( $groupe->getTerritoires() as $territoire)
				{
					$territoire->setGroupe(null);
					$app['orm.em']->persist($territoire);
				}
				
				// supprime la relation entre un background et le groupe
				foreach ( $groupe->getBackgrounds() as $background)
				{
					$app['orm.em']->remove($background);
				}
				
				$app['orm.em']->remove($groupe);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le groupe a été supprimé.');
				return $app->redirect($app['url_generator']->generate('groupe.admin.list'));
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
