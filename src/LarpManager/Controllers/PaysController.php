<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

// Chronologie d'un pays
class PaysController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Pays');
		$pays = $repo->findAll();
		return $app['twig']->render('pays/index.twig', array('pays' => $pays));
	}

	/**
	 * @description affiche le formulaire d'ajout d'un pays
	 */
	public function addAction(Request $request, Application $app)
	{
		if ( $request->getMethod() === 'POST' )
		{
			$nom = $request->get('nom');
			$description = $request->get('description');
			$impot = $request->get('impot');
			$richesse = $request->get('richesse');
			$histoire = $request->get('histoire');
			$capitale = $request->get('capitale');
			
			$pays = new \LarpManager\Entities\Pays();
			$pays->setNom($nom);
			$pays->setDescription($description);
			$pays->setImpot($impot);
			$pays->setRichesse($richesse);
			$pays->setHistoire($histoire);
			$pays->setCapitale($capitale);
				
			$app['orm.em']->persist($pays);
			$app['orm.em']->flush();
				
			return $app->redirect($app['url_generator']->generate('pays_list'));
		}
		return $app['twig']->render('pays/add.twig');
	}

	/**
	 * @description affiche le formulaire de modification d'une chrono
	 */
	public function modifyAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		$pays = $app['orm.em']->find('\LarpManager\Entities\Pays',$id);
		if(!$pays)
		{
			return $app->redirect($app['url_generator']->generate('pays_list'));
		}
		
		if ( $request->getMethod() === 'POST' )
		{
			$nom = $request->get('nom');
			$description = $request->get('description');
			$impot = $request->get('impot');
			$richesse = $request->get('richesse');
			$histoire = $request->get('histoire');
			$capitale = $request->get('capitale');
				
			$pays->setNom($nom);
			$pays->setDescription($description);
			$pays->setImpot($impot);
			$pays->setRichesse($richesse);
			$pays->setHistoire($histoire);
			$pays->setCapitale($capitale);
				
			$app['orm.em']->flush();
			//todo notif que ca s'est bien passe
			return $app->redirect($app['url_generator']->generate('pays_list'));
		}
		
		return $app['twig']->render('pays/modify.twig', array('pays' => $pays));
	}
	
	/**
	 * @description affiche le formulaire de suppresion d'une chrono
	 */
	public function removeAction(Request $request, Application $app)
	{
		$id = $request->get('index');

		$pays = $app['orm.em']->find('\LarpManager\Entities\Pays',$id);

		if ( $pays )
		{
			if ( $request->getMethod() === 'POST' )
			{
				
				$app['orm.em']->remove($pays); //je suppose un OnDelete cascade
				$app['orm.em']->flush();
				return $app->redirect($app['url_generator']->generate('pays_list'));
			}
			return $app['twig']->render('pays/remove.twig', array('pays' => $pays));
		}
		else
		{
			return $app->redirect($app['url_generator']->generate('pays_list'));
		}
	}

	/**
	 * @description affiche la détail d'une chronologie
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');

		$pays = $app['orm.em']->find('\LarpManager\Entities\Pays',$id);

		if ( $pays )
		{
			return $app['twig']->render('pays/detail.twig', array('pays',$pays));
		}
		else
		{
			return $app->redirect($app['url_generator']->generate('pays_list'));
		}
	}
}