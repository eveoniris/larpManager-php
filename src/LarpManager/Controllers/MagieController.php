<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\DomaineForm;
use LarpManager\Form\DomaineDeleteForm;
use LarpManager\Form\SortForm;
use LarpManager\Form\SortDeleteForm;
use LarpManager\Form\PotionForm;
use LarpManager\Form\PotionDeleteForm;

/**
 * LarpManager\Controllers\MagieController
 *
 * @author kevin
 *
 */
class MagieController
{
	
	/**
	 * Découverte de la magie, des domaines et sortilèges
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$domaines = $app['orm.em']->getRepository('\LarpManager\Entities\Domaine')->findAll();
		$personnage = $app['user']->getPersonnage();
		
		return $app['twig']->render('public/magie/index.twig', array(
				'domaines' => $domaines,
				'personnage' => $personnage,
		));
	}
	
	/**
	 * Liste des potions
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionListAction(Request $request, Application $app)
	{
		$potions = $app['orm.em']->getRepository('\LarpManager\Entities\Potion')->findAll();
		
		return $app['twig']->render('admin/potion/list.twig', array(
			'potions' => $potions,	
		));
	}
	
	/**
	 * Detail d'une potion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionDetailAction(Request $request, Application $app)
	{
		$potion = $request->get('potion');
	
		return $app['twig']->render('admin/potion/detail.twig', array(
				'potion' => $potion,
		));
	}
	
	/**
	 * Ajoute une potion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionAddAction(Request $request, Application $app)
	{
		$potion = new \LarpManager\Entities\Potion();
	
		$form = $app['form.factory']->createBuilder(new PotionForm(), $potion)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$potion = $form->getData();
	
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
					
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('magie.potion.list'),301);
				}
					
				$documentFilename = hash('md5',$potion->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$potion->setDocumentUrl($documentFilename);
			}
				
			$app['orm.em']->persist($potion);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','La potion a été ajouté');
			return $app->redirect($app['url_generator']->generate('magie.potion.detail',array('potion'=>$potion->getId())),301);
		}
	
		return $app['twig']->render('admin/potion/add.twig', array(
				'potion' => $potion,
				'form' => $form->createView(),
		));
	
	}
	
	/**
	 * Met à jour une potion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionUpdateAction(Request $request, Application $app)
	{
		$potion = $request->get('potion');
	
		$form = $app['form.factory']->createBuilder(new PotionForm(), $potion)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$potion = $form->getData();
	
			$files = $request->files->get($form->getName());
				
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
	
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
					
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('magie.potion.list'),301);
				}
					
				$documentFilename = hash('md5',$potion->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$potion->setDocumentUrl($documentFilename);
			}
				
			$app['orm.em']->persist($potion);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','La potion a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('magie.potion.detail',array('potion'=>$potion->getId())),301);
		}
	
		return $app['twig']->render('admin/potion/update.twig', array(
				'potion' => $potion,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime une potion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function potionDeleteAction(Request $request, Application $app)
	{
		$potion = $request->get('potion');
	
		$form = $app['form.factory']->createBuilder(new PotionDeleteForm(), $potion)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$potion = $form->getData();
	
			$app['orm.em']->remove($potion);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','La potion a été suprimé');
			return $app->redirect($app['url_generator']->generate('magie.potion.list'),301);
		}
	
		return $app['twig']->render('admin/potion/delete.twig', array(
				'potion' => $potion,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Obtenir le document lié a une potion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function getPotionDocumentAction(Request $request, Application $app)
	{
		$document = $request->get('document');
		$potion = $request->get('potion');
	
		// on ne peux télécharger que les documents des compétences que l'on connait
		/*if  ( ! $app['security.authorization_checker']->isGranted('ROLE_REGLE') )
		{
		if ( $app['user']->getPersonnage() )
		{
		if ( ! $app['user']->getPersonnage()->getCompetences()->contains($competence) )
		{
		$app['session']->getFlashBag()->add('error', 'Vous n\'avez pas les droits necessaires');
		}
		}
		}*/
	
		$file = __DIR__.'/../../../private/doc/'.$document;
	
		$stream = function () use ($file) {
			readfile($file);
		};
	
