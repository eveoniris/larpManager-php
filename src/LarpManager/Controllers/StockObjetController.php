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

use JasonGrimes\Paginator;
use LarpManager\Form\ObjetFindForm;
use LarpManager\Form\Stock\ObjetForm;
use LarpManager\Form\Stock\ObjetDeleteForm;
use LarpManager\Form\Stock\ObjetTagForm;

use LarpManager\Entities\Objet;

use LarpManager\Repository\ObjetRepository;
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
		$repoRangement = $app['orm.em']->getRepository('\LarpManager\Entities\Rangement');
		$rangements = $repoRangement->findAll();
		
		$repoTag = $app['orm.em']->getRepository('\LarpManager\Entities\Tag');
		$tags = $repoTag->findAll();

		$repoObjet = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');

        $objetsWithoutTagCount = $repoObjet->findCount(['tag' => ObjetRepository::CRIT_WITHOUT]);
		$objetsWithoutRangementCount = $repoObjet->findCount(['rangement' => ObjetRepository::CRIT_WITHOUT]);

        $criteria = [];

        // tag: null => no search based on tag;
        // tag: -1 or ObjetRepository::CRIT_WITHOUT => search object without
        // tag: [a-Z]+ => search object with this tag name
        $criteria['tag'] = $request->get('tag');

        // rangement: null => no search based on rangement;
        // rangement: -1 or ObjetRepository::CRIT_WITHOUT => search object without
        // rangement: [a-Z]+ => search object with this rangement name
        $criteria['rangement'] = $request->get('rangement');

        $order_by = $request->get('order_by', 'nom');
        $order_dir = $request->get('order_dir') === 'DESC' ? 'DESC' : 'ASC';
        $limit = (int) ($request->get('limit', 50));
        $page = (int) ($request->get('page', 1));
        $offset = ($page - 1) * $limit;

        $form = $app['form.factory']->createBuilder(new ObjetFindForm())->getForm();

        $form->handleRequest($request);

        if ( $form->isValid() ) {
            $data = $form->getData();
            $criteria[$data['type']] = $data['value'];
        }

        $objets = $repoObjet->findList(
            $criteria,
            ['by' =>  $order_by, 'dir' => $order_dir],
            $limit,
            $offset
        );

        $url = $app['url_generator']->generate('stock_objet_index');

		$paginator = new Paginator(
            $repoObjet->findCount($criteria),
			$limit,
			$page,
			$url . '?page=(:num)&' . http_build_query(
                [
                    'limit' => $limit,
                    'order_by' => $order_by,
                    'order_dir' => $order_dir,
                    'tag' => $criteria['tag'],
                    'rangement' => $criteria['rangement'],
                ]
            )
		);

		return $app['twig']->render('admin/stock/objet/list.twig', array(
				'objets' => $objets,
				'tag' => $criteria['tag'],
				'tags' => $tags,
                'form' => $form->createView(),
				'objetsWithoutTagCount' => $objetsWithoutTagCount,
				'objetsWithoutRangementCount' => $objetsWithoutRangementCount,
				'paginator' => $paginator,
				'rangements' => $rangements,
				'rangement' => $criteria['rangement'],
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
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		
		$qb = $repo->createQueryBuilder('o');
		$qb->select('o');
		$qb->where('o.proprietaire IS NULL');
		$objet_without_proprio = $qb->getQuery()->getResult();
		
		return $app['twig']->render('admin/stock/objet/listWithoutProprio.twig', array(
				'objets' => $objet_without_proprio,
			));
	}
	
	/**
	 * Fourni la liste des objets sans responsable
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function listWithoutResponsableAction(Request $request, Application $app)
	{	
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		
		$qb = $repo->createQueryBuilder('o');
		$qb->select('o');
		$qb->where('o.user IS NULL');
		$objet_without_responsable = $qb->getQuery()->getResult();
		
		return $app['twig']->render('admin/stock/objet/listWithoutResponsable.twig', array(
				'objets' => $objet_without_responsable,
		));
	}
	
	/**
	 * Fourni la liste des objets sans rangement
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function listWithoutRangementAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		
		$qb = $repo->createQueryBuilder('o');
		$qb->select('o');
		$qb->where('o.rangement IS NULL');
		$objet_without_rangement = $qb->getQuery()->getResult();
		
		return $app['twig']->render('admin/stock/objet/listWithoutRangement.twig', array(
				'objets' => $objet_without_rangement,
		));
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
				return $app->redirect($app['url_generator']->generate('stock_homepage'),303);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('stock_objet_add'),303);
			}
			else if ( $form->get('save_clone')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('stock_objet_clone', array('objet' => $objet->getId())),303);
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
				return $app->redirect($app['url_generator']->generate('stock_homepage'),303);
			}
			else
			{
				return $app->redirect($app['url_generator']->generate('stock_objet_clone', array('objet' => $newObjet->getId())),303);
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
					$objet->getPhoto()->upload($app);
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