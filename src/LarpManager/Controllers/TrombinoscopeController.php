<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use JasonGrimes\Paginator;

/**
 * LarpManager\Controllers\TrombinoscopeController
 *
 * @author kevin
 *
 */
class TrombinoscopeController
{
	/**
	 * Le trombinoscope général
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{				
		$order_by = $request->get('order_by') ?: 'username';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 16);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		$criteria = array();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\User');
		$users = $repo->findList(
				$criteria,
				array( 'by' =>  $order_by, 'dir' => $order_dir),
				$limit,
				$offset);
		
		$numResults = $repo->findCount($criteria);
		
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('trombinoscope') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
					
		return $app['twig']->render('admin/trombinoscope.twig', array(
				'users' => $users,
				'paginator' => $paginator,
		));
	}
}