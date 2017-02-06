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

use LarpManager\Form\Stock\ObjetForm;
use LarpManager\Form\Stock\ObjetDeleteForm;
use LarpManager\Form\Stock\ObjetTagForm;

use LarpManager\Entities\Objet;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * LarpManager\Controllers\StockObjetController
 *
 * @author kevin
 *
 */
class StockObjetController
{	
	/**
	 * Affiche la liste des objets
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{	
		$repoTag = $app['orm.em']->getRepository('\LarpManager\Entities\Tag');
		$tags = $repoTag->findAll();
		
		$repoObjet = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		
		$objetsWithoutTag = $repoObjet->findWithoutTag();
		$objetsWithoutTagCount = 0;
		if ( $objetsWithoutTag ) $objetsWithoutTagCount = count($objetsWithoutTag); 
		
			
		$tagId = $request->get('tag');
		$tag = null;
		if ( $tagId )
		{
			if ( $tagId == -1 ) // recherche les objets n'ayant pas de tag
			{
				$objets = $objetsWithoutTag;
			}
			else
			{
				$tag = $repoTag->find($tagId);
				if ( $tag )
				{
					$objets = $repoObjet->findByTag($tag);
				}
				else
				{
					$objets = $repoObjet->findAll();
				}
			}
		}
		else {
			$objets = $repoObjet->findAll();
		}

		return $app['twig']->render('admin/stock/objet/list.twig', array(
				'objets' => $objets,
				'tags' => $tags,
				'tag' => $tag,
				'tagId' => $tagId,
				'objetsWithoutTagCount' => $objetsWithoutTagCount,
		));
	}
	
	
	/**
	 * Fourni la liste des objets sans proprietaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function listWithoutProprioAction(Request $request, Application $app)
	{
	}
	
	/**
	 * Fourni la liste des objets sans responsable
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function listWithoutResponsableAction(Request $request, Application $app)
	{	
	}
	
	/**
	 * Fourni la liste des objets sans rangement
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function listWithoutRangementAction(Request $request, Application $app)
	{
	}

	/**
	 * Affiche la détail d'un objet
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app, Objet $objet)
	{	
		return $app['twig']->render('admin/stock/objet/detail.twig', array('objet' => $objet));
	}
	
	/**
	 * Fourni les données de la photo lié à l'objet
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function photoAction(Request $request, Application $app, Objet $objet)
	{
		$miniature = $request->get('miniature');
		$photo = $objet->getPhoto();
		
		if ( ! $photo ) {
			return null;
		}
		
		$file = $photo->getFilename();
		$filename = __DIR__.'/../../../private/stock/'.$file;
		
		if ( $miniature ) {
			$image = $app['imagine']->open($filename);
			
			$stream = function () use ($image) {
				$size = new \Imagine\Image\Box(200, 200);
				$thumbnail = $image->thumbnail($size);			
				ob_start(null,0, PHP_OUTPUT_HANDLER_FLUSHABLE|PHP_OUTPUT_HANDLER_REMOVABLE);
				echo $thumbnail->get('jpeg');
				ob_end_flush();
			};
		}
		else
		{
			$stream = function () use ($filename) {
				readfile($filename);
			};
		}

		return $app->stream($stream, 200, array(
				'Content-Type' => 'image/jpeg',
				'cache-control' => 'private'
		));
	}
	
	/**
	 * Ajoute un objet
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$objet = new \LarpManager\Entities\Objet();
		
		$objet->setNombre(1);
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etat = $repo->find(1);
		if ( $etat ) $objet->setEtat($etat);
		
		$form = $app['form.factory']->createBuilder(new ObjetForm(), $objet)
				->add('save','submit', array('label' => 'Sauvegarder et fermer'))
				->add('save_continue','submit',array('label' => 'Sauvegarder et nouveau'))
				->add('save_clone','submit',array('label' => 'Sauvegarder et cloner'))
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$objet = $form->getData();
			
			if ($objet->getObjetCarac() ) 
			{
				$app['orm.em']->persist($objet->getObjetCarac());
			}

			if ( $objet->getPhoto() )
			{
				$objet->getPhoto()->upload($app);
				$app['orm.em']->persist($objet->getPhoto());
			}
			
			/**$repo = $app['orm.em']->getRepository('\LarpManager\Entities\User');
			$user = $repo->find(1);
			$user->addObjetRelatedByCreateurId($objet);
			$objet->setUserRelatedByCreateurId($user);**/
			
			
			$app['orm.em']->persist($objet);				
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success','L\'objet a été ajouté dans le stock');
			
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('stock_homepage'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('stock_objet_add'),301);
			}
			else if ( $form->get('save_clone')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('stock_objet_clone', array('index' => $objet->getId())),301);
			}
			
		}
	
		return $app['twig']->render('admin/stock/objet/add.twig', array('form' => $form->createView()));
	}
	
	/**
	 * Créé un objet à partir d'un autre
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function cloneAction(Request $request, Application $app, Objet $objet)
	{
		$newObjet = clone($objet);
		
		$numero = $objet->getNumero();
		if ( $numero)
		{
			$newObjet->setNumero($numero + 1 );
		}
		
		$form = $app['form.factory']->createBuilder(new ObjetForm(), $newObjet)
			->add('save','submit', array('label' => 'Sauvegarder et fermer'))
			->add('save_clone','submit',array('label' => 'Sauvegarder et cloner'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$objet = $form->getData();

			if ($objet->getObjetCarac() ) 
			{
				$app['orm.em']->persist($objet->getObjetCarac());
			}

			if ( $objet->getPhoto() )
			{
				$objet->getPhoto()->upload($app);
				$app['orm.em']->persist($objet->getPhoto());
			}
				
			$app['orm.em']->persist($objet);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'L\'objet a été ajouté dans le stock');
				
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('stock_homepage'),301);
			}
			else
			{
				return $app->redirect($app['url_generator']->generate('stock_objet_clone', array('index' => $newObjet->getId())),301);
			}
		}
		
		return $app['twig']->render('admin/stock/objet/clone.twig', array('objet' => $newObjet, 'form' => $form->createView()));
	}
	
	/**
	 * Mise à jour un objet
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app, Objet $objet)
	{
		$form = $form = $app['form.factory']->createBuilder(new ObjetForm(), $objet)
				->add('update','submit', array('label' => "Sauvegarder et fermer"))
				->add('delete','submit', array('label' => "Supprimer"))
				->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$objet = $form->getData();
				
			if ($form->get('update')->isClicked()) 
			{					
				if ($objet->getObjetCarac() )
				{
					$app['orm.em']->persist($objet->getObjetCarac());
				}
				
				if ( $objet->getPhoto() )
				{
					$objet->getPhoto()->upload();
					$app['orm.em']->persist($objet->getPhoto());
				}

				$app['orm.em']->persist($objet);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'L\'objet a été mis à jour');
			}
			else if ($form->get('delete')->isClicked()) 
			{
				$app['orm.em']->remove($objet);
				$app['orm.em']->flush();
				
				$app['session']->getFlashBag()->add('success', 'L\'objet a été supprimé');
			}
			
			return $app->redirect($app['url_generator']->generate('stock_homepage'));
		}
	
		return $app['twig']->render('admin/stock/objet/update.twig', array('objet' => $objet, 'form' => $form->createView()));
	}
	
	/**
	 * Suppression d'un objet
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Objet $objet
	 */
	public function deleteAction(Request $request, Application $app, Objet $objet)
	{
		$form = $app['form.factory']->createBuilder(new ObjetDeleteForm(), $objet)->getForm();
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$objet = $form->getData();
			
			$app['orm.em']->remove($objet);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'L\'objet a été supprimé');
			return $app->redirect($app['url_generator']->generate('stock_objet_index'));
		}
		
		return $app['twig']->render('admin/stock/objet/delete.twig', array('objet' => $objet, 'form' => $form->createView()));
	}
	
	/**
	 * Modification des tags d'un objet
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Objet $objet
	 */
	public function tagAction(Request $request, Application $app, Objet $objet)
	{
		$form = $app['form.factory']->createBuilder(new ObjetTagForm(), $objet)->getForm();
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$objet = $form->getData();
			
			$newTags = $form['news']->getData();
			foreach ( $newTags as $tag )	
			{
				$objet->addTag($tag);
				$app['orm.em']->persist($tag);
			}
			$app['orm.em']->persist($objet);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'les tags ont été mis à jour');
			return $app->redirect($app['url_generator']->generate('stock_objet_index'));
		}
		
		return $app['twig']->render('admin/stock/objet/tag.twig', array('objet' => $objet, 'form' => $form->createView()));
	}
	
	/**
	 * Exporte la liste des objets au format CSV.
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function exportAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		$objets = $repo->findAll();
		
		
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=eveoniris_stock_".date("Ymd").".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$output = fopen("php://output", "w");
		
		// header
		fputcsv($output,
					array(
						'nom',
						'code',
						'description',
						'photo',
						'rangement',
						'etat',
						'proprietaire',
						'responsable',
						'nombre',
						'creation_date'), ',');
		
		foreach ($objets as $objet)
		{
			fputcsv($output, $objet->getExportValue(), ',');			
		}
		
		fclose($output);
		exit();
		
	}
	
	
}