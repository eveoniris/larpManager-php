<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

use LarpManager\Form\CompetenceForm;

class CompetenceController
{
	/**
	 * Liste des compétences
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Competence');
		$competences = $repo->findAll();
		return $app['twig']->render('competence/index.twig', array('competences' => $competences));
	}
	
	/**
	 * Ajout d'une compétence
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$competence = new \LarpManager\Entities\Competence();
		
		// l'identifiant de la famille de competence peux avoir été passé en paramètre
		// pour initialiser le formulaire avec une valeur par défaut.
		// TODO : dans ce cas, il ne faut proposer que les niveaux pour lesquels une compétence
		// n'a pas été défini pour cette famille
		
		$competenceFamilyId = $request->get('competenceFamily');
		$levelIndex = $request->get('level');
		
		if ( $competenceFamilyId ) 
		{
			$competenceFamily = $app['orm.em']->find('\LarpManager\Entities\CompetenceFamily', $competenceFamilyId);
			if ( $competenceFamily )
			{
				$competence->setCompetenceFamily($competenceFamily);
			}
		}
		
		if ( $levelIndex )
		{
			$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Level');
			$level = $repo->findByIndex($levelIndex+1);
			if ( $level )
			{
				$competence->setLevel($level);
			}
		}
		
		$form = $app['form.factory']->createBuilder(new CompetenceForm(), $competence)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$competence = $form->getData();
				
			$app['orm.em']->persist($competence);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'La compétence a été ajoutée.');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('competence'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('competence.add'),301);
			}
		}
		
		return $app['twig']->render('competence/add.twig', array(
				'form' => $form->createView(),
		));	
	}
	
	/**
	 * Detail d'une compétence
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$competence = $app['orm.em']->find('\LarpManager\Entities\Competence',$id);
		
		if ( $competence )
		{
			return $app['twig']->render('competence/detail.twig', array('competence' => $competence));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'La compétence n\'a pas été trouvée.');
			return $app->redirect($app['url_generator']->generate('competence'));
		}
		
	}
	
	/**
	 * Met à jour une compétence
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$competence = $app['orm.em']->find('\LarpManager\Entities\Competence',$id);
		
		$form = $app['form.factory']->createBuilder(new CompetenceForm(), $competence)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$competence = $form->getData();
				
			if ($form->get('update')->isClicked())
			{	
				$app['orm.em']->persist($competence);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La compétence a été mise à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($competence);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'La compétence a été supprimée.');
			}
		
			return $app->redirect($app['url_generator']->generate('competence'));
		}
		
		return $app['twig']->render('competence/update.twig', array(
				'competence' => $competence,
				'form' => $form->createView(),
		));
	}
}