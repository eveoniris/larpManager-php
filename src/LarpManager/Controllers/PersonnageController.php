<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use LarpManager\Form\PersonnageForm;
use LarpManager\Form\PersonnageCompetenceForm;
use LarpManager\Form\PersonnageReligionForm;

/**
 * LarpManager\Controllers\PersonnageController
 *
 * @author kevin
 *
 */
class PersonnageController
{
		
	/**
	 * Affiche le détail d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
				
		$personnage = $app['orm.em']->find('\LarpManager\Entities\Personnage',$id);
		
		if ( $personnage )
		{
			return $app['twig']->render('personnage/detail.twig', array('personnage' => $personnage));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le personnage n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('homepage'));
		}
	}
	
	/**
	 * Ajoute une religion au personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addReligionAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$personnage = $app['orm.em']->find('\LarpManager\Entities\Personnage',$id);
		$personnageReligion = new \LarpManager\Entities\PersonnageReligion();		
		
		$form = $app['form.factory']->createBuilder(new PersonnageReligionForm(), $personnageReligion)
			->add('save','submit', array('label' => 'Valider votre religion'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnageReligion = $form->getData();
			$personnageReligion->setPersonnage($personnage);
			$app['orm.em']->persist($personnageReligion);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		return $app['twig']->render('personnage/religion_add.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
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
		$id = $request->get('index');
		
		$personnage = $app['orm.em']->find('\LarpManager\Entities\Personnage',$id);
		$availableCompetences = $app['personnage.manager']->getAvailableCompetences($personnage);
		
		if ( $availableCompetences->count() == 0 )
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'y a plus de compétence disponible.');
			return $app->redirect($app['url_generator']->generate('personnage.detail',array('index'=>$id)),301);
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
			
			if ( $xp - cout < 0 )
			{
				$app['session']->getFlashBag()->add('error','Vos n\'avez pas suffisement de point d\'expérience pour acquérir cette compétence.');
				return $app->redirect($app['url_generator']->generate('personnage.detail',array('index'=>$personnage->getId()),301));
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
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		return $app['twig']->render('personnage/competence.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'competences' =>  $availableCompetences,
		));
	}
	
	/**
	 * Recherche d'un personnage
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function searchAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new FindPersonnageForm(), array())
			->add('submit','submit', array('label' => 'Rechercher'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
				
			$type = $data['type'];
			$search = $data['search'];
	
			$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Personnage');
				
			$personnages = null;
				
			switch ($type)
			{
				case 'nom' :
					$personnages = $repo->findByName($search);
					break;
				case 'surnom' :
					$personnages = $repo->findByNickname($search);
					break;
				case 'numero' :
					// TODO
					break;
			}
				
			if ( $personnages != null )
			{
				if ( $personnages->count() == 1 )
				{
					$app['session']->getFlashBag()->add('success', 'Le personnage a été trouvé.');
					return $app->redirect($app['url_generator']->generate('personnage.detail', array('index'=> $personnages->first()->getId())));
				}
				else
				{
					$app['session']->getFlashBag()->add('success', 'Il y a plusieurs résultats à votre recherche.');
					return $app['twig']->render('personnage/search_result.twig', array(
							'personnages' => $personnages,
					));
				}
			}
				
			$app['session']->getFlashBag()->add('error', 'Désolé, le personnage n\'a pas été trouvé.');
		}
	
		return $app['twig']->render('joueur/search.twig', array(
				'form' => $form->createView(),
		));
	}
}