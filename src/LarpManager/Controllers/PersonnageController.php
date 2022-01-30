<?php

/**
 * LarpManager - A Live Action Role Playing Manager
 * Copyright (C) 2016 Kevin Polez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
namespace LarpManager\Controllers;

use LarpManager\Repository\PersonnageRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

use Silex\Application;
use JasonGrimes\Paginator;
use LarpManager\Entities\Personnage;
use LarpManager\Entities\PersonnageRessource;
use LarpManager\Entities\PersonnageIngredient;
use LarpManager\Entities\PersonnageHasToken;

use LarpManager\Form\Personnage\PersonnageIngredientForm;
use LarpManager\Form\Personnage\PersonnageRessourceForm;
use LarpManager\Form\Personnage\PersonnageRichesseForm;
use LarpManager\Form\Personnage\PersonnageDocumentForm;
use LarpManager\Form\Personnage\PersonnageItemForm;
use LarpManager\Form\Personnage\PersonnageOriginForm;
use LarpManager\Form\Personnage\PersonnageReligionForm;
use LarpManager\Form\Personnage\PersonnageUpdateRenommeForm;
use LarpManager\Form\Personnage\PersonnageUpdateHeroismeForm;
use LarpManager\Form\Personnage\PersonnageUpdatePugilatForm;
use LarpManager\Form\Personnage\PersonnageTechnologieForm;
use LarpManager\Form\Personnage\PersonnageChronologieForm;
use LarpManager\Form\Personnage\PersonnageLigneeForm;

use LarpManager\Form\PersonnageFindForm;
use LarpManager\Form\PersonnageForm;
use LarpManager\Form\TriggerForm;
use LarpManager\Form\TriggerDeleteForm;
use LarpManager\Form\PersonnageUpdateForm;
use LarpManager\Form\PersonnageTransfertForm;
use LarpManager\Form\PersonnageUpdateSortForm;
use LarpManager\Form\PersonnageUpdatePotionForm;
use LarpManager\Form\PersonnageUpdateDomaineForm;
use LarpManager\Form\PersonnageUpdateLangueForm;
use LarpManager\Form\PersonnageUpdatePriereForm;
use LarpManager\Form\PersonnageUpdateAgeForm;
use LarpManager\Form\PersonnageBackgroundForm;
use LarpManager\Form\PersonnageStatutForm;
use LarpManager\Form\PersonnageDeleteForm;
use LarpManager\Form\PersonnageXpForm;
use LarpManager\Form\TrombineForm;
use LarpManager\Repository\LikeExpression;
use LarpManager\Repository\EqualExpression;


/**
 * LarpManager\Controllers\PersonnageController
 *
 * @author kevin
 *
 */
class PersonnageController
{
	
	/**
	 * Selection du personnage courant
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Personnage $personnage
	 */
	public function selectAction(Request $request, Application $app, Personnage $personnage)
	{
		$app['personnage.manager']->setCurrentPersonnage($personnage->getId());		
		return $app->redirect($app['url_generator']->generate('homepage'),303);
	}
	
	/**
	 * Dé-Selection du personnage courant
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Personnage $personnage
	 */
	public function unselectAction(Request $request, Application $app)
	{
		$app['personnage.manager']->resetCurrentPersonnage();
		return $app->redirect($app['url_generator']->generate('homepage'),303);
	}
		
