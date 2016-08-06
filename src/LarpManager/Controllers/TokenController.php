<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Entities\Token;
use LarpManager\Form\TokenForm;
use LarpManager\Form\TokenDeleteForm;

/**
 * LarpManager\Controllers\TokenController
 *
 * @author kevin
 *
 */
class TokenController
{
	/**
	 * Liste des tokens
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$tokens = $app['orm.em']->getRepository('\LarpManager\Entities\Token')->findAllOrderedByLabel();
		
		return $app['twig']->render('admin/token/list.twig', array('tokens' => $tokens));
	}
	
	/**
	 * Impression des tokens
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function printAction(Request $request, Application $app)
	{
		$tokens = $app['orm.em']->getRepository('\LarpManager\Entities\Token')->findAllOrderedByLabel();
	
		return $app['twig']->render('admin/token/print.twig', array('tokens' => $tokens));
	}
	
	/**
	 * Téléchargement des tokens
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function downloadAction(Request $request, Application $app)
	{
		$tokens = $app['orm.em']->getRepository('\LarpManager\Entities\Token')->findAllOrderedByLabel();
		
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=eveoniris_tokens_".date("Ymd").".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$output = fopen("php://output", "w");
		
		// header
		fputcsv($output,
			array(
			'id',
			'label',
			'tag',
			'description'), ';');
		
		foreach ($tokens as $token)
		{
			fputcsv($output, $token->getExportValue(), ';');
		}
		
		fclose($output);
		exit();
	}
	
	/**
	 * Ajouter un token
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{		
		$token = new Token();
		
		$form = $app['form.factory']->createBuilder(new TokenForm(), $token)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$token = $form->getData();
			
			$app['orm.em']->persist($token);
			$app['orm.em']->flush($token);
			
			$app['session']->getFlashBag()->add('success', 'Le jeton a été ajouté.');
			
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('token.list'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('token.add'),301);
			}
		}
		
		return $app['twig']->render('admin/token/add.twig', array(
				'token' => $token,
				'form' => $form->createView(),
		));
		
	}
	
	/**
	 * Détail d'un token
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app, Token $token)
	{
		return $app['twig']->render('admin/token/detail.twig', array('token' => $token));
	}
	
	/**
	 * Mise à jour d'un token
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app, Token $token)
	{		
		$form = $app['form.factory']->createBuilder(new TokenForm(), $token)
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$token = $form->getData();
				
			$app['orm.em']->persist($token);
			$app['orm.em']->flush($token);
				
			$app['session']->getFlashBag()->add('success', 'Le jeton a été modifié.');
				
			return $app->redirect($app['url_generator']->generate('token.list'),301);
		}
		return $app['twig']->render('admin/token/update.twig', array(
				'token' => $token,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Suppression d'un token
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function deleteAction(Request $request, Application $app, Token $token)
	{
		$form = $app['form.factory']->createBuilder(new TokenDeleteForm(), $token)
			->add('save','submit', array('label' => "Supprimer", 'attr' => array('class' => 'btn-danger')))
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$app['orm.em']->remove($token);
			$app['orm.em']->flush($token);
			
			$app['session']->getFlashBag()->add('success', 'Le jeton a été supprimé.');
			
			return $app->redirect($app['url_generator']->generate('token.list'),301);
		}
		
		return $app['twig']->render('admin/token/delete.twig', array(
				'token' => $token,
				'form' => $form->createView(),
		));
		
	}
}