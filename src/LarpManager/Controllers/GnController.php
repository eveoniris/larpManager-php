<?php

namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

use LarpManager\Form\GnForm;

/**
 * LarpManager\Controllers\GnController
 *
 * @author kevin
 *
 */
class GnController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app) 
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Gn');
		$gns = $repo->findAll();
		return $app['twig']->render('gn/index.twig', array('gns' => $gns));
	}
	
	/**
	 * affiche le formulaire d'ajout d'un gn
	 * Lorsqu'un GN est créé, son forum associé doit lui aussi être créé
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$gn = new \LarpManager\Entities\Gn();
	
		$form = $app['form.factory']->createBuilder(new GnForm(), $gn)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$gn = $form->getData();
			
			/**
			 * Création du topic associé à ce gn
			 * @var \LarpManager\Entities\Topic $topic
			 */
			$topic = new \LarpManager\Entities\Topic();
			$topic->setTitle($gn->getLabel());
			$topic->setDescription($gn->getDescription());
			$topic->setUser($app['user']);

			$gn->setTopic($topic);
			
			$app['orm.em']->persist($topic);
			$app['orm.em']->persist($gn);
			
			// défini les droits d'accés à ce forum
			// (les participants au GN ont le droits d'accéder à ce forum)
			$topic->setRight('GN_PARTICIPANT');
			$topic->setObjectId($gn->getId());
			$app['orm.em']->persist($topic);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Le gn a été ajouté.');
	
			return $app->redirect($app['url_generator']->generate('gn'),301);
		}
	
		return $app['twig']->render('gn/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Detail d'un gn
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$gn = $app['orm.em']->find('\LarpManager\Entities\Gn',$id);
	
		if ( $gn )
		{
			if ( $app['security.authorization_checker']->isGranted('ROLE_ADMIN') )
			{
				return $app['twig']->render('gn/detail.twig', array('gn' => $gn));
			}
			else
			{
				return $app['twig']->render('gn/detail_joueur.twig', array('gn' => $gn));
			}
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le gn n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('homepage'));
		}	
	}
	
	/**
	 * Met à jour un gn
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$gn = $app['orm.em']->find('\LarpManager\Entities\Gn',$id);
	
		$form = $app['form.factory']->createBuilder(new GnForm(), $gn)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$gn = $form->getData();
	
			if ($form->get('update')->isClicked())
			{	
				$app['orm.em']->persist($gn);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le gn a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($gn);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le gn a été supprimé.');
			}
	
			return $app->redirect($app['url_generator']->generate('gn'));
		}
	
		return $app['twig']->render('gn/update.twig', array(
				'gn' => $gn,
				'form' => $form->createView(),
		));
	}
}
