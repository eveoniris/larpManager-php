<?php

namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

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
	 * @description affiche le formulaire d'ajout d'un gn
	 */
	public function addAction(Request $request, Application $app)
	{
		if ( $request->getMethod() === 'POST' ) 
		{
			$name = $request->get('name');
			
			$gn = new \LarpManager\Entities\Gn();
			$gn->setName($name);
			
			$app['orm.em']->persist($gn);
			$app['orm.em']->flush();
			
			return $app->redirect($app['url_generator']->generate('gn_list'));
		}
		
		return $app['twig']->render('gn/add.twig');
	}
	
	/**
	 * @description affiche le formulaire de suppresion d'un gn
	 */
	public function removeAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$gn = $app['orm.em']->find('\LarpManager\Entities\Gn',$id);
		
		if ( $gn )
		{
			if ( $request->getMethod() === 'POST' )
			{
				$app['orm.em']->remove($gn);
				$app['orm.em']->flush();
				return $app->redirect($app['url_generator']->generate('gn_list'));
			}
			return $app['twig']->render('gn/remove.twig', array('gn' => $gn));
		}
		else
		{
			return $app->redirect($app['url_generator']->generate('gn_list'));
		}
	}
	
	/**
	 * @description affiche la détail d'un gn
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$gn = $app['orm.em']->find('\LarpManager\Entities\Gn',$id);
		
		if ( $gn )
		{
			return $app['twig']->render('gn/detail.twig', array('gn',$gn));
		}
		else
		{
			return $app->redirect($app['url_generator']->generate('gn_list'));
		}
	}
}