	/**
	 * Obtenir une image protégée
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function getTrombineAction(Request $request, Application $app, Personnage $personnage)
	{
		$trombine = $personnage->getTrombineUrl();
		$filename = __DIR__.'/../../../private/img/'.$trombine;
	
		$stream = function () use ($filename) {
			readfile($filename);
		};
		return $app->stream($stream, 200, array(
				'Content-Type' => 'image/jpeg',
				'cache-control' => 'private'
		));
	}
	
	/**
	 * Mise à jour de la photo
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Personnage $personnage
	 */
	public function updateTrombineAction(Request $request, Application $app, Personnage $personnage)
	{
		$form = $app['form.factory']->createBuilder(new TrombineForm(), array())
			->add('envoyer','submit', array('label' => 'Envoyer'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$files = $request->files->get($form->getName());
		
			$path = __DIR__.'/../../../private/img/';
			$filename = $files['trombine']->getClientOriginalName();
			$extension = $files['trombine']->guessExtension();
		
			if (!$extension || ! in_array($extension, array('png', 'jpg', 'jpeg','bmp'))) {
				$app['session']->getFlashBag()->add('error','Désolé, votre image ne semble pas valide (vérifiez le format de votre image)');
				return $app->redirect($app['url_generator']->generate('personnage.admin.detail', array('personnage' => $personnage->getId())),303);
			}
		
			$trombineFilename = hash('md5',$app['user']->getUsername().$filename . time()).'.'.$extension;
		
			$image = $app['imagine']->open($files['trombine']->getPathname());
			$image->resize($image->getSize()->widen( 160 ));
			$image->save($path. $trombineFilename);
		
			$personnage->setTrombineUrl($trombineFilename);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','La photo a été enregistrée');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail', array('personnage' => $personnage->getId())),303);
		}
		return $app['twig']->render('admin/personnage/trombine.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView()
		));
	}
	
	/**
	 * Création d'un nouveau personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function newAction(Request $request, Application $app)
	{
		$personnage = new Personnage();
				
		$form = $app['form.factory']->createBuilder(new PersonnageForm(), $personnage)
			->add('valider','submit', array('label' => 'Enregistrer'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			$app['user']->addPersonnage($personnage);
			$app['orm.em']->persist($app['user']);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['personnage.manager']->setCurrentPersonnage($personnage);
			$app['session']->getFlashBag()->add('success', 'Votre personnage a été créé');
			return $app->redirect($app['url_generator']->generate('homepage'),303);
		}
		
		return $app['twig']->render('public/personnage/new.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Page d'accueil de gestion des personnage
	 * @param Request $request
	 * @param Application $app
	 */
	public function accueilAction(Request $request, Application $app)
	{
		return $app['twig']->render('public/personnage/accueil.twig', array());
	}
	
	/**
	 * Permet de faire vieillir les personnages
	 * Cela va donner un Jeton Vieillesse à tous les personnages et changer la catégorie d'age des personnages cumulants deux jetons vieillesse
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function vieillirAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder()
			->add('valider','submit', array('label' => 'Faire vieillir tous les personnages', 'attr' => array('class' => 'btn-danger')))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnages = $app['orm.em']->getRepository('\LarpManager\Entities\Personnage')->findAll();
			$token = $app['orm.em']->getRepository('\LarpManager\Entities\Token')->findOneByTag('VIEILLESSE');
			$ages = $app['orm.em']->getRepository('\LarpManager\Entities\Age')->findAll();
			
			if ( ! $token )
			{
				$app['session']->getFlashBag()->add('error', 'Le jeton VIEILLESSE n\'existe pas !');
				return $app->redirect($app['url_generator']->generate('homepage'),303);
			}
			
			foreach ( $personnages as $personnage)
			{
				// donne un jeton vieillesse
				$personnageHasToken = new \LarpManager\Entities\PersonnageHasToken();
				$personnageHasToken->setToken($token);
				$personnageHasToken->setPersonnage($personnage);
				$personnage->addPersonnageHasToken($personnageHasToken);
				$app['orm.em']->persist($personnageHasToken);
				
				$personnage->setAgeReel($personnage->getAgeReel() + 5); // ajoute 5 ans à l'age réél
				
				if ( $personnage->getPersonnageHasTokens()->count() % 2 == 0 )
				{
					if ( $personnage->getAge()->getId() != 5 )
					{
						$personnage->setAge($ages[$personnage->getAge()->getId() +1]);
					}
					else
					{
						$personnage->setVivant(false);
						foreach ($personnage->getParticipants() as $participant)
						{
							if ($participant->getGn() != null) {
								$anneeGN = $participant->getGn()->getDateJeu() + rand(1, 4);
							}
						}
						$evenement = 'Mort de vieillesse';
						$personnageChronologie = new \LarpManager\Entities\PersonnageChronologie();
						$personnageChronologie->setAnnee($anneeGN);
						$personnageChronologie->setEvenement($evenement);
						$personnageChronologie->setPersonnage($personnage);
						$app['orm.em']->persist($personnageChronologie);
					}
				}
				
				$app['orm.em']->persist($personnage);
			}
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'tous les personnages ont reçut un jeton vieillesse.');
			return $app->redirect($app['url_generator']->generate('homepage'),303);
		}
		
		return $app['twig']->render('admin/personnage/vieillir.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modifier l'age d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Personnage $personnage
	 */
	public function adminUpdateAgeAction(Request $request, Application $app, Personnage $personnage)
	{
		$form = $app['form.factory']->createBuilder(new PersonnageUpdateAgeForm(), $personnage)
			->add('valider','submit', array('label' => 'Valider'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/age.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modification des technologies d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Personnage $personnage
	 */
	public function adminTechnologieAction(Request $request, Application $app, Personnage $personnage)
	{
		$form = $app['form.factory']->createBuilder(new PersonnageTechnologieForm(), $personnage)
			->add('valider','submit', array('label' => 'Valider'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
				
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/updateTechnologie.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}

	public function getLangueMateriel(Personnage $personnage)
	{
		$langueMateriel = array();
		foreach($personnage->getPersonnageLangues() as $langue) {
			if(!in_array('Bracelet '.$langue->getLangue()->getGroupeLangue()->getCouleur(),$langueMateriel)) {
				if ($langue->getLangue()->getGroupeLangue()->getId() != 0 && $langue->getLangue()->getGroupeLangue()->getId() != 6){
					array_push($langueMateriel, 'Bracelet '.$langue->getLangue()->getGroupeLangue()->getCouleur());
				}
			}
			if($langue->getLangue()->getDiffusion() === 0) {
				array_push($langueMateriel, 'Alphabet '.$langue->getLangue()->getLabel());
			}
		}
		sort($langueMateriel);
		return $langueMateriel;
	}
	
	public function enveloppePrintAction(Request $request, Application $app, Personnage $personnage)
	{
		return $app['twig']->render('admin/personnage/enveloppe.twig', array(
				'personnage' => $personnage,
				'langueMateriel' => $this->getLangueMateriel($personnage),
		));
	}
	
	/**
	 * Modifie le matériel lié à un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminMaterielAction(Request $request, Application $app, Personnage $personnage)
	{
		$form = $app['form.factory']->createBuilder()
			->add('materiel','textarea', array(
					'required' => false,
					'data' => $personnage->getMateriel(),
			))
			->add('valider','submit', array('label' => 'Valider'))
			->getForm();

		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$personnage->setMateriel($data['materiel']);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/materiel.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modification du statut d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminStatutAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$form = $app['form.factory']->createBuilder(new PersonnageStatutForm(), $personnage)
			->add('submit','submit', array('label' => 'Valider'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			if ($personnage->getVivant() == false){
				$evenement = 'Mort violente';
			}
			else {
				$evenement = 'Résurrection';
			}
			// TODO: Trouver comment avoir la date du GN
			/*
			$personnageChronologie = new \LarpManager\Entities\PersonnageChronologie();
			$personnageChronologie->setAnnee($anneeGN);
			$personnageChronologie->setEvenement($evenement);
			$personnageChronologie->setPersonnage($personnage);
			$app['orm.em']->persist($personnageChronologie);
			*/

			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le statut du personnage a été modifié');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/statut.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Transfert d'un personnage à un autre utilisateur
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminTransfertAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$form = $app['form.factory']->createBuilder()
			->add('participant','entity', array(
					'required' => true,
					'expanded' => true,
					'label' => 'Nouveau propriétaire',
					'class' => 'LarpManager\Entities\Participant',
					'property' => 'userIdentity',
			))
			->add('transfert','submit', array('label' => 'Transferer'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$participant = $data['participant'];
			
			$personnage->setUser($participant->getUser());
						
			// gestion de l'ancien personnage
			if ( $participant->getPersonnage() )
			{
				$oldPersonnage = $participant->getPersonnage();
				$oldPersonnage->removeParticipant($participant);
				$oldPersonnage->setGroupeNull();
			}
			
			// le personnage doit rejoindre le groupe de l'utilisateur
			if ( $participant->getGroupeGn())
			{
				if ( $participant->getGroupeGn()->getGroupe() )
				{
					$personnage->setGroupe($participant->getGroupeGn()->getGroupe());
				}
			}
				
			$participant->setPersonnage($personnage);
			$personnage->addParticipant($participant);
						
			$app['orm.em']->persist($participant);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été transféré');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/transfert.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}
    private function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
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

		$formData = $request->query->get('personnageFind');
        $religion = isset($formData['religion'])?$app['orm.em']->find('LarpManager\Entities\Religion',$formData['religion']):null;
        $competence = isset($formData['competence'])?$app['orm.em']->find('LarpManager\Entities\Competence',$formData['competence']):null;
        $classe = isset($formData['classe'])?$app['orm.em']->find('LarpManager\Entities\Classe',$formData['classe']):null;
        $optionalParameters = "";

		$form = $app['form.factory']->createBuilder(
		    new PersonnageFindForm(),
            null,
            array(
                'data' => [
                    'religion' => $religion,
                    'classe' => $classe,
                    'competence' => $competence,
                ],
                'method' => 'get',
                'csrf_protection' => false
            )
        )->getForm();

		$form->handleRequest($request);

		if ( $form->isValid() )
		{
			$data = $form->getData();
			$type = $data['type'];
			$value = $data['value'];
			switch ($type){
				case 'nom':
				    // $criteria[] = new LikeExpression("p.nom", "%$value%");
				    $criteria["nom"] = "LOWER(p.nom) LIKE '%".preg_replace('/[\'"<>=*;]/', '', strtolower($value))."%' OR LOWER(p.surnom) LIKE '%".preg_replace('/[\'"<>=*;]/', '', strtolower($value))."%'";
					break;
				case 'id':
				    // $criteria[] = new EqualExpression("p.id", $value);
				    $criteria["id"] = "p.id = ".preg_replace('/[^\d]/', '', $value);
					break;								
			}
		}
        if($religion){
            $criteria["religion"] = "pr.id = {$religion->getId()}";
            $optionalParameters .= "&personnageFind[religion]={$religion->getId()}";
        }
        if($competence){
            $criteria["competence"] = "cmp.id = {$competence->getId()}";
            $optionalParameters .= "&personnageFind[competence]={$competence->getId()}";
        }
        if($classe){
            $criteria["classe"] = "cl.id = {$classe->getId()}";
            $optionalParameters .= "&personnageFind[classe]={$classe->getId()}";
        }

        /* @var PersonnageRepository $repo */
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Personnage');
		$personnages = $repo->findList(
            $criteria,
            array( 'by' =>  $order_by, 'dir' => $order_dir),
            $limit,
            $offset
        );
		
		$numResults = $repo->findCount($criteria);
		
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('personnage.admin.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir . $optionalParameters
				);
		
		return $app['twig']->render('admin/personnage/list.twig', array(
            'personnages' => $personnages,
            'paginator' => $paginator,
            'form' => $form->createView(),
            'optionalParameters' => $optionalParameters
		));
	}
	
	/**
	 * Imprimmer la liste des personnages
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminPrintAction(Request $request, Application $app)
	{
		
	}
	
	/**
	 * Télécharger la liste des personnages au format CSV
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDownloadAction(Request $request, Application $app)
	{
		
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
		$personnageLangues = $personnage->getPersonnageLangues();

		return $app['twig']->render('admin/personnage/detail.twig', array('personnage' => $personnage,'langueMateriel' => $this->getLangueMateriel($personnage)));
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
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);			
		}
		
		return $app['twig']->render('admin/personnage/xp.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Ajout d'un personnage (orga seulement)
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddAction(Request $request, Application $app)
	{
		$personnage = new \LarpManager\Entities\Personnage();
		
		$participant = $request->get('participant');
		if ( !$participant ) {
			$participant = $app['user']->getParticipant();
		}
		else {
			$participant = $app['orm.em']->getRepository('\LarpManager\Entities\Participant')->find($participant);
		}
		
		
		$form = $app['form.factory']->createBuilder(new PersonnageForm(), $personnage)
			->add('classe','entity', array(
					'label' =>  'Classes disponibles',
					'property' => 'label',
					'class' => 'LarpManager\Entities\Classe',
			))
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			$participant->setPersonnage($personnage);
			
			if ( $participant->getGroupe())
			{
				$personnage->setGroupe($participant->getGroupe());
			}
			
			$personnage->setXp($app['larp.manager']->getGnActif()->getXpCreation());
				
			// historique
			$historique = new \LarpManager\Entities\ExperienceGain();
			$historique->setExplanation("Création de votre personnage");
			$historique->setOperationDate(new \Datetime('NOW'));
			$historique->setPersonnage($personnage);
			$historique->setXpGain($app['larp.manager']->getGnActif()->getXpCreation());
			$app['orm.em']->persist($historique);
			
			// ajout des compétences acquises à la création
			foreach ($personnage->getClasse()->getCompetenceFamilyCreations() as $competenceFamily)
			{
				$firstCompetence = $competenceFamily->getFirstCompetence();
				if ( $firstCompetence )
				{
					$personnage->addCompetence($firstCompetence);
					$firstCompetence->addPersonnage($personnage);
					$app['orm.em']->persist($firstCompetence);
				}
			}

			// Ajout des points d'expérience gagné grace à l'age
			$xpAgeBonus = $personnage->getAge()->getBonus();
			if ( $xpAgeBonus )
			{
				$personnage->addXp($xpAgeBonus);
				$historique = new \LarpManager\Entities\ExperienceGain();
				$historique->setExplanation("Bonus lié à l'age");
				$historique->setOperationDate(new \Datetime('NOW'));
				$historique->setPersonnage($personnage);
				$historique->setXpGain($xpAgeBonus);
				$app['orm.em']->persist($historique);
			}
			
			
			$app['orm.em']->persist($personnage);
			$app['orm.em']->persist($participant);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Votre personnage a été sauvegardé.');
			if ( $participant->getGroupe())
			{
				return $app->redirect($app['url_generator']->generate('groupe.detail', array('index' => $participant->getGroupe()->getId())),303);
			}
			else
			{
				return $app->redirect($app['url_generator']->generate('homepage'),303);
			}
		}
		
		return $app['twig']->render('admin/personnage/add.twig', array(
				'form' => $form->createView(),
				'participant' => $participant,
		));
	}
			
	/**
	 * Supression d'un personnage (orga seulement)
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDeleteAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$form = $app['form.factory']->createBuilder(new PersonnageDeleteForm(), $personnage)
			->add('delete','submit', array('label' => 'Supprimer'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$personnage = $form->getData();

			foreach ($personnage->getExperienceGains() as $xp)
			{
				$personnage->removeExperienceGain($xp);
				$app['orm.em']->remove($xp);
			}
			
			foreach ($personnage->getExperienceUsages() as $xp)
			{
				$personnage->removeExperienceUsage($xp);
				$app['orm.em']->remove($xp);
			}
			
			foreach ($personnage->getMembres() as $membre)
			{
				$personnage->removeMembre($membre);
				$app['orm.em']->remove($membre);
			}
			
			foreach ( $personnage->getPersonnagesReligions() as $personnagesReligions)
			{
				$personnage->removePersonnagesReligions($personnagesReligions);
				$app['orm.em']->remove($personnagesReligions);
			}
			
			foreach ($personnage->getPostulants() as $postulant)
			{
				$personnage->removePostulant($postulant);
				$app['orm.em']->remove($postulant);
			}
			
			foreach ($personnage->getPersonnageLangues() as $personnageLangue)
			{
				$personnage->removePersonnageLangues($personnageLangue);
				$app['orm.em']->remove($personnageLangue);
			}
			
			foreach ($personnage->getPersonnageTriggers() as $trigger)
			{
				$personnage->removePersonnageTrigger($trigger);
				$app['orm.em']->remove($trigger);
			}
			
			foreach ($personnage->getPersonnageBackgrounds() as $background)
			{
				$personnage->removePersonnageBackground($background);
				$app['orm.em']->remove($background);
			}
			
			foreach ($personnage->getPersonnageHasTokens() as $token)
			{
				$personnage->removePersonnageHasToken($token);
				$app['orm.em']->remove($token);
			}
			
			foreach ($personnage->getParticipants() as $participant)
			{
				$participant->setPersonnage();
				$app['orm.em']->persist($participant);
			}
						
			$app['orm.em']->remove($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été supprimé.');
			return $app->redirect($app['url_generator']->generate('homepage'),303);
		}
		
		return $app['twig']->render('admin/personnage/delete.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage
		));
	}
	
	/**
	 * Modification du personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
				
		$form = $app['form.factory']->createBuilder(new PersonnageUpdateForm(), $personnage)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/update.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage));
	}
	
	/**
	 * Ajoute un background au personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddBackgroundAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$background = new \LarpManager\Entities\PersonnageBackground();
		
		$background->setPersonnage($personnage);
		$background->setUser($app['user']);
		
		$form = $app['form.factory']->createBuilder(new PersonnageBackgroundForm(), $background)
			->add('visibility','choice', array(
					'required' => true,
					'label' =>  'Visibilité',
					'choices' => $app['larp.manager']->getPersonnageBackgroundVisibility(),
			))
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();

		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$background = $form->getData();
		
			$app['orm.em']->persist($background);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le background a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/addBackground.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'background' => $background,
		));
	}
		
	/**
	 * Modifie le background d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateBackgroundAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$background = $request->get('background');

		$form = $app['form.factory']->createBuilder(new PersonnageBackgroundForm(), $background)
			->add('visibility','choice', array(
					'required' => true,
					'label' =>  'Visibilité',
					'choices' => $app['larp.manager']->getPersonnageBackgroundVisibility(),
			))
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$background = $form->getData();
		
			$app['orm.em']->persist($background);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le background a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/updateBackground.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'background' => $background,
		));
	}
	
	/**
	 * Modification de la renommee du personnage
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateRenommeAction(Request $request, Application $app, Personnage $personnage)
	{	
		$form = $app['form.factory']->createBuilder(new PersonnageUpdateRenommeForm())
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$renomme = $form->get('renomme')->getData();
			$explication = $form->get('explication')->getData();
			
			$renomme_history = new \LarpManager\Entities\RenommeHistory();
			
			$renomme_history->setRenomme($renomme);
			$renomme_history->setExplication($explication);
			$renomme_history->setPersonnage($personnage);
			$personnage->addRenomme($renomme);
				
			$app['orm.em']->persist($renomme_history);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/updateRenomme.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage));
	}
	
	/**
	 * Modification de l'héroisme d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Personnage $personnage
	 */
	public function adminUpdateHeroismeAction(Request $request, Application $app, Personnage $personnage)
	{
		$form = $app['form.factory']->createBuilder(new PersonnageUpdateHeroismeForm())
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$heroisme = $form->get('heroisme')->getData();
			$explication = $form->get('explication')->getData();
				
			$heroisme_history = new \LarpManager\Entities\HeroismeHistory();
				
			$heroisme_history->setHeroisme($heroisme);
			$heroisme_history->setExplication($explication);
			$heroisme_history->setPersonnage($personnage);
			$personnage->addHeroisme($heroisme);
		
			$app['orm.em']->persist($heroisme_history);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/updateHeroisme.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage));
	}
	
	
	/**
	 * Modification du pugilat d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Personnage $personnage
	 */
	public function adminUpdatePugilatAction(Request $request, Application $app, Personnage $personnage)
	{
		$form = $app['form.factory']->createBuilder(new PersonnageUpdatePugilatForm())
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$pugilat = $form->get('pugilat')->getData();
			$explication = $form->get('explication')->getData();
				
			$pugilat_history = new \LarpManager\Entities\PugilatHistory();
				
			$pugilat_history->setPugilat($pugilat);
			$pugilat_history->setExplication($explication);
			$pugilat_history->setPersonnage($personnage);
			$personnage->addPugilat($pugilat);
		
			$app['orm.em']->persist($pugilat_history);
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/updatePugilat.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage));
	}
	
	/**
	 * Ajoute un jeton vieillesse au personnage
	 */
	public function adminTokenAddAction(Request $request, Application $app, Personnage $personnage)
	{
		$token = $request->get('token');
		$token = $app['orm.em']->getRepository('\LarpManager\Entities\Token')->findOneByTag($token);

		// donne un jeton vieillesse
		$personnageHasToken = new \LarpManager\Entities\PersonnageHasToken();
		$personnageHasToken->setToken($token);
		$personnageHasToken->setPersonnage($personnage);
		$personnage->addPersonnageHasToken($personnageHasToken);
		$app['orm.em']->persist($personnageHasToken);
		
		$personnage->setAgeReel($personnage->getAgeReel() + 5); // ajoute 5 ans à l'age réél
		
		if ( $personnage->getPersonnageHasTokens()->count() % 2 == 0 )
		{
			if ( $personnage->getAge()->getId() != 5 )
			{
				$age = $app['orm.em']->getRepository('\LarpManager\Entities\Age')->findOneById($personnage->getAge()->getId() + 1);
				$personnage->setAge($age);
			}
			else
			{
				$personnage->setVivant(false);
				foreach ($personnage->getParticipants() as $participant)
				{
					if ($participant->getGn() != null) {
						$anneeGN = $participant->getGn()->getDateJeu() + rand(1, 4);
					}
				}
				$evenement = 'Mort de vieillesse';
				$personnageChronologie = new \LarpManager\Entities\PersonnageChronologie();
				$personnageChronologie->setAnnee($anneeGN);
				$personnageChronologie->setEvenement($evenement);
				$personnageChronologie->setPersonnage($personnage);
				$app['orm.em']->persist($personnageChronologie);
			}
		}
		$app['orm.em']->persist($personnage);
		$app['orm.em']->flush();	
		$app['session']->getFlashBag()->add('success','Le jeton '.$token->getTag().' a été ajouté.');
		return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
	}
	
	/**
	 * Retire un jeton d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Personnage $personnage
	 * @param PersonnageHasToken $personnageToken
	 */
	public function adminTokenDeleteAction(Request $request, Application $app, Personnage $personnage, PersonnageHasToken $personnageHasToken)
	{
		$personnage->removePersonnageHasToken($personnageHasToken);
		// $personnage->setAgeReel($personnage->getAgeReel() - 5);
		if ( $personnage->getPersonnageHasTokens()->count() % 2 != 0 )
		{
			if ( $personnage->getAge()->getId() != 5 )
			{
				$age = $app['orm.em']->getRepository('\LarpManager\Entities\Age')->findOneById($personnage->getAge()->getId() - 1);
				$personnage->setAge($age);
			}
		}
		$app['orm.em']->remove($personnageHasToken);
		$app['orm.em']->persist($personnage);

		// Chronologie : Fruits & Légumes
		foreach ($personnage->getParticipants() as $participant)
		{
			if ($participant->getGn() != null) {
				$anneeGN = $participant->getGn()->getDateJeu() +1;
			}
		}
		$evenement = 'Consommation de Fruits & Légumes';
		$personnageChronologie = new \LarpManager\Entities\PersonnageChronologie();
		$personnageChronologie->setAnnee($anneeGN);
		$personnageChronologie->setEvenement($evenement);
		$personnageChronologie->setPersonnage($personnage);
		$app['orm.em']->persist($personnageChronologie);

		$app['orm.em']->flush();

		$app['session']->getFlashBag()->add('success','Le jeton a été retiré.');
		return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
	}
	
	/**
	 * Ajoute un trigger 
	 */
	public function adminTriggerAddAction(Request $request, Application $app, Personnage $personnage)
	{		
		$trigger = new \LarpManager\Entities\PersonnageTrigger();
		$trigger->setPersonnage($personnage);
		$trigger->setDone(false);
		
		$form = $app['form.factory']->createBuilder(new TriggerForm(), $trigger)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$trigger = $form->getData();
		
			$app['orm.em']->persist($trigger);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le déclencheur a été ajouté.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/addTrigger.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage));
	}
		
	/**
	 * Supprime un trigger
	 */
	public function adminTriggerDeleteAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$trigger = $request->get('trigger');
		
		$form = $app['form.factory']->createBuilder(new TriggerDeleteForm(), $trigger)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$trigger = $form->getData();
		
			$app['orm.em']->remove($trigger);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le déclencheur a été supprimé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/deleteTrigger.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'trigger' => $trigger,
		));
	}		
	
	/**
	 * Modifie la liste des domaines de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateDomaineAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$domaines = $app['orm.em']->getRepository('LarpManager\Entities\Domaine')->findAll();
		
		$originalDomaines = new ArrayCollection();
		foreach ( $personnage->getDomaines() as $domaine)
		{
			$originalDomaines[] = $domaine;
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageUpdateDomaineForm(), $personnage)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
			
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			foreach($personnage->getDomaines() as $domaine)
			{
				if ( ! $originalDomaines->contains($domaine))
				{
					$domaine->addPersonnage($personnage);
				}
			}
			
			foreach ( $originalDomaines as $domaine)
			{
				if ( ! $personnage->getDomaines()->contains($domaine))
				{
					$domaine->removePersonnage($personnage);
				}
			}
	
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/updateDomaine.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
		
	}
	
	/**
	 * Modifie la liste des langues
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateLangueAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
				
		$langues = $app['orm.em']->getRepository('LarpManager\Entities\Langue')->findBy(array(), array('secret' => 'ASC', 'diffusion' => 'DESC', 'label' => 'ASC'));
			
		$originalLanguages = array();
		foreach ( $personnage->getLanguages() as $languages)
		{
			$originalLanguages[] = $languages;
		}
		
		$form = $app['form.factory']->createBuilder()
			->add('langues','entity', array(
					'required' => true,
					'label' => 'Choisissez les langues du personnage',
					'multiple' => true,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Langue',
					'choices' => $langues,
					'choice_label' => 'label',
					'data' => $originalLanguages,
			))
			->add('save','submit', array('label' => 'Valider vos modifications'))
			->getForm();

		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$langues = $data['langues'];

			// pour toutes les nouvelles langues
			foreach( $langues as $langue)
			{
				if ( ! $personnage->isKnownLanguage($langue))
				{
					$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
					$personnageLangue->setPersonnage($personnage);
					$personnageLangue->setLangue($langue);
					$personnageLangue->setSource('ADMIN');
					$app['orm.em']->persist($personnageLangue);
				}
			}
			
			if ( count($langues) == 0 )
			{
				foreach( $personnage->getLanguages() as $langue)
				{
					$personnageLangue = $personnage->getPersonnageLangue($langue);
					$app['orm.em']->remove($personnageLangue);
				}
			}
			else 
			{
				foreach( $personnage->getLanguages() as $langue)
				{
					$found = false;
					foreach ( $langues as $l)
					{
						if ($l === $langue) $found = true;
					}
					
					if ( ! $found )
					{
						$personnageLangue = $personnage->getPersonnageLangue($langue);
						$app['orm.em']->remove($personnageLangue);
					}
				}
			}

			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
	
		return $app['twig']->render('admin/personnage/updateLangue.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Modifie la liste des prières
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdatePriereAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');

		$originalPrieres = new ArrayCollection();
		foreach ( $personnage->getPrieres() as $priere)
		{
			$originalPrieres[] = $priere;
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageUpdatePriereForm(), $personnage)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$personnage = $form->getData();

			foreach($personnage->getPrieres() as $priere)
			{
				if ( ! $originalPrieres->contains($priere))
				{
					$priere->addPersonnage($personnage);
				}
			}
			
			foreach ( $originalPrieres as $priere)
			{
				if ( ! $personnage->getPrieres()->contains($priere))
				{
					$priere->removePersonnage($personnage);
				}
			}
	
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
	
		return $app['twig']->render('admin/personnage/updatePriere.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	
	
	/**
	 * Modifie la liste des sorts
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateSortAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
			
		$form = $app['form.factory']->createBuilder(new PersonnageUpdateSortForm(), $personnage)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/updateSort.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Modifie la liste des potions
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdatePotionAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
			
		$form = $app['form.factory']->createBuilder(new PersonnageUpdatePotionForm(), $personnage)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
				
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
	
		return $app['twig']->render('admin/personnage/updatePotion.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Modifie la liste des ingrédients
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateIngredientAction(Request $request, Application $app, Personnage $personnage)
	{
		$originalPersonnageIngredients = new ArrayCollection();
		
		/**
		 *  Crée un tableau contenant les objets personnageIngredient du groupe
		 */
		foreach ($personnage->getPersonnageIngredients() as $personnageIngredient)
		{
			$originalPersonnageIngredients->add($personnageIngredient);
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageIngredientForm(), $personnage)->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			/**
			 * Pour tous les ingredients
			 */
			foreach ($personnage->getPersonnageIngredients() as $personnageIngredient)
			{
				$personnageIngredient->setPersonnage($personnage);
			}
			
			/**
			 *  supprime la relation entre personnageIngredient et le personnage
			 */
			foreach ($originalPersonnageIngredients as $personnageIngredient) {
				if ($personnage->getPersonnageIngredients()->contains($personnageIngredient) == false) {
					$app['orm.em']->remove($personnageIngredient);
				}
			}
			
			$random = $form['random']->getData();
			
			/**
			 *  Gestion des ingrédients alloués au hasard
			 */
			if ( $random && $random > 0 )
			{
				$ingredients = $app['orm.em']->getRepository('LarpManager\Entities\Ingredient')->findAll();
				shuffle( $ingredients );
				$needs = new ArrayCollection(array_slice($ingredients,0,$random));
					
				foreach ( $needs as $ingredient )
				{
					$pi = new PersonnageIngredient();
					$pi->setIngredient($ingredient);
					$pi->setNombre(1);
					$pi->setPersonnage($personnage);
					$app['orm.em']->persist($pi);
				}
			}
	
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
	
		return $app['twig']->render('admin/personnage/ingredients.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Modifie la liste des ressources
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateRessourceAction(Request $request, Application $app, Personnage $personnage)
	{			
		$originalPersonnageRessources = new ArrayCollection();
		
		/**
		 *  Crée un tableau contenant les objets personnageIngredient du groupe
		 */
		foreach ($personnage->getPersonnageRessources() as $personnageRessource)
		{
			$originalPersonnageRessources->add($personnageRessource);
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageRessourceForm(), $personnage)->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
	
			/**
			 * Pour toutes les ressources
			 */
			foreach ($personnage->getPersonnageRessources() as $personnageRessource)
			{
				$personnageRessource->setPersonnage($personnage);
			}
			
			/**
			 *  supprime la relation entre personnageRessource et le personnage
			 */
			foreach ($originalPersonnageRessources as $personnageRessource) {
				if ($personnage->getPersonnageRessources()->contains($personnageRessource) == false) {
					$app['orm.em']->remove($personnageRessource);
				}
			}
			
			$randomCommun = $form['randomCommun']->getData();
				
			/**
			 *  Gestion des ressources communes alloués au hasard
			 */
			if ( $randomCommun && $randomCommun > 0 )
			{
				$ressourceCommune = $app['orm.em']->getRepository('LarpManager\Entities\Ressource')->findCommun();
				shuffle( $ressourceCommune );
				$needs = new ArrayCollection(array_slice($ressourceCommune,0,$randomCommun));
			
				foreach ( $needs as $ressource )
				{
					$pr = new PersonnageRessource();
					$pr->setRessource($ressource);
					$pr->setNombre(1);
					$pr->setPersonnage($personnage);
					$app['orm.em']->persist($pr);
				}
			}
				
			$randomRare = $form['randomRare']->getData();
				
			/**
			 *  Gestion des ressources rares alloués au hasard
			 */
			if ( $randomRare && $randomRare > 0 )
			{
				$ressourceRare = $app['orm.em']->getRepository('LarpManager\Entities\Ressource')->findRare();
				shuffle( $ressourceRare );
				$needs = new ArrayCollection(array_slice($ressourceRare,0,$randomRare));
					
				foreach ( $needs as $ressource )
				{
					$pr = new PersonnageRessource();
					$pr->setRessource($ressource);
					$pr->setNombre(1);
					$pr->setPersonnage($personnage);
					$app['orm.em']->persist($pr);
				}
			}
			
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
	
		return $app['twig']->render('admin/personnage/ressources.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Modifie la richesse
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateRichesseAction(Request $request, Application $app, Personnage $personnage)
	{
		$form = $app['form.factory']->createBuilder(new PersonnageRichesseForm(), $personnage)->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
	
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
	
		return $app['twig']->render('admin/personnage/richesse.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Gestion des documents lié à un personnage
	 * @param Request $request
	 * @param Application $app
	 * @param Personnage $personnage
	 */
	public function documentAction(Request $request, Application $app, Personnage $personnage)
	{
		$form = $app['form.factory']->createBuilder(new PersonnageDocumentForm(), $personnage)
			->add('submit','submit', array('label' => 'Enregistrer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Le document a été ajouté au personnage.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail', array('personnage' => $personnage->getId())),303);
		}
	
		return $app['twig']->render('admin/personnage/documents.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Gestion des objets lié à un personnage
	 * @param Request $request
	 * @param Application $app
	 * @param Personnage $personnage
	 */
	public function itemAction(Request $request, Application $app, Personnage $personnage)
	{
		$form = $app['form.factory']->createBuilder(new PersonnageItemForm(), $personnage)
			->add('submit','submit', array('label' => 'Enregistrer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'L\'objet a été ajouté au personnage.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail', array('personnage' => $personnage->getId())),303);
		}
	
		return $app['twig']->render('admin/personnage/items.twig', array(
				'personnage' => $personnage,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Ajoute une religion à un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddReligionAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		// refuser la demande si le personnage est Fanatique
		if ( $personnage->isFanatique() )
		{
			$app['session']->getFlashBag()->add('error','Désolé, le personnage êtes un Fanatique, il vous est impossible de choisir une nouvelle religion. (supprimer la religion fanatique qu\'il possède avant)' );
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		$personnageReligion = new \LarpManager\Entities\PersonnagesReligions();
		$personnageReligion->setPersonnage($personnage);
		
		// ne proposer que les religions que le personnage ne pratique pas déjà ...
		$availableReligions = $app['personnage.manager']->getAvailableReligions($personnage);
		
		if ( $availableReligions->count() == 0 )
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'y a plus de religion disponibles');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		// construit le tableau de choix
		$choices = array();
		foreach ( $availableReligions as $religion)
		{
			$choices[] = $religion;
		}
		
		$form = $app['form.factory']->createBuilder(new PersonnageReligionForm(), $personnageReligion)
			->add('religion','entity', array(
					'required' => true,
					'label' => 'Votre religion',
					'class' => 'LarpManager\Entities\Religion',
					'choices' => $availableReligions,
					'property' => 'label',
			))
			->add('save','submit', array('label' => 'Valider votre religion'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$personnageReligion = $form->getData();
			
			// supprimer toutes les autres religions si l'utilisateur à choisi fanatique
			// n'autoriser que un Fervent que si l'utilisateur n'a pas encore Fervent.
			if ( $personnageReligion->getReligionLevel()->getIndex() == 3 )
			{
				$personnagesReligions = $personnage->getPersonnagesReligions();
				foreach ( $personnagesReligions as $oldReligion)
				{
					$app['orm.em']->remove($oldReligion);
				}
			}
			else if ( $personnageReligion->getReligionLevel()->getIndex() == 2 )
			{
				if ( $personnage->isFervent() )
				{
					$app['session']->getFlashBag()->add('error','Désolé, vous êtes déjà Fervent d\'une autre religion, il vous est impossible de choisir une nouvelle religion en tant que Fervent. Veuillez contacter votre orga en cas de problème.');
					return $app->redirect($app['url_generator']->generate('homepage'),303);
				}
			}
						
			$app['orm.em']->persist($personnageReligion);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/addReligion.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage));
		
	}
	
	/**
	 * Retire une religion d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminRemoveReligionAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$personnageReligion = $request->get('personnageReligion');
		
		$form = $app['form.factory']->createBuilder()
			->add('save','submit', array('label' => 'Retirer la religion'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			$app['orm.em']->remove($personnageReligion);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/removeReligion.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'personnageReligion'=> $personnageReligion,
		));
	}
	
	/**
	 * Retire une langue d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminRemoveLangueAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$personnageLangue = $request->get('personnageLangue');
		
		$form = $app['form.factory']->createBuilder()
			->add('save','submit', array('label' => 'Retirer la langue'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
				
			$app['orm.em']->remove($personnageLangue);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/removeLangue.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'personnageLangue'=> $personnageLangue,
		));
	}
	
	/**
	 * Modifie l'origine d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateOriginAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$oldOrigine = $personnage->getTerritoire();
		
		$form = $app['form.factory']->createBuilder(new PersonnageOriginForm(), $personnage)
			->add('save','submit', array('label' => 'Valider l\'origine du personnage'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$personnage = $form->getData();
			
			// le personnage doit perdre les langues de son ancienne origine
			// et récupérer les langue de sa nouvelle origine
			foreach( $personnage->getPersonnageLangues() as $personnageLangue )
			{
				if ($personnageLangue->getSource() == 'ORIGINE')
				{
					$personnage->removePersonnageLangues($personnageLangue);
					$app['orm.em']->remove($personnageLangue);
				}
			}			
			$newOrigine = $personnage->getTerritoire();
			foreach ( $newOrigine->getLangues() as $langue )
			{
				$personnageLangue = new \LarpManager\Entities\PersonnageLangues();
				$personnageLangue->setPersonnage($personnage);
				$personnageLangue->setSource('ORIGINE');
				$personnageLangue->setLangue($langue);
				
				$app['orm.em']->persist($personnageLangue);
				$personnage->addPersonnageLangues($personnageLangue);
			}
						
			$app['orm.em']->persist($personnage);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','Le personnage a été sauvegardé.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/updateOrigine.twig', array(
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
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
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
			
			// cas special noblesse
			// noblesse apprentit +2 renomme
			// noblesse initie  +3 renomme
			// noblesse expert +2 renomme
			// TODO : trouver un moyen pour ne pas implémenter les règles spéciales de ce type dans le code.
			if ( $lastCompetence->getCompetenceFamily()->getLabel() == "Noblesse")
			{
				switch ($lastCompetence->getLevel()->getId())
				{
					case 1:
						$personnage->removeRenomme(2);
						break;
					case 2:
						$personnage->removeRenomme(3);
						break;
					case 3:
						$personnage->removeRenomme(2);
						break;
					case 4:
						$personnage->removeRenomme(5);
						break;
					case 5:
						$personnage->removeRenomme(6);
						break;
				}
			}
			
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
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);			
		}
		
		return $app['twig']->render('admin/personnage/removeCompetence.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'competence' =>  $lastCompetence,
		));
		
	}
	
	public function adminAddCompetenceAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		
		$availableCompetences = $app['personnage.manager']->getAvailableCompetences($personnage);
		
		if ( $availableCompetences->count() == 0 )
		{
			$app['session']->getFlashBag()->add('error','Désolé, il n\'y a plus de compétence disponible.');
			return $app->redirect($app['url_generator']->generate('homepage'),303);
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
				$app['session']->getFlashBag()->add('error','Vos n\'avez pas suffisement de points d\'expérience pour acquérir cette compétence.');
				return $app->redirect($app['url_generator']->generate('homepage'),303);
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
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail', array('personnage' => $personnage->getId())),303);
		}
			
		return $app['twig']->render('admin/personnage/competence.twig', array(
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
	public function exportAction(Request $request, Application $app, Personnage $personnage)
	{
		$participant = $personnage->getParticipants()->last();
		$groupe = null;

		if ( $participant && $participant->getGroupeGn() )
		{
			$groupe = $participant->getGroupeGn()->getGroupe();
		}

		return $app['twig']->render('admin/personnage/print.twig', array(
				'personnage' => $personnage,
				'participant' => $participant,
				'groupe' => $groupe,
			));
	}
	
	/**
	 * Ajoute un evenement de chronologie au personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddChronologieAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$personnageChronologie = new \LarpManager\Entities\PersonnageChronologie();
		$personnageChronologie->setPersonnage($personnage);

		$form = $app['form.factory']->createBuilder(new PersonnageChronologieForm(), $personnageChronologie)
			->add('save','submit', array('label' => 'Valider l\'évènement'))
			->getForm();

		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$anneeGN = $form->get('annee')->getData();
			$evenement = $form->get('evenement')->getData();
				
			$personnageChronologie = new \LarpManager\Entities\PersonnageChronologie();
				
			$personnageChronologie->setAnnee($anneeGN);
			$personnageChronologie->setEvenement($evenement);
			$personnageChronologie->setPersonnage($personnage);

			$app['orm.em']->persist($personnageChronologie);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','L\'évènement a été ajouté à la chronologie.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/updateChronologie.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'personnageChronologie' => $personnageChronologie,
		));
	}

	/**
	 * Retire un évènement d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDeleteChronologieAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$personnageChronologie = $request->get('personnageChronologie');
		
		$form = $app['form.factory']->createBuilder()
			->add('save','submit', array('label' => 'Retirer l\'évènement'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
				
			$app['orm.em']->remove($personnageChronologie);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','L\'évènement a été supprimé de la chronologie.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/removeChronologie.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'personnageChronologie'=> $personnageChronologie,
		));
	}

	/**
	 * Ajoute une lignée au personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddLigneeAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$personnageLignee = new \LarpManager\Entities\PersonnageLignee();
		$personnageLignee->setPersonnage($personnage);
		
		$form = $app['form.factory']->createBuilder(new PersonnageLigneeForm(), $personnageLignee)
			->add('save','submit', array('label' => 'Valider les modifications'))
			->getForm();

		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$parent1 = $form->get('parent1')->getData();
			$parent2 = $form->get('parent2')->getData();
			$lignee = $form->get('lignee')->getData();

			$personnageLignee->setParent1($parent1);
			$personnageLignee->setParent2($parent2);
			$personnageLignee->setLignee($lignee);

			$app['orm.em']->persist($personnageLignee);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','La lignée a été ajoutée.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/updateLignee.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'lignee' => $personnageLignee,
			));
	}

	/**
	 * Retire une lignée d'un personnage
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDeleteLigneeAction(Request $request, Application $app)
	{
		$personnage = $request->get('personnage');
		$personnageLignee = $request->get('personnageLignee');
		
		$form = $app['form.factory']->createBuilder()
			->add('save','submit', array('label' => 'Retirer la lignée'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
				
			$app['orm.em']->remove($personnageLignee);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','La lignée a été supprimée.');
			return $app->redirect($app['url_generator']->generate('personnage.admin.detail',array('personnage'=>$personnage->getId())),303);
		}
		
		return $app['twig']->render('admin/personnage/removeLignee.twig', array(
				'form' => $form->createView(),
				'personnage' => $personnage,
				'personnageLignee'=> $personnageLignee,
		));
	}
}
