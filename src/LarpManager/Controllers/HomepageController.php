<?php

namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\GroupeInscriptionForm;
use LarpManager\Form\GnInscriptionForm;
use LarpManager\Form\TrombineForm;
use Imagine\Image\Box;



/**
 * LarpManager\Controllers\HomepageController
 *
 * @author kevin
 *
 */
class HomepageController
{

	/**
	 * Choix de la page d'acceuil en fonction de l'état de l'utilisateur
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app) 
	{	
		if ( ! $app['user'] )
		{
			return $this->notConnectedIndexAction($request, $app);
		}
		else if ( $app['security.authorization_checker']->isGranted('ROLE_ORGA') )
		{
			return $this->orgaIndexAction($request, $app);
		}
		
		return $this->joueurIndexAction($request, $app);
	}
	
	/**
	 * Modification de la photo
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function trombineAction(Request $request, Application $app)
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
				return $app->redirect($app['url_generator']->generate('trombine'),301);
			}
						
			$trombineFilename = hash('md5',$app['user']->getUsername().$filename . time()).'.'.$extension; 
			
			$image = $app['imagine']->open($files['trombine']->getPathname());
			$image->resize($image->getSize()->widen( 160 ));
			$image->save($path. $trombineFilename);

			$app['user']->setTrombineUrl($trombineFilename);
			$app['orm.em']->persist($app['user']);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Votre photo a été enregistrée');
			return $app->redirect($app['url_generator']->generate('trombine'),301);
		}
		
		return $app['twig']->render('public/trombine.twig', array(
				'form' => $form->createView()
		));
	}
	
	/**
	 * Obtenir une image protégée
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function getTrombineAction(Request $request, Application $app)
	{
		$trombine = $request->get('trombine');
		$filename = __DIR__.'/../../../private/img/'.$trombine;
		return $app->sendFile($filename);
	}
	
	/**
	 * Page d'acceuil pour les utilisateurs non connecté
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function notConnectedIndexAction(Request $request, Application $app)
	{
		return $app['twig']->render('homepage/not_connected.twig');
	}
	
	/**
	 * affiche la page d'acceuil.
	 *
	 * Si l'utilisateur a enregistrer ses informations de joueur, on lui propose la liste
	 * des GNs auquel il peux s'inscrire.
	 *
	 * Si l'utilisateur n'a pas enregistré ses informations de joueur, on lui propose la liste
	 * de tous les GNs actifs.
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function joueurIndexAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new GroupeInscriptionForm(), array())
			->add('subscribe','submit', array('label' => 'S\'inscrire'))
			->getForm();
			
		$repoGn = $app['orm.em']->getRepository('LarpManager\Entities\Gn');
		$gns = $repoGn->findByActive();
		
		$repoAnnonce = $app['orm.em']->getRepository('LarpManager\Entities\Annonce');
		$annonces = $repoAnnonce->findBy(array('archive' => false));
		
		return $app['twig']->render('homepage/index.twig', array(
				'form_groupe' => $form->createView(),
				'gns' => $gns,
				'annonces' => $annonces,
		));
	}
	
	/**
	 * Affichage de la page d'acceuil pour les orgas
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function orgaIndexAction(Request $request, Application $app)
	{
		
		$repoAnnonce = $app['orm.em']->getRepository('LarpManager\Entities\Annonce');
		$annonces = $repoAnnonce->findBy(array('archive' => false));
		
		return $app['twig']->render('homepage/orga.twig', array(
				'annonces' => $annonces,
		));
	}
	
	/**
	 * Affiche une carte du monde
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function worldAction(Request $request, Application $app)
	{	
		return $app['twig']->render('homepage/world.twig');		
	}
	
	/**
	 * Fourni la liste des pays, leur geographie et leur description
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function countriesAction(Request $request, Application $app)
	{
		$repoTerritoire = $app['orm.em']->getRepository('LarpManager\Entities\Territoire');
		$territoires = $repoTerritoire->findRoot();
		
		$countries = array();
		foreach ( $territoires as $territoire)
		{
			$countries[] = array(
					'id' => $territoire->getId(),
					'geom' => $territoire->getGeojson(),
					'name' => $territoire->getNom(),
					'color' => $territoire->getColor(),
					'description' => strip_tags($territoire->getDescription())
				);
		}
		
		return $app->json($countries);
	}
	
	public function fiefsAction(Request $request, Application $app)
	{
		$repoTerritoire = $app['orm.em']->getRepository('LarpManager\Entities\Territoire');
		$territoires = $repoTerritoire->findFiefs();
		
		$fiefs = array();
		foreach ( $territoires as $territoire)
		{
			$fiefs[] = array(
					'id' => $territoire->getId(),
					'geom' => $territoire->getGeojson(),
					'name' => $territoire->getNom(),
					'color' => $territoire->getColor(),
					'description' => strip_tags($territoire->getDescription())
			);
		}
		
		return $app->json($fiefs);
	}

	/**
	 * Met à jour la geographie d'un pays
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateCountryGeomAction(Request $request, Application $app)
	{
		$territoire = $request->get('territoire');
		$geom = $request->get('geom');
		$color = $request->get('color');
		
		$territoire->setGeojson($geom);
		$territoire->setColor($color);
		
		$app['orm.em']->persist($territoire);
		$app['orm.em']->flush();
		
		$country = array(
					'id' => $territoire->getId(),
					'geom' => $territoire->getGeojson(),
					'name' => $territoire->getNom(),
					'description' => strip_tags($territoire->getDescription())
				);
		return $app->json($country);
	}
	
	/**
	 * Affiche une page récapitulatif des liens pour discuter
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function discuterAction(Request $request, Application $app)
	{
		return $app['twig']->render('public/discuter.twig');
	}
	
	/**
	 * Affiche une page récapitulatif des événements
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function evenementAction(Request $request, Application $app)
	{
		return $app['twig']->render('public/evenement.twig');
	}
	
	/**
	 * Affiche les mentions légales
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function legalAction(Request $request, Application $app)
	{
		return $app['twig']->render('homepage/legal.twig');
	}
	
	/**
	 * Formulaire d'inscription d'un joueur dans un gn
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function inscriptionGnFormAction(Request $request, Application $app)
	{
		$idGn = $request->get('idGn');
		
		$repo = $app['orm.em']->getRepository("LarpManager\Entities\Gn");
		$gn = $repo->findOneById($idGn);
		
		/** Erreur, le gn n'existe pas ou n'est pas actif */
		if ( ! $gn || $gn->getActif() == false )
		{
			throw new LarpManager\Exception\RequestInvalidException();
		}
		
