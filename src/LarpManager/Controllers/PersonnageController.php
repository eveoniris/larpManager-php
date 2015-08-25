<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use LarpManager\Form\PersonnageForm;
use LarpManager\Form\PersonnageCompetenceForm;

class PersonnageController
{
	
	/**
	 * Création d'un nouveau personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		// l'utilisateur doit appartenir à un groupe de joueur
		// l'utilisateur ne doit pas déjà posséder un personnage
		// le groupe de joueur doit pouvoir avoir un personnage de plus
		$groupe = $app['user']->getGroupe();
		$joueur = $app['user']->getJoueur();
		
		if ( ! $joueur || ! $groupe )
		{
			$app['session']->getFlashBag()->add('error','Désolé, vous devez faire parti d\'un groupe pour pouvoir créer un personnage.');
			return $app->redirect($app['url_generator']->generate('homepage',array('index'=>$id)),301);
		}
		
		// si le joueur dispose déjà d'un personnage, refuser le personnage
		if ( $joueur->getPersonnage() )
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
		
		$personnage = new \LarpManager\Entities\Personnage();
		
		// j'ajoute içi certain champs du formulaires (les classes)
		// car j'ai besoin des informations du groupes pour les alimenter
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
			$joueur->setPersonnage($personnage);
			
			// ajout des compétences acquises à la création
			foreach ($personnage->getClasse()->getCompetenceFamilyCreations() as $competenceFamily)
			{
				var_dump($competenceFamily->getLabel());
				$firstCompetence = $competenceFamily->getFirstCompetence();
				if ( $firstCompetence )
				{
					var_dump($firstCompetence->getId());
					$personnage->addCompetence($firstCompetence);
				}
			}
				
			$app['orm.em']->persist($personnage);
			$app['orm.em']->persist($joueur);
			$app['orm.em']->flush();
				
				
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.detail',array('index'=>$personnage->getId()),301));
		}
		
		return $app['twig']->render('personnage/add.twig', array(
				'form' => $form->createView(),
				'classes' => array_unique($groupe->getAvailableClasses()),
		));
		
	}
	
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
	 * Ajoute une compétence au personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addCompetenceAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$personnage = $app['orm.em']->find('\LarpManager\Entities\Personnage',$id);
		
		$form = $app['form.factory']->createBuilder(new PersonnageCompetenceForm(), $personnage)
					->add('competence','entity', array(
							'label' =>  'Choisissez une nouvelle compétence',
							'property' => 'label',
							'class' => 'LarpManager\Entities\Competence',
							'mapped' => false,
							'choices' => $app['personnage.manager']->getAvailableCompetences($personnage),
					))
					->add('save','submit', array('label' => 'Valider la compétence'))
					->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			$competence = $form->get('competence');
			
			$cout = $app['personnage.manager']->getCompetenceCout($personnage, $competence);
			$joueur = $personnage->getJoueur();
			$xp = $joueur->getXp();
			
			if ( $xp - cout > 0 )
			{
				$app['session']->getFlashBag()->add('error','Vos n\'avez pas suffisement de point d\'expérience pour acquérir cette compétence.');
				return $app->redirect($app['url_generator']->generate('personnage.detail',array('index'=>$personnage->getId()),301));
			}
			$joueur->setXp($xp - $cout);
			$personnage->addCompetence($competence);
			
			$app['orm.em']->persist($joueur);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.detail',array('index'=>$personnage->getId()),301));
		}
		
		return $app['twig']->render('personnage/competence.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'competences' =>  $app['personnage.manager']->getAvailableCompetences($personnage),
		));
	}
}