		return $app->stream($stream, 200, array(
				'Content-Type' => 'text/pdf',
				'Content-length' => filesize($file),
				'Content-Disposition' => 'attachment; filename="'.$potion->getLabel().'.pdf"'
		));
	}
	
	/**
	 * Liste des domaines de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineListAction(Request $request, Application $app)
	{
		$domaines = $app['orm.em']->getRepository('\LarpManager\Entities\Domaine')->findAll();
		
		return $app['twig']->render('admin/domaine/list.twig', array(
				'domaines' => $domaines,
		));
	}
	
	/**
	 * Detail d'un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineDetailAction(Request $request, Application $app)
	{
		$domaine = $request->get('domaine');
		
		return $app['twig']->render('admin/domaine/detail.twig', array(
				'domaine' => $domaine,
		));
	}
	
	/**
	 * Ajoute un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineAddAction(Request $request, Application $app)
	{
		$domaine = new \LarpManager\Entities\Domaine();
		
		$form = $app['form.factory']->createBuilder(new DomaineForm(), $domaine)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$domaine = $form->getData();
			
			$app['orm.em']->persist($domaine);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le domaine de magie a été ajouté');
			return $app->redirect($app['url_generator']->generate('magie.domaine.detail',array('domaine'=>$domaine->getId())),301);
		}
		
		return $app['twig']->render('admin/domaine/add.twig', array(
				'domaine' => $domaine,
				'form' => $form->createView(),
		));
		
	}
	
	/**
	 * Met à jour un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineUpdateAction(Request $request, Application $app)
	{
		$domaine = $request->get('domaine');
		
		$form = $app['form.factory']->createBuilder(new DomaineForm(), $domaine)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$domaine = $form->getData();
				
			$app['orm.em']->persist($domaine);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le domaine de magie a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('magie.domaine.detail',array('domaine'=>$domaine->getId())),301);
		}
		
		return $app['twig']->render('admin/domaine/update.twig', array(
				'domaine' => $domaine,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime un domaine de magie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function domaineDeleteAction(Request $request, Application $app)
	{
		$domaine = $request->get('domaine');
		
		$form = $app['form.factory']->createBuilder(new DomaineDeleteForm(), $domaine)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$domaine = $form->getData();
		
			$app['orm.em']->remove($domaine);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success','Le domaine de magie a été suprimé');
			return $app->redirect($app['url_generator']->generate('magie.domaine.list'),301);
		}
		
		return $app['twig']->render('admin/domaine/delete.twig', array(
				'domaine' => $domaine,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Liste des sortilèges
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortListAction(Request $request, Application $app)
	{
		$sorts = $app['orm.em']->getRepository('\LarpManager\Entities\Sort')->findAll();
	
		return $app['twig']->render('admin/sort/list.twig', array(
				'sorts' => $sorts,
		));
	}
	
	/**
	 * Detail d'un sortilèges
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortDetailAction(Request $request, Application $app)
	{
		$sort = $request->get('sort');
	
		return $app['twig']->render('admin/sort/detail.twig', array(
				'sort' => $sort,
		));
	}
	
	/**
	 * Ajoute un sortilège
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortAddAction(Request $request, Application $app)
	{
		$sort = new \LarpManager\Entities\Sort();
	
		$form = $app['form.factory']->createBuilder(new SortForm(), $sort)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$sort = $form->getData();
				
			
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
					
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('magie.sort.list'),301);
				}
					
				$documentFilename = hash('md5',$sort->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$sort->setDocumentUrl($documentFilename);
			}
			
			$app['orm.em']->persist($sort);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le sortilége a été ajouté');
			return $app->redirect($app['url_generator']->generate('magie.sort.detail',array('sort'=>$sort->getId())),301);
		}
	
		return $app['twig']->render('admin/sort/add.twig', array(
				'sort' => $sort,
				'form' => $form->createView(),
		));
	
	}
	
	/**
	 * Met à jour un sortilège
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortUpdateAction(Request $request, Application $app)
	{
		$sort = $request->get('sort');
	
		$form = $app['form.factory']->createBuilder(new SortForm(), $sort)
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$sort = $form->getData();
	
			$files = $request->files->get($form->getName());
			
			// Si un document est fourni, l'enregistrer
			if ( $files['document'] != null )
			{
				
				$path = __DIR__.'/../../../private/doc/';
				$filename = $files['document']->getClientOriginalName();
				$extension = 'pdf';
					
				if (!$extension || ! in_array($extension, array('pdf'))) {
					$app['session']->getFlashBag()->add('error','Désolé, votre document ne semble pas valide (vérifiez le format de votre document)');
					return $app->redirect($app['url_generator']->generate('magie.sort.list'),301);
				}
					
				$documentFilename = hash('md5',$sort->getLabel().$filename . time()).'.'.$extension;
					
				$files['document']->move($path,$documentFilename);
					
				$sort->setDocumentUrl($documentFilename);
			}
			
			$app['orm.em']->persist($sort);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le sortilège a été sauvegardé');
			return $app->redirect($app['url_generator']->generate('magie.sort.detail',array('sort'=>$sort->getId())),301);
		}
	
		return $app['twig']->render('admin/sort/update.twig', array(
				'sort' => $sort,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Supprime un sortilège
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function sortDeleteAction(Request $request, Application $app)
	{
		$sort = $request->get('sort');
	
		$form = $app['form.factory']->createBuilder(new SortDeleteForm(), $sort)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
	
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$sort = $form->getData();
	
			$app['orm.em']->remove($sort);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le sortilège a été suprimé');
			return $app->redirect($app['url_generator']->generate('magie.sort.list'),301);
		}
	
		return $app['twig']->render('admin/sort/delete.twig', array(
				'sort' => $sort,
				'form' => $form->createView(),
		));
	}	
	
	/**
	 * Obtenir le document lié a un sortilège
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function getSortDocumentAction(Request $request, Application $app)
	{
		$document = $request->get('document');
		$sort = $request->get('sort');
		
		// on ne peux télécharger que les documents des compétences que l'on connait
		/*if  ( ! $app['security.authorization_checker']->isGranted('ROLE_REGLE') )
		{
			if ( $app['user']->getPersonnage() )
			{
				if ( ! $app['user']->getPersonnage()->getCompetences()->contains($competence) )
				{
					$app['session']->getFlashBag()->add('error', 'Vous n\'avez pas les droits necessaires');
				}
			}
		}*/
		
		$file = __DIR__.'/../../../private/doc/'.$document;
		
		$stream = function () use ($file) {
			readfile($file);
		};
		
		return $app->stream($stream, 200, array(
				'Content-Type' => 'text/pdf',
				'Content-length' => filesize($file),
				'Content-Disposition' => 'attachment; filename="'.$sort->getLabel().'.pdf"'
		));
	}
}