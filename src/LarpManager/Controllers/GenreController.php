<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

use LarpManager\Form\GenreForm;

/**
 * LarpManager\Controllers\GenreController
 *
 * @author kevin
 *
 */
class GenreController
{
	/**
	 * Présentation des genres
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Genre');
		$genres = $repo->findAll();
		return $app['twig']->render('genre/index.twig', array('genres' => $genres));
	}
	
	/**
	 * Ajout d'un genre
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$genre = new \LarpManager\Entities\Genre();
	
		$form = $app['form.factory']->createBuilder(new GenreForm(), $genre)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$genre = $form->getData();
				
			$app['orm.em']->persist($genre);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Le genre a été ajouté.');
	
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('genre'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('genre.add'),301);
			}
		}
	
		return $app['twig']->render('genre/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Detail d'un genre
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$genre = $app['orm.em']->find('\LarpManager\Entities\Genre',$id);
	
		if ( $genre )
		{
			return $app['twig']->render('genre/detail.twig', array('genre' => $genre));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le genre n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('genre'));
		}	
	}
	
	/**
	 * Met à jour un genre
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$genre = $app['orm.em']->find('\LarpManager\Entities\Genre',$id);
	
		$form = $app['form.factory']->createBuilder(new GenreForm(), $genre)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$genre = $form->getData();
	
			if ($form->get('update')->isClicked())
			{	
				$app['orm.em']->persist($genre);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le genre a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($genre);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le genre a été supprimé.');
			}
	
			return $app->redirect($app['url_generator']->generate('genre'));
		}
	
		return $app['twig']->render('genre/update.twig', array(
				'genre' => $genre,
				'form' => $form->createView(),
		));
	}
}