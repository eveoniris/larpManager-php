<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

use LarpManager\Form\CompetenceFamilyForm;

class CompetenceFamilyController
{
	/**
	 * Liste les famille de competence
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\CompetenceFamily');
		$competenceFamilies = $repo->findAllOrderedByLabel();
		return $app['twig']->render('competenceFamily/index.twig', array('competenceFamilies' => $competenceFamilies));
	}
	
	/**
	 * Ajoute une famille de competence
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$competenceFamily = new \LarpManager\Entities\CompetenceFamily();
	
		$form = $app['form.factory']->createBuilder(new CompetenceFamilyForm(), $competenceFamily)
								->add('save','submit', array('label' => "Sauvegarder"))
								->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
								->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$competenceFamily = $form->getData();
	
			$app['orm.em']->persist($competenceFamily);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'La famille de compétence a été ajoutée.');
	
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('competence.family'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('competence.family.add'),301);
			}
		}
	
		return $app['twig']->render('competenceFamily/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour une famille de compétence
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$competenceFamily = $app['orm.em']->find('\LarpManager\Entities\CompetenceFamily',$id);
	
		$form = $app['form.factory']->createBuilder(new CompetenceFamilyForm(), $competenceFamily)
				->add('update','submit', array('label' => "Sauvegarder"))
				->add('delete','submit', array('label' => "Supprimer"))
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$competenceFamily = $form->getData();
	
			if ($form->get('update')->isClicked())
			{
				$app['orm.em']->persist($competenceFamily);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La famille de compétence a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($competenceFamily);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'La famille de compétence a été supprimé.');
			}
	
			return $app->redirect($app['url_generator']->generate('competence.family'));
		}
	
		return $app['twig']->render('competenceFamily/update.twig', array(
				'competenceFamily' => $competenceFamily,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Detail d'une famille de compétence
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$competenceFamily = $app['orm.em']->find('\LarpManager\Entities\CompetenceFamily',$id);
	
		if ( $competenceFamily )
		{
			return $app['twig']->render('competenceFamily/detail.twig', array('competenceFamily' => $competenceFamily));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'La famille de compétence n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('competence.family'));
		}
	}
}