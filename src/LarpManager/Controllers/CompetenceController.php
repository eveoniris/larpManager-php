<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

use LarpManager\Form\CompetenceMinimalForm;
use LarpManager\Form\CompetenceForm;
use LarpManager\Form\CompetenceNiveauForm;

class CompetenceController
{
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Competence');
		$competences = $repo->findAll();
		return $app['twig']->render('competence/index.twig', array('competences' => $competences));
	}
	
	public function addAction(Request $request, Application $app)
	{
		$competence = new \LarpManager\Entities\Competence();
		
		$form = $app['form.factory']->createBuilder(new CompetenceMinimalForm(), $competence)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$competence = $form->getData();
			$competence->setCreator($app['user']);
				
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
				$competence->setUpdateDate(new \DateTime('NOW'));
		
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
	
	/**
	 * Ajoute un niveau à une compétence
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function niveauAddAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$competence = $app['orm.em']->find('\LarpManager\Entities\Competence',$id);
		$competenceNiveau = new \LarpManager\Entities\CompetenceNiveau();
		$competenceNiveau->setCompetence($competence);
		
		$form = $app['form.factory']->createBuilder(new CompetenceNiveauForm(), $competenceNiveau)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$competenceNiveau = $form->getData();
		
			$app['orm.em']->persist($competenceNiveau);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'Le niveau a été ajoutée.');
		
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('competence.update', array('index' => $id) ),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('competence.niveau.add', array('index' => $id)),301);
			}
		}
		
		return $app['twig']->render('competence/niveau/add.twig', array(
				'competence' => $competence,
				'form' => $form->createView(),
		));		
	}
	
	/**
	 * Met à jour un niveau
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function niveauUpdateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$competenceNiveau = $app['orm.em']->find('\LarpManager\Entities\CompetenceNiveau',$id);

		$form = $app['form.factory']->createBuilder(new CompetenceNiveauForm(), $competenceNiveau)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$competenceNiveau = $form->getData();
	
			if ($form->get('update')->isClicked())
			{
				$app['orm.em']->persist($competenceNiveau);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Le niveau a été modifier.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($competenceNiveau);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Le niveau a été modifier.');
			}
			
			return $app->redirect($app['url_generator']->generate('competence.update', array('index' => $competenceNiveau->getCompetence()->getId()) ),301);
		}
	
		return $app['twig']->render('competence/niveau/update.twig', array(
				'competenceNiveau' => $competenceNiveau,
				'form' => $form->createView(),
		));
	}
	
	public function exportAction(Request $request, Application $app)
	{
	
	}
}