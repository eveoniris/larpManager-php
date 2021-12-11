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
use Silex\Application;
use LarpManager\Form\GroupeSecondaireTypeForm;

/**
 * LarpManager\Controllers\GroupeSecondaireTypeController
 *
 * @author kevin
 *
 */
class GroupeSecondaireTypeController
{
	/**
	 * Liste les types de groupe secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminListAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\SecondaryGroupType');
		$groupeSecondaireTypes = $repo->findAll();
		return $app['twig']->render('admin/groupeSecondaireType/list.twig', array('groupeSecondaireTypes' => $groupeSecondaireTypes));
	}
	
	/**
	 * Ajoute un type de groupe secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminAddAction(Request $request, Application $app)
	{
		$groupeSecondaireType = new \LarpManager\Entities\SecondaryGroupType();
		
		$form = $app['form.factory']->createBuilder(new GroupeSecondaireTypeForm(), $groupeSecondaireType)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$groupeSecondaireType = $form->getData();
		
			$app['orm.em']->persist($groupeSecondaireType);
			$app['orm.em']->flush();
		
			$app['session']->getFlashBag()->add('success', 'Le type de groupe secondaire a été ajouté.');
		
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupeSecondaire.admin.list'),303);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('groupeSecondaire.admin.type.add'),303);
			}
		}
		
		return $app['twig']->render('admin/groupeSecondaireType/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour un type de groupe secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminUpdateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupeSecondaireType = $app['orm.em']->find('\LarpManager\Entities\SecondaryGroupType',$id);
		
		$form = $app['form.factory']->createBuilder(new GroupeSecondaireTypeForm(), $groupeSecondaireType)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$groupeSecondaireType = $form->getData();
		
			if ($form->get('update')->isClicked())
			{
				$app['orm.em']->persist($groupeSecondaireType);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le type de groupe secondaire a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($groupeSecondaireType);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le type de groupe secondaire a été supprimé.');
			}
		
			return $app->redirect($app['url_generator']->generate('groupeSecondaire.admin.type.list'));
		}
				
		return $app['twig']->render('admin/groupeSecondaireType/update.twig', array(
				'groupeSecondaireType' => $groupeSecondaireType,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Detail d'un type de groupe secondaire
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function adminDetailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$groupeSecondaireType = $app['orm.em']->find('\LarpManager\Entities\SecondaryGroupType',$id);
		
		if ( $groupeSecondaireType )
		{
			return $app['twig']->render('admin/groupeSecondaireType/detail.twig', array('groupeSecondaireType' => $groupeSecondaireType));
		}
		else
		{
			$app['session']->getFlashBag()->add('error', 'Le type de groupe secondaire n\'a pas été trouvé.');
			return $app->redirect($app['url_generator']->generate('groupeSecondaire.admin.type.list'));
		}
	}
	
}