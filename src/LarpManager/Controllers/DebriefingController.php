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

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use JasonGrimes\Paginator;

use LarpManager\Entities\Debriefing;
use LarpManager\Form\Debriefing\DebriefingForm;
use LarpManager\Form\Debriefing\DebriefingFindForm;
use LarpManager\Form\Debriefing\DebriefingDeleteForm;
use LarpManager\Entities\Groupe;


/**
 * LarpManager\Controllers\DebriefingController
 *
 * @author kevin
 *
 */
class DebriefingController
{
    public const DOC_PATH = __DIR__.'/../../../private/doc/';

	/**
	 * Présentation des debriefings
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$order_by = $request->get('order_by') ?: 'id';
		$order_dir = $request->get('order_dir') === 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		$criteria = array();
		
		$form = $app['form.factory']->createBuilder(new DebriefingFindForm())
			->getForm();
		
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			// TODO
			/*$data = $form->getData();
			$type = $data['type'];
			$value = $data['value'];
			switch ($type){
				case 'Auteur':
					$criteria[] = "g.nom LIKE '%$value%'";
					break;
				case 'Groupe':
					$criteria[] = "u.name LIKE '%$value%'";
					break;
			}*/
		}
	
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Debriefing');
		$debriefings = $repo->findBy(
				$criteria,
				array( $order_by => $order_dir),
				$limit,
				$offset);
	
		$numResults = $repo->findCount($criteria);
	
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('debriefing.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
	
		return $app['twig']->render('admin/debriefing/list.twig', array(
				'debriefings' => $debriefings,
				'paginator' => $paginator,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Ajout d'un debriefing
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$debriefing = new Debriefing;
		$groupeId = $request->get('groupe');
		
		if ( $groupeId )
		{
			$groupe = $app['orm.em']->find(Groupe::class, $groupeId);
			if ( $groupe ) {
                $debriefing->setGroupe($groupe);
            }
		}
		
		$form = $app['form.factory']->createBuilder(new DebriefingForm(), $debriefing)
			->add('visibility','choice', array(
					'required' => true,
					'label' =>  'Visibilité',
					'choices' => $app['larp.manager']->getVisibility(),
			))
			->add('save','submit', array('label' => 'Sauvegarder'))
			->getForm();
			
		$form->handleRequest($request);
			
		if ( $form->isValid() && $form->isSubmitted() )
		{
			$debriefing = $form->getData();
			$debriefing->setUser($app['user']);

            if ($this->handleDocument($request, $app, $form, $debriefing)){

                $app['orm.em']->persist($debriefing);
                $app['orm.em']->flush();

                $app['session']->getFlashBag()->add('success', 'Le debriefing a été ajouté.');

            }
			return $app->redirect($app['url_generator']->generate('groupe.detail', array('index' => $debriefing->getGroupe()->getId())),303);
		}
		
		return $app['twig']->render('admin/debriefing/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Suppression d'un debriefing
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Debriefing $debriefing
	 */
	public function deleteAction(Request $request, Application $app, Debriefing $debriefing)
	{
		$form = $app['form.factory']->createBuilder(new DebriefingDeleteForm(), $debriefing)
			->add('save','submit', array('label' => 'Supprimer'))
			->getForm();
			
		$form->handleRequest($request);
			
		if ( $form->isValid() )
		{
			$debriefing = $form->getData();
			$app['orm.em']->remove($debriefing);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'Le debriefing a été supprimé.');
			return $app->redirect($app['url_generator']->generate('groupe.detail', array('index' => $debriefing->getGroupe()->getId())),303);
		}
		
		return $app['twig']->render('admin/debriefing/delete.twig', array(
				'form' => $form->createView(),
				'debriefing' => $debriefing
		));
	}
	
	/**
	 * Mise à jour d'un debriefing
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app, Debriefing $debriefing)
    {
        $form = $app['form.factory']->createBuilder(new DebriefingForm(), $debriefing)
            ->add('visibility', 'choice', array(
                'required' => true,
                'label' => 'Visibilité',
                'choices' => $app['larp.manager']->getVisibility(),
            ))
            ->add('save', 'submit', array('label' => 'Sauvegarder'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $debriefing = $form->getData();

            if ($this->handleDocument($request, $app, $form, $debriefing)) {

                $app['orm.em']->persist($debriefing);
                $app['orm.em']->flush();

                $app['session']->getFlashBag()->add('success', 'Le debriefing a été modifié.');
                return $app->redirect($app['url_generator']->generate('groupe.detail', array('index' => $debriefing->getGroupe()->getId())), 303);
            }
        }
            return $app['twig']->render('admin/debriefing/update.twig', array(
                'form' => $form->createView(),
                'debriefing' => $debriefing
            ));
    }

	
	/**
	 * Détail d'un debriefing
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app, Debriefing $debriefing)
	{		
		return $app['twig']->render('admin/debriefing/detail.twig', array(
				'debriefing' => $debriefing
		));
	}

    /**
     * Gère le document uploadé et renvoie true si il est valide, false sinon
     *
     * @param Request $request
     * @param Application $app
     * @param Form $form
     * @param Debriefing $debriefing
     * @return bool
     */
    private function handleDocument(Request $request, Application $app, Form $form, Debriefing $debriefing) : bool
    {
        $files = $request->files->get($form->getName());
        $documentFile = $files['document'];
        // Si un document est fourni, l'enregistrer
        if ($documentFile !== null )
        {
            $filename = $documentFile->getClientOriginalName();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            if ($extension !== 'pdf') {
                $app['session']->getFlashBag()->add('error','Désolé, votre document n\'est pas valide. Vérifiez le format de votre document ('.$extension.'), seuls les .pdf sont acceptés.');
                return false;
            }

            $documentFilename = hash('md5',$debriefing->getTitre().$filename . time()).'.'.$extension;

            $documentFile->move(self::DOC_PATH,$documentFilename);

            // delete previous language document if it exists
            $this->tryDeleteDocument($debriefing);

            $debriefing->setDocumentUrl($documentFilename);
        }
        return true;
    }

    /**
     * Supprime le document spécifié, en cas d'erreur, ne fait rien pour le moment
     *
     * @param Debriefing $debriefing
     */
    private function tryDeleteDocument(Debriefing $debriefing): void
    {
        try
        {
            if (!empty($debriefing->getDocumentUrl()))
			{
				$docFilePath = self::DOC_PATH.$debriefing->getDocumentUrl();
				unlink($docFilePath);
			}
        }
        catch (FileException $e)
        {
            // for now, simply ignore
        }
    }
}