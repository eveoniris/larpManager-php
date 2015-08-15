<?php

namespace LarpManager\Controllers;

use LarpManager\Form\Type\GroupeType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

/**
 * Gestion des groupes
 * @author kevin
 */
class GroupeController
{
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
		$groupes = $repo->findAll();
		
		return $app['twig']->render('groupe/index.twig', array(
				'last_add' => $groupes));
	}
	
	public function addAction(Request $request, Application $app)
	{
		$groupe = new \LarpManager\Entities\Groupe();
		
		$form = $app['form.factory']->createBuilder(new GroupeType(), $groupe)
		->add('save','submit', array('label' => 'Sauvegarder et fermer'))
		->add('save_continue','submit',array('label' => 'Sauvegarder et nouveau'))
		->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$groupe = $form->getData();
			
			$app['orm.em']->persist($groupe);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le groupe été sauvegardé');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupe_homepage'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupe_add'),301);
			}
				
		}
		
		return $app['twig']->render('groupe/add.twig', array('form' => $form->createView()));
	}
	
	public function updateAction(Request $request, Application $app)
	{
		
	}
	
	public function detailAction(Request $request, Application $app)
	{
		
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