		$form = $app['form.factory']->createBuilder(new GnInscriptionForm(), array('idGn' => $idGn))
			->add('subscribe','submit', array('label' => 'S\'inscrire'))
			->getForm();
		
		return $app['twig']->render('homepage/inscriptionGn.twig', array(
				'form' => $form->createView(),
				'gn' => $gn,
		));
	}
	
	/**
	 * Traitement du formulaire d'inscription d'un joueur dans un gn
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @throws LarpManager\Exception\RequestInvalidException
	 */
	public function inscriptionGnAction(Request $request, Application $app)
	{
		$user = $app['user'];
		
		/** Erreur, l'utilisateur n'est pas encore un joueur */
		if ( ! $user->getJoueur() )
		{
			throw new LarpManager\Exception\RequestInvalidException();
		}
		
		$form = $app['form.factory']->createBuilder(new GnInscriptionForm())
			->add('subscribe','submit', array('label' => 'S\'inscrire'))
			->getForm();
		
		$form->handleRequest($request);
		
		/** Erreur si la requête est invalide */
		if (  ! $form->isValid() )
		{
			throw new LarpManager\Exception\RequestInvalidException();
		}
		
		$data = $form->getData();

		$repo = $app['orm.em']->getRepository("LarpManager\Entities\Gn");
		$gn = $repo->findOneById($data['idGn']);
		
		/** Erreur si le gn n'est pas actif (l'utilisateur à bidouillé la requête */
		if ( ! $gn || ! $gn->getActif() )
		{
			throw new LarpManager\Exception\RequestInvalidException();
		}
		
		/** Enregistre en base de données la nouvelle relation entre le joueur et le gn */
		$joueurGn = new \LarpManager\Entities\JoueurGn();
		$joueurGn->setJoueur($app['user']->getJoueur());
		$joueurGn->setGn($gn);
		$joueurGn->setSubscriptionDate(new \Datetime('NOW'));
		
		$app['orm.em']->persist($joueurGn);
		$app['orm.em']->flush();
		
		/** redirige vers la page principale avec un message de succés */
		$app['session']->getFlashBag()->add('success', 'Vous êtes maintenant inscrit au gn '. $gn->getLabel());
		return $app->redirect($app['url_generator']->generate('homepage'),301);
		
	}
	
	/**
	 * Inscription dans un groupe
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function inscriptionAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new GroupeInscriptionForm(), array())
			->add('subscribe','submit', array('label' => 'S\'inscrire'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
			$groupe = $repo->findOneByCode($data['code']);
			
			if ( $groupe )
			{
				$participant = $app['user']->getParticipantByGn($app['larp.manager']->getGnActif());
				$participant->setGroupe($groupe);
												
				$app['orm.em']->persist($participant);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'Vous êtes maintenant inscrit au groupe.');
				return $app->redirect($app['url_generator']->generate('homepage'),301);
			}
			else
			{
				$app['session']->getFlashBag()->add('error', 'Désolé, le code fourni n\'est pas valide. Veuillez vous rapprocher de votre chef de groupe pour le vérifier.');
				return $app->redirect($app['url_generator']->generate('homepage'),301);
			}
		}
	}
}