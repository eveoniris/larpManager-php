<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\GroupeSecondaireForm;
use LarpManager\Form\GroupeSecondairePostulerForm;

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
	 * Liste des groupes secondaires
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\SecondaryGroup');
		$groupeSecondaires = $repo->findAll();
	
		if ( $app['security.authorization_checker']->isGranted('ROLE_SCENARISTE') )
		{
			return $app['twig']->render('groupeSecondaire/index.twig', array(
					'groupeSecondaires' => $groupeSecondaires));
		}
		else
		{
			return $app['twig']->render('groupeSecondaire/list_joueur.twig', array(
					'groupeSecondaires' => $groupeSecondaires));
		}
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
			$app['session']->getFlashBag()->add('success', 'Votre candidature a été enregistrée, et transmise au chef de groupe.');
		
			return $app->redirect($app['url_generator']->generate('homepage'));
		}
		
		return $app['twig']->render('groupeSecondaire/postuler.twig', array(
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
	public function addAction(Request $request, Application $app)
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
	
			$app['orm.em']->persist($groupeSecondaire);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Le groupe secondaire a été ajouté.');
	
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupeSecondaire'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupeSecondaire.add'),301);
			}
		}
	
		return $app['twig']->render('groupeSecondaire/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour un de groupe secondaire
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
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
	
			return $app->redirect($app['url_generator']->generate('groupeSecondaire'));
		}
	
		return $app['twig']->render('groupeSecondaire/update.twig', array(
				'groupeSecondaire' => $groupeSecondaire,
				'form' => $form->createView(),
		));
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
			if ( $app['larp.manager']->isResponsableOfSecondaryGroup($app['user'],$groupeSecondaire) )
			{
				return $app['twig']->render('groupeSecondaire/detail_responsable.twig', array('groupeSecondaire' => $groupeSecondaire));
			}
			else if ( $app['larp.manager']->isMemberOfSecondaryGroup($app['user'],$groupeSecondaire) )
			{
				return $app['twig']->render('groupeSecondaire/detail_member.twig', array('groupeSecondaire' => $groupeSecondaire));
			}
			else if ( $app['security.authorization_checker']->isGranted('ROLE_SCENARISTE') )
			{
				return $app['twig']->render('groupeSecondaire/detail.twig', array('groupeSecondaire' => $groupeSecondaire));
			}
			else
			{
				throw new AccessDeniedException();
			}
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le groupe secondaire n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('groupeSecondaire'));
		}
	}
}