<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use JasonGrimes\Paginator;
use LarpManager\Form\PersonnageReligionForm;
use LarpManager\Form\PersonnageFindForm;
use LarpManager\Form\PersonnageForm;
use LarpManager\Form\PersonnageXpForm;

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
		$personnage = $request->get('personnage');
		return $app['twig']->render('admin/personnage/detail.twig', array('personnage' => $personnage));
	}
	
	/**
	 * Gestion des points d'expérience d'un personnage (pour les orgas)
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminXpAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$form = $app['form.factory']->createBuilder(new PersonnageXpForm(), array())
				->add('save','submit', array('label' => 'Sauvegarder'))
				->getForm();
		
		$form->handleRequest($request);
					
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			$xp = $personnage->getXp();
				
			$personnage->setXp($xp + $data['xp']);
				
			// historique
			$historique = new \LarpManager\Entities\ExperienceGain();
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setXpGain($data['xp']);
			$historique->setExplanation($data['explanation']);
			$historique->setPersonnage($personnage);
			
			$app['orm.em']->persist($personnage);
			$app['orm.em']->persist($historique);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Les points d\'expériences ont été ajoutés');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);			
		}
		
		return $app['twig']->render('admin/personnage/xp.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Ajout d'un personage (orga seulement)
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddAction(Request $request, Application $app)
	{
		$personnage = new \LarpManager\Entities\Personnage();

		$form = $app['form.factory']->createBuilder(new PersonnageForm(), $personnage)
			->add('classe','entity', array(
					'label' =>  'Classes disponibles',
					'property' => 'label',
					'class' => 'LarpManager\Entities\Classe',
			))
			->add('save','submit', array('label' => 'Sauvegarder'))
			->add('delete','submit', array('label' => 'Supprimer'))
			->getForm();
		
		return $app['twig']->render('admin/personnage/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modification d'un personnage (orga seulement)
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$form = $app['form.factory']->createBuilder(new PersonnageForm(), $personnage)
			->add('classe','entity', array(
					'label' =>  'Classes disponibles',
					'property' => 'label',
					'class' => 'LarpManager\Entities\Classe',
			))
			->add('save','submit', array('label' => 'Sauvegarder'))
			->add('delete','submit', array('label' => 'Supprimer'))			
			->getForm();
		
		return $app['twig']->render('admin/personnage/update.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage
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
		$personnage = $request->get('personnage');
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
		$personnage = $request->get('personnage');
		
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
	 * Retire la dernière compétence acquise par un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function removeCompetenceAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$lastCompetence = $app['personnage.manager']->getLastCompetence($personnage);
		
		if ( ! $lastCompetence ) {
			$app['session']->getFlashBag()->add('error','Désolé, le personnage n\'a pas encore acquis de compétences');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);
		}
		
		$form = $app['form.factory']->createBuilder()
			->add('save','submit', array('label' => 'Retirer la compétence'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			$cout = $app['personnage.manager']->getCompetenceCout($personnage, $lastCompetence);
			$xp = $personnage->getXp();
			
			$personnage->setXp($xp + $cout);
			$personnage->removeCompetence($lastCompetence);
			$lastCompetence->removePersonnage($personnage);
			
			// historique
			$historique = new \LarpManager\Entities\ExperienceGain();
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setXpGain($cout);
			$historique->setExplanation('Suppression de la compétence ' . $lastCompetence->getLabel());
			$historique->setPersonnage($personnage);
				
			$app['orm.em']->persist($lastCompetence);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->persist($historique);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','La compétence a été retirée');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),301);			
		}
		
		return $app['twig']->render('admin/personnage/removeCompetence.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'competence' =>  $lastCompetence,
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
		$personnage = $request->get('personnage');
		
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
			
			if ( $xp - $cout < 0 )
			{
				$app['session']->getFlashBag()->add('error','Vos n\'avez pas suffisement de point d\'expérience pour acquérir cette compétence.');
				return $app->redirect($app['url_generator']->generate('homepage'),301);
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
	
	//JOS - 22/11/2015 - Export de la fiche de personnage en PDF - Phase 1
	/**
	 * Exporte la fiche d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function exportAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		// Define layers
		$lPdfDataLayer = $app['pdf.manager']->AddLayer('Fiche De Personnage');
		$lPdfBackgoundLayer = $app['pdf.manager']->AddLayer('Arrière-plans');
		
		// Open layer pane in PDF viewer
		$app['pdf.manager']->OpenLayerPane();

		$app['pdf.manager']->AddPage();
		

		$app['pdf.manager']->BeginLayer($lPdfBackgoundLayer);
		
		$app['pdf.manager']->Image('http://img01.deviantart.net/8c5b/i/2010/048/7/3/texture_a_by_knald.jpg',0,0,210);
		
		$app['pdf.manager']->EndLayer();

		
		$app['pdf.manager']->BeginLayer($lPdfDataLayer);

		

		$dataInfoGenerales = array(
			array(utf8_decode('Nom'), utf8_decode($personnage->getNom())),
			array(utf8_decode('Surnom'), utf8_decode($personnage->getSurnom())),
			array(utf8_decode('Age'), utf8_decode($personnage->getAge())),
			array(utf8_decode('Genre'), utf8_decode($personnage->getGenre())),
			array(utf8_decode('Classe'), utf8_decode($personnage->getClasse())),
			array(utf8_decode($personnage->getIntrigue()?"Participe aux intrigues":"Ne participe pas aux intrigues"))
		);
		
		$miseEnFormeInfoGenerales = array(
			'default' => array( //row
				'default' => array( //cell
					'SetFont' => array( //function arguments
							'Arial','',10
					)
				)
			),
			'even' => array( //row
				'default' => array( //cell
					'SetFont' => array( //function arguments
						'Arial','B',10
					)
				)					
			)
		);
		
		
		$app['pdf.manager']->SetFont('Arial','B',16);
		$app['pdf.manager']->Cell(10,30,utf8_decode('Informations générales'));
		$app['pdf.manager']->SetXY(10, 35);
		$app['pdf.manager']->SetFont('Arial','',10);
		$app['pdf.manager']->FixtedWidthBasicTable($dataInfoGenerales, 80);
	
		$app['pdf.manager']->SetFont('Arial','B',16);
		$app['pdf.manager']->Cell(100,30,utf8_decode('Informations générales'));
		$app['pdf.manager']->SetXY(100, 35);
		$app['pdf.manager']->SetFont('Arial','',10);
		$app['pdf.manager']->FixtedWidthStyleSheetTable($dataInfoGenerales, $miseEnFormeInfoGenerales, 80);

		$app['pdf.manager']->ImagePngWithAlpha('http://www.eveoniris.com/images/logo.png',0,100,70);
		$app['pdf.manager']->Image('http://www.eveoniris.com/images/logo.png',0,100,70);

		$app['pdf.manager']->ImagePngWithAlpha('http://www.eveoniris.com/images/logo_eve.png',70,100,30);
		$app['pdf.manager']->Image('http://www.eveoniris.com/images/logo_eve.png',70,100,30);
		
		$app['pdf.manager']->EndLayer();
		
		
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