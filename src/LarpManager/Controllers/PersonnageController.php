<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use JasonGrimes\Paginator;
use LarpManager\Form\PersonnageReligionForm;
use LarpManager\Form\PersonnageFindForm;

/**
 * LarpManager\Controllers\PersonnageController
 *
 * @author kevin
 *
 */
class PersonnageController
{
	
	/**
	 * Liste des personnages
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminListAction(Request $request, Application $app)
	{
		$order_by = $request->get('order_by') ?: 'id';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		$criteria = array();
		
		$form = $app['form.factory']->createBuilder(new PersonnageFindForm())
			->add('find','submit', array('label' => 'Rechercher'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$type = $data['type'];
			$value = $data['value'];
			switch ($type){
				case 'nom':
					$criteria[] = "p.nom LIKE '%$value%'";
					break;
			}
			
		}
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Personnage');
		$personnages = $repo->findList(
				$criteria,
				array( 'by' =>  $order_by, 'dir' => $order_dir),
				$limit,
				$offset);
		
		$numResults = $repo->findCount($criteria);
		
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('personnage.admin.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
		
		return $app['twig']->render('admin/personnage/list.twig', array(
				'personnages' => $personnages,
				'paginator' => $paginator,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Affiche le détail d'un personnage (pour les orgas)
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDetailAction(Request $request, Application $app)
	{
		$personnage = $request->attributes->get('personnage');
		return $app['twig']->render('admin/personnage/detail.twig', array('personnage' => $personnage));
	}
	
	/**
	 * Gestion des points d'expérience d'un personnage (pour les orgas)
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminXpAction(Request $request, Application $app)
	{
		$personnage = $request->attributes->get('personnage');
		return $app['twig']->render('admin/personnage/xp.twig', array('personnage' => $personnage));
		
	}
	
	/**
	 * Ajout d'un personage (orga seulement)
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddAction(Request $request, Application $app)
	{
		
	}
	
	/**
	 * Modification d'un personnage (orga seulement)
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateAction(Request $request, Application $app)
	{
		$personnage = $request->attributes->get('personnage');
	}
			
			
		
	/**
	 * Affiche le détail d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$personnage = $request->attributes->get('personnage');
		return $app['twig']->render('personnage/detail.twig', array('personnage' => $personnage));
	}
	
	
	/**
	 * Ajoute une religion au personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addReligionAction(Request $request, Application $app)
	{
		$personnage = $request->attributes->get('personnage');
		
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
		$personnage = $request->attributes->get('personnage');
		
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
	 * Exporte la fiche d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function exportAction(Request $request, Application $app)
	{
		$personnage = $request->attributes->get('personnage');
		
		$app['pdf.manager']->AddPage();
		$app['pdf.manager']->SetFont('Arial','B',16);
		$app['pdf.manager']->Cell(40,10,$personnage->getNom());
		
		header("Content-Type: application/pdf");
		header("Content-Disposition: attachment; filename=".$personnage->getNom()."_".date("Ymd").".pdf");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$output = fopen("php://output", "w");
		fputs($app['pdf.manager']->Output($personnage->getNom()."_".date("Ymd").".pdf",'I'));
		fclose($output);
		exit();
		
	}
}