<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\ReligionForm;


class ReligionController
{
	/**
	 * affiche la liste des religions
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Religion');
		$religions = $repo->findAllOrderedByLabel();
		
		return $app['twig']->render('religion/index.twig', array('religions' => $religions));
	}
	
	/**
	 * Detail d'une religion
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$religion = $app['orm.em']->find('\LarpManager\Entities\Religion',$id);
		
		return $app['twig']->render('religion/detail.twig', array('religion' => $religion));
	}
	
	/**
	 * Ajoute une religion
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$religion = new \LarpManager\Entities\Religion();
		
		$form = $app['form.factory']->createBuilder(new ReligionForm(), $religion)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);

		// si l'utilisateur soumet une nouvelle religion
		if ( $form->isValid() )
		{
			$religion = $form->getData();
			$app['orm.em']->persist($religion);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La religion a été ajoutée.');
				
			// l'utilisateur est redirigé soit vers la liste des religions, soit vers de nouveau
			// vers le formulaire d'ajout d'une religion
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('religion'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('religion.add'),301);
			}
		}
		
		return $app['twig']->render('religion/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modifie une religion. Si l'utilisateur clique sur "sauvegarder", la religion est sauvegardée et
	 * l'utilisateur est redirigé vers la liste des religions. 
	 * Si l'utilisateur clique sur "supprimer", la religion est supprimée et l'utilisateur est
	 * redirigé vers la liste des religions. 
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{	
		$id = $request->get('index');
		
		$religion = $app['orm.em']->find('\LarpManager\Entities\Religion',$id);
		
		$form = $app['form.factory']->createBuilder(new ReligionForm(), $religion)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$religion = $form->getData();
		
			if ( $form->get('update')->isClicked())
			{
				$app['orm.em']->persist($religion);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La religion a été mise à jour.');
		
				return $app->redirect($app['url_generator']->generate('religion.detail',array('index' => $id)),301);
			}
			else if ( $form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($religion);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La religion a été supprimée.');
				return $app->redirect($app['url_generator']->generate('religion'),301);
			}
		}		

		return $app['twig']->render('religion/update.twig', array(
				'religion' => $religion,
				'form' => $form->createView(),
		));
	}
}