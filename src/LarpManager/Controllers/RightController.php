<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * LarpManager\Controllers\RightController
 *
 * @author kevin
 *
 */
class RightController
{	
	/**
	 * Liste des droits
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @return View $view
	 */
	public function listAction(Request $request, Application $app)
	{
		$rights = $app['larp.manager']->getAvailableRoles();
		
		return $app['twig']->render('admin/right/list.twig', array(
				'rights' => $rights));
	}
}