<?php

namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class HomepageController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app) 
	{	
		return $app['twig']->render('homepage/index.twig');
	}
}