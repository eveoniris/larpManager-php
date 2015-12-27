<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use JasonGrimes\Paginator;
use LarpManager\Form\GroupeSecondaireForm;
use LarpManager\Form\GroupeSecondairePostulerForm;
use LarpManager\Form\PostulantReponseForm;
use LarpManager\Form\SecondaryGroupFindForm;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\Controllers\GroupeSecondaireController
 *
 * @author kevin
 *
 */
class GroupeSecondaireController
{
	/**
	 * Liste des groupes secondaires (pour les orgas)
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminListAction(Request $request, Application $app)
	{
		$order_by = $request->get('order_by') ?: 'label';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		
		$form = $app['form.factory']->createBuilder(new SecondaryGroupFindForm())
			->add('find','submit', array('label' => 'Rechercher'))
			->getForm();
		
		$criteria = array();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\SecondaryGroup');
		$groupeSecondaires = $repo->findBy(
				$criteria,
				array( $order_by => $order_dir),
				$limit,
				$offset);
		
		$numResults = $repo->findCount($criteria);
		
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('groupeSecondaire.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
		
		return $app['twig']->render('admin/groupeSecondaire/list.twig', array(
				'groupeSecondaires' => $groupeSecondaires,
				'paginator' => $paginator,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Liste des groupes secondaires (pour les joueurs)
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$order_by = $request->get('order_by') ?: 'label';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
	
		$criteria = array();
	
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\SecondaryGroup');
		$groupeSecondaires = $repo->findBy(
				$criteria,
				array( $order_by => $order_dir),
				$limit,
				$offset);
	
		$numResults = $repo->findCount($criteria);
	
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('groupeSecondaire.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
	
		return $app['twig']->render('public/groupeSecondaire/list.twig', array(
				'groupeSecondaires' => $groupeSecondaires,
				'paginator' => $paginator,
		));
	}
	
	/**
	 * Postuler à un groupe secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function postulerAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupeSecondaire = $app['orm.em']->find('\LarpManager\Entities\SecondaryGroup',$id);
		
		/**
		 * Si le joueur est déjà postulant dans ce groupe, refuser d'office la demande
		 */
		$repoPostulant = $app['orm.em']->getRepository('\LarpManager\Entities\Postulant');
		$personnage = $app['user']->getPersonnage();
		
