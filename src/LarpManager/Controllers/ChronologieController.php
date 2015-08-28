<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * LarpManager\Controllers\ChronologieController
 *
 */
class ChronologieController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Chronologie');
		$chronos = $repo->findAll();
		foreach($chronos as $chrono)
		{
			$chrono->getPays(); //y'a surement mieux a faire...
		}
		return $app['twig']->render('chronologie/index.twig', array('chronologies' => $chronos));
	}

	/**
	 * @description affiche le formulaire d'ajout d'une chrono
	 */
	public function addAction(Request $request, Application $app)
	{
		if ( $request->getMethod() === 'POST' )
		{
			$date = $request->get('date');
			$paysId = $request->get('paysId');
			$description = $request->get('description');
			
			$chrono = new \LarpManager\Entities\Chronologie();
			$chrono->setDate(new \DateTime($date));
			
			$pays = $app['orm.em']->find('\LarpManager\Entities\Pays',$paysId);
			if(!$pays)
			{
				return $app->redirect($app['url_generator']->generate('chrono_list'));
			}
			$chrono->setPays($pays);
			$chrono->setDescription($description);
				
			$app['orm.em']->persist($chrono);
			$app['orm.em']->flush();
				
			return $app->redirect($app['url_generator']->generate('chrono_list'));
		}
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Pays');
		$pays = $repo->findAll();
		return $app['twig']->render('chronologie/add.twig', array('pays' => $pays));
	}

	/**
	 * @description affiche le formulaire de modification d'une chrono
	 */
	public function modifyAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		$chrono = $app['orm.em']->find('\LarpManager\Entities\Chronologie',$id);
		if(!$chrono)
		{
			return $app->redirect($app['url_generator']->generate('chrono_list'));
		}
		
		if ( $request->getMethod() === 'POST' )
		{
			$date = $request->get('date');
			$paysId = $request->get('paysId');
			$description = $request->get('description');
			
			$chrono->setDate(new \DateTime($date));
			$pays = $app['orm.em']->find('\LarpManager\Entities\Pays',$paysId);
			if(!$pays)
			{
				return $app->redirect($app['url_generator']->generate('chrono_list'));
			}
				
			$chrono->setPays($pays);
			$chrono->setDescription($description);
			
			$app['orm.em']->flush();
	
			return $app->redirect($app['url_generator']->generate('chrono_list'));
		}
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Pays');
		$pays = $repo->findAll();
		$chrono->getPays();
		return $app['twig']->render('chronologie/modify.twig', array('chronologie' => $chrono, 'pays' => $pays));
	}
	
	/**
	 * @description affiche le formulaire de suppresion d'une chrono
	 */
	public function removeAction(Request $request, Application $app)
	{
		$id = $request->get('index');

		$chrono = $app['orm.em']->find('\LarpManager\Entities\Chronologie',$id);

		if ( $chrono )
		{
			if ( $request->getMethod() === 'POST' )
			{
				$app['orm.em']->remove($chrono);
				$app['orm.em']->flush();
				return $app->redirect($app['url_generator']->generate('chrono_list'));
			}
			$chrono->getPays();
			return $app['twig']->render('chronologie/remove.twig', array('chronologie' => $chrono));
		}
		else
		{
			return $app->redirect($app['url_generator']->generate('chrono_list'));
		}
	}

	/**
	 * @description affiche la détail d'une chronologie
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');

		$chrono = $app['orm.em']->find('\LarpManager\Entities\Chronologie',$id);

		if ( $chrono )
		{
			$chrono->getPays();
			return $app['twig']->render('chronologie/detail.twig', array('chronologie',$chrono));
		}
		else
		{
			return $app->redirect($app['url_generator']->generate('chrono_list'));
		}
	}
}