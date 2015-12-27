<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Form\PnjInscriptionForm;

/**
 * LarpManager\Controllers\PnjController
 *
 * @author kevin
 *
 */
class PnjController
{
	/**
	 * 
	 */
	public function inscriptionAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new PnjInscriptionForm())
			->add('subscribe','submit', array('label' => 'S\'inscrire'))
			->getForm();
		
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			
		}
		
		return $app['twig']->render('public/pnj/inscription.twig', array(
				'form' => $form->createView(),
		));
	}
}