		if ( ! $personnage )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez avoir créer un personnage avant de postuler à un groupe !');
			return $app->redirect($app['url_generator']->generate('homepage'));
		}
		
		foreach (  $personnage->getPostulants() as $postulant )
		{
			if ( $postulant->getSecondaryGroup() == $groupeSecondaire )
			{
				$app['session']->getFlashBag()->add('error', 'Votre avez déjà postulé dans ce groupe. Inutile d\'en refaire la demande.');
				return $app->redirect($app['url_generator']->generate('homepage'));
			}
		}
		
		$form = $app['form.factory']->createBuilder(new GroupeSecondairePostulerForm())
			->add('postuler','submit', array('label' => "Postuler"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$postulant = new \LarpManager\Entities\Postulant();
			$postulant->setPersonnage($app['user']->getPersonnage());
			$postulant->setSecondaryGroup($groupeSecondaire);
			$postulant->setExplanation($data['explanation']);
		
			$app['orm.em']->persist($postulant);
			$app['orm.em']->flush();
			
			
			// envoi d'un mail au chef du groupe secondaire
			if ( $groupeSecondaire->getResponsable() )
			{
				$message = "Nouvelle candidature";
				$message = \Swift_Message::newInstance()
					->setSubject('[LarpManager] Nouvelle candidature')
					->setFrom(array('noreply@eveoniris.com'))
					->setTo(array($groupeSecondaire->getResponsable()->getParticipant()->getUser()->getEmail()))
					->setBody($message);
				 
				$app['mailer']->send($message);
			}
			
			$app['session']->getFlashBag()->add('success', 'Votre candidature a été enregistrée, et transmise au chef de groupe.');
		
			return $app->redirect($app['url_generator']->generate('homepage'));
		}
		
		return $app['twig']->render('public/groupeSecondaire/postuler.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Ajoute un groupe secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddAction(Request $request, Application $app)
	{
		$groupeSecondaire = new \LarpManager\Entities\SecondaryGroup();
	
		$form = $app['form.factory']->createBuilder(new GroupeSecondaireForm(), $groupeSecondaire)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$groupeSecondaire = $form->getData();
	
			/**
			 * Création des topics associés à ce groupe
			 * un topic doit être créé par GN auquel ce groupe est inscrit
			 * @var \LarpManager\Entities\Topic $topic
			 */
			$topic = new \LarpManager\Entities\Topic();
			$topic->setTitle($groupeSecondaire->getLabel());
			$topic->setDescription($groupeSecondaire->getDescription());
			$topic->setUser($app['user']);

			$app['orm.em']->persist($groupeSecondaire);
			$app['orm.em']->flush();
			
			// défini les droits d'accés à ce forum
			// (les membres du groupe ont le droit d'accéder à ce forum)
			$topic->setRight('GROUPE_SECONDAIRE_MEMBER');
			$topic->setObjectId($groupeSecondaire->getId());
			$groupeSecondaire->setTopic($topic);

			/**
			 * Ajoute le responsable du groupe dans le groupe si il n'y est pas déjà
			 */
			$personnage = $groupeSecondaire->getResponsable();
			if ( $personnage )
			{
				$groupeSecondaire->addPersonnage($personnage);
			}
			
			$app['orm.em']->persist($topic);
			$app['orm.em']->persist($groupeSecondaire);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le groupe secondaire a été ajouté.');
	
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupeSecondaire.admin.list'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupeSecondaire.admin.add'),301);
			}
		}
	
		return $app['twig']->render('admin/groupeSecondaire/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour un de groupe secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$groupeSecondaire = $app['orm.em']->find('\LarpManager\Entities\SecondaryGroup',$id);
	
		$form = $app['form.factory']->createBuilder(new GroupeSecondaireForm(), $groupeSecondaire)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$groupeSecondaire = $form->getData();
	
			if ($form->get('update')->isClicked())
			{
				/**
				 * Ajoute le responsable du groupe dans le groupe si il n'y est pas déjà
				 */
				$personnage = $groupeSecondaire->getResponsable();
				if ( ! $groupeSecondaire->getPersonnages()->contains($personnage))
				{
					$groupeSecondaire->addPersonnage($personnage);
				}
				
				$app['orm.em']->persist($groupeSecondaire);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le groupe secondaire a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($groupeSecondaire);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le groupe secondaire a été supprimé.');
			}
	
			return $app->redirect($app['url_generator']->generate('groupeSecondaire.admin.list'));
		}
	
		return $app['twig']->render('admin/groupeSecondaire/update.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Détail d'un groupe secondaire (pour les orgas)
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDetailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		$groupeSecondaire = $app['orm.em']->find('\LarpManager\Entities\SecondaryGroup',$id);
		if ( $groupeSecondaire )
		{
			return $app['twig']->render('admin/groupeSecondaire/detail.twig', array('groupeSecondaire' => $groupeSecondaire));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le groupe secondaire n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('groupeSecondaire'));
		}
	}
	
	/**
	 * Detail d'un type de groupe secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		$groupeSecondaire = $app['orm.em']->find('\LarpManager\Entities\SecondaryGroup',$id);
	
		if ( $groupeSecondaire )
		{
			if ( $app['security.authorization_checker']->isGranted('GROUPE_SECONDAIRE_RESPONSABLE',$groupeSecondaire->getId()) )
			{
				return $app['twig']->render('groupeSecondaire/detail_responsable.twig', array('groupeSecondaire' => $groupeSecondaire));
			}
			if ( $app['security.authorization_checker']->isGranted('GROUPE_SECONDAIRE_MEMBER',$groupeSecondaire->getId()) )
			{
				return $app['twig']->render('groupeSecondaire/detail_member.twig', array('groupeSecondaire' => $groupeSecondaire));
			}
			else
			{
				throw new AccessDeniedException();
			}
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le groupe secondaire n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
	}
	

	/**
	 * Accepter une candidature à un groupe secondaire (orgas)
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminReponseAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		$postulantId = $request->get('postulantId');
	
		$groupeSecondaire = $app['orm.em']->find('\LarpManager\Entities\SecondaryGroup',$id);
		$postulant = $app['orm.em']->find('\LarpManager\Entities\Postulant',$postulantId);
	
		// le candidat doit effectivement candidater à ce groupe
		if ( $postulant->getSecondaryGroup() != $groupeSecondaire )
		{
			$app['session']->getFlashBag()->add('error', 'Le postulant ne demande pas à participer à ce groupe! .');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		$form = $app['form.factory']->createBuilder(new PostulantReponseForm())
			->add('accepter','submit', array('label' => "Accepter"))
			->add('refuser','submit', array('label' => "Refuser"))
			->getForm();

		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			if ($form->get('accepter')->isClicked())
			{	
				$personnage = $postulant->getPersonnage();
				$personnage->addSecondaryGroup($groupeSecondaire);
				
				$app['orm.em']->persist($personnage);
				$app['orm.em']->remove($postulant);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'La candidature a été acceptée.');
				return $app->redirect($app['url_generator']->generate('groupeSecondaire.admin.detail', array('index' => $groupeSecondaire->getId())),301);
			}
			else if ( $form->get('refuser')->isClicked())
			{
				$app['orm.em']->remove($postulant);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La candidature a été refusée.');
				return $app->redirect($app['url_generator']->generate('groupeSecondaire.admin.detail', array('index' => $groupeSecondaire->getId())),301);
			}
			
		}
		
		return $app['twig']->render('admin/groupeSecondaire/reponse.twig', array(
			'groupeSecondaire' => $groupeSecondaire,
			'postulant' => $postulant,
			'form' => $form->createView(),
		));
	}
	
	
	/**
	 * Accepter une candidature à un groupe secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function reponseAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		$postulantId = $request->get('postulantId');
		
		$groupeSecondaire = $app['orm.em']->find('\LarpManager\Entities\SecondaryGroup',$id);
		$postulant = $app['orm.em']->find('\LarpManager\Entities\Postulant',$postulantId);
		
		// le candidat doit effectivement candidater à ce groupe
		return $app['twig']->render('public/groupeSecondaire/reponse.twig', array(
			'groupeSecondaire' => $groupeSecondaire,
			'postulant' => $postulant,
			'form' => $form->createView(),
		));
	}

}