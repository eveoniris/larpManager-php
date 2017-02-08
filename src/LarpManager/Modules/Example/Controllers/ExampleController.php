<?php

namespace LarpManager\Modules\Example\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


/**
 * LarpManager\Controllers\AdminController
 *
 * @author kevin
 *
 */
class ExampleController
{
	public function indexAction(Request $request, Application $app)
	{
		return $app['twig']->render('@Example/index.twig', array());
	}
}