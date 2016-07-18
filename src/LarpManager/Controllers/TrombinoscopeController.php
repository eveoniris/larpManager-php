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
	
	/**
	 * Permet de selectionner des personnages pour faire un trombinoscope
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function persoAction(Request $request, Application $app)
	{
		$personnages = null;
		$titre = null;
		
		$form = $app['form.factory']->createBuilder()
			->add('titre', 'text', array(
					'label' => 'Le titre de votre sélection',
			))
			->add('ids', 'textarea', array(
					'label' => 'Indiquez les numéros des personnages séparé d\'un espace',
			))
			->add('send','submit', array('label' => 'Envoyer'))
			->add('print','submit', array('label' => 'Imprimer'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$titre = $data['titre'];
			$ids = $data['ids'];
			$ids = explode(' ',$ids);
			$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Personnage');
			$personnages = $repo->findByIds($ids);
			
			if ( $form->get('print')->isClicked())
			{
				return $app['twig']->render('admin/trombinoscopePersoPrint.twig', array(
						'titre' => $titre,
						'personnages' => $personnages,
				));
			}
		}
		
		return $app['twig']->render('admin/trombinoscopePerso.twig', array(
			'titre' => $titre,
			'personnages' => $personnages,
			'form' => $form->createView(),
		));
	}
}