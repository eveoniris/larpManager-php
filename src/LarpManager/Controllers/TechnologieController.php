<?php

/**
 * LarpManager - A Live Action Role Playing Manager
 * Copyright (C) 2016 Kevin Polez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace LarpManager\Controllers;

use LarpManager\Entities\TechnologiesRessources;
use LarpManager\Form\Technologie\TechnologiesRessourcesForm;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use LarpManager\Entities\Technologie;
use LarpManager\Form\Technologie\TechnologieForm;
use LarpManager\Form\Technologie\TechnologieDeleteForm;

class TechnologieController
{
	/**
	 * Liste des technologie
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$technologies = $app['orm.em']->getRepository('LarpManager\Entities\Technologie')->findAll();
		
		return $app['twig']->render('admin\technologie\index.twig', array(
			'technologies' => $technologies,	
		));
	}
	
	/**
	 * Ajout d'une technologie
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{				
		$form = $app['form.factory']->createBuilder(new TechnologieForm(),new Technologie())->getForm();

		$form->handleRequest($request);
				
		if ( $form->isValid() && $form->isSubmitted() )
		{
			$technologie = $form->getData();
			
			$app['orm.em']->persist($technologie);
            $app['orm.em']->flush();
            $app['session']->getFlashBag()->add('success', 'La technologie a été ajoutée.');
            return $app->redirect($app['url_generator']->generate('technologie'),303);
		}
		
		return $app['twig']->render('admin\technologie\add.twig', array(
            'form' => $form->createView(),
		));
	}

    /**
     * Détail d'une technologie
     *
     * @param Request $request
     * @param Application $app
     * @param Technologie $technologie
     * @return mixed
     */
	public function detailAction(Request $request, Application $app, Technologie $technologie)
	{
		return $app['twig']->render('admin\technologie\detail.twig', array(
				'technologie' => $technologie,
		));
	}

    /**
     * Mise à jour d'une technologie
     *
     * @param Request $request
     * @param Application $app
     * @param Technologie $technologie
     */
	public function updateAction(Request $request, Application $app, Technologie $technologie)
	{
		$form = $app['form.factory']->createBuilder(new TechnologieForm(), $technologie)
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$technologie = $form->getData();
			
			$app['orm.em']->persist($technologie);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La technologie a été mise à jour.');
			return $app->redirect($app['url_generator']->generate('technologie'),303);
		}
		
		return $app['twig']->render('admin\technologie\update.twig', array(
				'form' => $form->createView(),
				'technologie' => $technologie,
		));
	}

    /**
     * Suppression d'une technologie
     *
     * @param Request $request
     * @param Application $app
     * @param Technologie $technologie
     */
	public function deleteAction(Request $request, Application $app, Technologie $technologie)
	{
		$form = $app['form.factory']->createBuilder(new TechnologieDeleteForm(), $technologie)
			->add('submit', 'submit', array('label' => 'Supprimer'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$technologie = $form->getData();
			
			$app['orm.em']->remove($technologie);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La technologie a été supprimée.');
			return $app->redirect($app['url_generator']->generate('technologie'),303);
		}
		
		return $app['twig']->render('admin\technologie\delete.twig', array(
				'form' => $form->createView(),
				'technologie' => $technologie,
		));
	}

	/**
	 * Liste des personnages ayant cette technologie
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Technologie
	 */
	public function personnagesAction(Request $request, Application $app, Technologie $technologie)
	{
	    $routeName = 'technologie.personnages';
	    $routeParams = array('technologie' => $technologie->getId());
	    $twigFilePath = 'admin/technologie/personnages.twig';
	    $columnKeys = ['colId', 'colStatut', 'colNom', 'colClasse', 'colGroupe', 'colUser'];
	    $personnages =  $technologie->getPersonnages();
	    $additionalViewParams = array(
	        'technologie' => $technologie
	    );
	    
	    // handle the request and return an array containing the parameters for the view
	    $personnageSearchHandler = $app['personnage.manager']->getSearchHandler();
	    
	    $viewParams = $personnageSearchHandler->getSearchViewParameters(
	        $request,
	        $routeName,
	        $routeParams,
	        $columnKeys,
	        $additionalViewParams,
	        $personnages
	        );
	    
	    return $app['twig']->render(
	        $twigFilePath,
	        $viewParams
	        );
	    
	}

    /**
     * Ajout d'une ressource à une technologie
     * @param Request $request
     * @param Application $app
     */
    public function addRessourceAction(Request $request, Application $app)
    {
        $technologieId = $request->get('technologie');
        $technologie = $app['orm.em']->find(Technologie::class,$technologieId);

        $form = $app['form.factory']->createBuilder(new TechnologiesRessourcesForm(),new TechnologiesRessources())->getForm();

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted())
        {
            $ressource = $form->getData();
            $ressource->setTechnologie($technologie);

            $app['orm.em']->persist($ressource);
            $app['orm.em']->flush();
            $app['session']->getFlashBag()->add('success', 'La ressource a été ajoutée.');
            return $app->redirect($app['url_generator']->generate('technologie') ,303);
        }

        return $app['twig']->render('admin\technologie\addRessource.twig', array(
            'technologie' => $technologieId,
            'form' => $form->createView(),
        ));
    }
}