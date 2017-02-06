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

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

use Silex\Application;

use LarpManager\Entities\Objet;
use LarpManager\Entities\Item;
use LarpManager\Form\Item\ItemForm;

/**
 * LarpManager\Controllers\ObjetController
 *
 * @author kevin
 *
 */
class ObjetController
{
	/**
	 * Présentation des objets de jeu
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Item');
		$items = $repo->findAll();
		
		return $app['twig']->render('admin/objet/index.twig', array(
				'items' => $items,
		));
	}
	
	/**
	 * Création d'un nouvel objet de jeu
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Objet $objet
	 */
	public function newAction(Request $request, Application $app, Objet $objet)
	{
		$item = new Item();
		$item->setObjet($objet);
		
		$form = $app['form.factory']->createBuilder(new ItemForm(), $item)->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$item = $form->getData();
			
			// si le numéro est vide, générer un numéro en suivant l'ordre
			$numero = $item->getNumero();
			if ( ! $numero )
			{
				$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Item');
				$numero = $repo->findNextNumero();
				if ( ! $numero ) $numero = 0;
				$item->setNumero($numero);
			}
			
			// en fonction de l'identification choisie, choisir un numéro d'identification
			$identification = $item->getIdentification();
			switch ($identification){
				case 1:
					$identification = mt_rand(1,10);
					$item->setIdentification($identification);
				case 11:
					$identification = mt_rand(11,20);
					$item->setIdentification($identification);
				case 81:
					$identification = mt_rand(81,99);
					$item->setIdentification($identification);
			}

			$app['orm.em']->persist($item);	
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'L\'objet de jeu a été créé');
			return $app->redirect($app['url_generator']->generate('objet'),301);
		}
		
		return $app['twig']->render('admin/objet/new.twig', array(
			'objet' => $objet,
			'item' => $item,
			'form' => $form->createView(),
		));
	}
	
	
}