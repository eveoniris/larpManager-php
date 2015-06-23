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
		$repo = $app['orm.em']->getRepository('LarpManager\Entities\Gn');
		$gns = $repo->findAll();
		return $app['twig']->render('gn/index.twig', array('gns' => $gns));
	}
	
	/**
	 * @description 
	 */
	public function addAction(Request $request, Application $app)
	{
		if ( $request->getMethod() === 'POST' ) {
			
			$gn = new LarpManager\Entities\Gn();
			$gn->setName();
			
			$app['orm.em']->persist($gn);
			$app['orm.em']->flush();
			
			return $app->redirect('gn_list');	
		}
		
		return $app['twig']->render('gn/add.twig');
	}
	
	/**
	 * @description affiche la vue index.twig
	 */
	public function removeAction(Request $request, Application $app)
	{
		return $app['twig']->render('gn/remove.twig');
	}
	
	/**
	 * @description affiche la vue index.twig
	 */
	public function detailAction(Request $request, Application $app)
	{
		return $app['twig']->render('gn/detail.twig');
	}
}
