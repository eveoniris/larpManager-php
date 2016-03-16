<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\Query;
use Silex\Application;
use LarpManager\Form\LangueForm;

/**
 * LarpManager\Controllers\LangueController
 *
 * @author kevin
 *
 */
class LangueController
{
	
	/**
	 * API: fourni la liste des langues
	 * GET /api/langue
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function apiListAction(Request $request, Application $app)
	{
		$qb = $app['orm.em']->createQueryBuilder();
		$qb->select('Langue')
			->from('\LarpManager\Entities\Langue','Langue');
	
		$query = $qb->getQuery();
	
		$langues = $query->getResult(Query::HYDRATE_ARRAY);
		return new JsonResponse($langues);
	}
	
	/**
	 * affiche la liste des langues
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Langue');
		$langues = $repo->findAllOrderedByLabel();
		
		return $app['twig']->render('langue/index.twig', array('langues' => $langues));
	}
	
	/**
	 * Detail d'une langue
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$langue = $app['orm.em']->find('\LarpManager\Entities\Langue',$id);
		
		return $app['twig']->render('langue/detail.twig', array('langue' => $langue));
	}
	
	/**
	 * Ajoute une langue
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$langue = new \LarpManager\Entities\Langue();
		
		$form = $app['form.factory']->createBuilder(new LangueForm(), $langue)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);

		// si l'utilisateur soumet une nouvelle langue
		if ( $form->isValid() )
		{
			$langue = $form->getData();
			$app['orm.em']->persist($langue);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La langue a été ajoutée.');
				
			// l'utilisateur est redirigé soit vers la liste des langues, soit vers de nouveau
			// vers le formulaire d'ajout d'une langue
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('langue'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('langue.add'),301);
			}
		}
		
		return $app['twig']->render('langue/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modifie une langue. Si l'utilisateur clique sur "sauvegarder", la langue est sauvegardée et
	 * l'utilisateur est redirigé vers la liste des langues. 
	 * Si l'utilisateur clique sur "supprimer", la langue est supprimée et l'utilisateur est
	 * redirigé vers la liste des langues. 
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{	
		$id = $request->get('index');
		
		$langue = $app['orm.em']->find('\LarpManager\Entities\Langue',$id);
		
		$form = $app['form.factory']->createBuilder(new LangueForm(), $langue)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$langue = $form->getData();
		
			if ( $form->get('update')->isClicked())
			{
				$app['orm.em']->persist($langue);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La langue a été mise à jour.');
		
				return $app->redirect($app['url_generator']->generate('langue.detail',array('index' => $id)),301);
			}
			else if ( $form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($langue);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La langue a été supprimée.');
				return $app->redirect($app['url_generator']->generate('langue'),301);
			}
		}		

		return $app['twig']->render('langue/update.twig', array(
				'langue' => $langue,
				'form' => $form->createView(),
		));
	}
}