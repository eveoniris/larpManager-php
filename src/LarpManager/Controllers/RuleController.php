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

use LarpManager\Entities\Rule;
use LarpManager\Form\Rule\RuleForm;
use LarpManager\Form\Rule\RuleDeleteForm;
use LarpManager\Form\Rule\RuleUpdateForm;


/**
 * LarpManager\Controllers\RuleController
 *
 * @author kevin
 *
 */
class RuleController
{
	/**
	 * Page de gestion des règles
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$regles = $app['orm.em']->getRepository('LarpManager\Entities\Rule')->findAll();
	
		return $app['twig']->render('rule/list.twig', array(
				'regles' => $regles,
		));
	
	}
	
	/**
	 * Ajout d'une règle
	 */
	public function addAction(Request $request, Application $app)
	{
		$form = $app['form.factory']->createBuilder(new RuleForm(), array())
			->add('envoyer','submit', array('label' => 'Envoyer'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$files = $request->files->get($form->getName());
				
			$path = __DIR__.'/../../../private/rules/';
			$filename = $files['rule']->getClientOriginalName();
			$extension = $files['rule']->guessExtension();
				
			if (!$extension || ! in_array($extension, array('pdf'))) {
				$app['session']->getFlashBag()->add('error','Désolé, votre fichier ne semble pas valide (vérifiez le format de votre fichier)');
				return $app->redirect($app['url_generator']->generate('rules'),301);
			}
				
			$ruleFilename = hash('md5',$app['user']->getUsername().$filename . time()).'.'.$extension;
	
				
			$files['rule']->move($path, $filename);
				
			$rule = new \LarpManager\Entities\Rule();
			$rule->setLabel($data['label']);
			$rule->setDescription($data['description']);
			$rule->setUrl($filename);
				
			$app['orm.em']->persist($rule);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Votre fichier a été enregistrée');
		}
	
		return $app['twig']->render('rule/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Détail d'une règle
	 */
	public function detailAction(Request $request, Application $app, Rule $rule)
	{
		return $app['twig']->render('rule/detail.twig', array(
				'rule' => $rule,
		));
	}
	
	/**
	 * Mise à jour d'une règle
	 */
	public function updateAction(Request $request, Application $app, Rule $rule)
	{
		$form = $app['form.factory']->createBuilder(new RuleUpdateForm(), $rule)
			->add('envoyer','submit', array('label' => 'Envoyer'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$rule = $form->getData();	
			$app['orm.em']->persist($rule);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success','Vos modifications été enregistrées');
		}
	
		return $app['twig']->render('rule/update.twig', array(
				'form' => $form->createView(),
				'rule' => $rule,
		));
	}
	
	/**
	 * Supression d'un fichier de règle
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function deleteAction(Request $request, Application $app, Rule $rule)
	{
		$form = $app['form.factory']->createBuilder(new RuleDeleteForm(), $rule)
			->add('supprimer','submit', array('label' => 'Supprimer'))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$app['orm.em']->remove($rule);
			$app['orm.em']->flush();
	
			$filename = __DIR__.'/../../../private/rules/'.$rule->getUrl();
	
			if ( file_exists($filename))
			{
				unlink($filename);
				$app['session']->getFlashBag()->add('success','suppresion du fichier ' . $filename);
			}
			else
			{
				$app['session']->getFlashBag()->add('error','impossible de supprimer le fichier ' . $filename);
			}
		
			return $app->redirect($app['url_generator']->generate('rules'),301);
		}
		
		return $app['twig']->render('rule/delete.twig', array(
				'form' => $form->createView(),
				'rule' => $rule,
		));
	}
	
	/**
	 * Télécharger une règle
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function documentAction(Request $request, Application $app, Rule $rule)
	{
		$filename = __DIR__.'/../../../private/rules/'.$rule->getUrl();
		return $app->sendFile($filename);
	}
}