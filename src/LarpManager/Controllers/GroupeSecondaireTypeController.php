<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\GroupeSecondaireTypeForm;

/**
 * LarpManager\Controllers\GroupeSecondaireTypeController
 *
 * @author kevin
 *
 */
class GroupeSecondaireTypeController
{
	/**
	 * Liste les types de groupe secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\SecondaryGroupType');
		$groupeSecondaireTypes = $repo->findAll();
		return $app['twig']->render('groupeSecondaireType/index.twig', array('groupeSecondaireTypes' => $groupeSecondaireTypes));
	}
	
	/**
	 * Ajoute un type de groupe secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$groupeSecondaireType = new \LarpManager\Entities\SecondaryGroupType();
		
		$form = $app['form.factory']->createBuilder(new GroupeSecondaireTypeForm(), $groupeSecondaireType)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$groupeSecondaireType = $form->getData();
		
			$app['orm.em']->persist($groupeSecondaireType);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'Le type de groupe secondaire a été ajouté.');
		
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupeSecondaire.type'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupeSecondaire.type.add'),301);
			}
		}
		
		return $app['twig']->render('groupeSecondaireType/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour un type de groupe secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupeSecondaireType = $app['orm.em']->find('\LarpManager\Entities\SecondaryGroupType',$id);
		
		$form = $app['form.factory']->createBuilder(new GroupeSecondaireTypeForm(), $groupeSecondaireType)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$groupeSecondaireType = $form->getData();
		
			if ($form->get('update')->isClicked())
			{
				$app['orm.em']->persist($groupeSecondaireType);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le type de groupe secondaire a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($groupeSecondaireType);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le type de groupe secondaire a été supprimé.');
			}
		
			return $app->redirect($app['url_generator']->generate('groupeSecondaire.type'));
		}
				
		return $app['twig']->render('groupeSecondaireType/update.twig', array(
				'groupeSecondaireType' => $groupeSecondaireType,
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
		
		$groupeSecondaireType = $app['orm.em']->find('\LarpManager\Entities\SecondaryGroupType',$id);
		
		if ( $groupeSecondaireType )
		{
			return $app['twig']->render('groupeSecondaireType/detail.twig', array('groupeSecondaireType' => $groupeSecondaireType));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le type de groupe secondaire n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('groupeSecondaire.type'));
		}
	}
	
}