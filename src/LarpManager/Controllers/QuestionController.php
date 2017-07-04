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
use LarpManager\Entities\Question;
use LarpManager\Form\Question\QuestionForm;
use LarpManager\Form\Question\QuestionDeleteForm;

class QuestionController
{
	/**
	 * Liste des question
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$questions = $app['orm.em']->getRepository('LarpManager\Entities\Question')->findAll();
		
		return $app['twig']->render('admin\question\index.twig', array(
			'questions' => $questions,	
		));
	}
	
	/**
	 * Ajout d'une question
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{				
		$form = $app['form.factory']->createBuilder(new QuestionForm(),new Question())->getForm();
		$form->handleRequest($request);
				
		if ( $form->isValid() )
		{
			$question = $form->getData();
			$question->setUser($app['user']);
			$question->setDate(new \Datetime('NOW'));

			$app['orm.em']->persist($question);
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La question a été ajoutée.');
			return $app->redirect($app['url_generator']->generate('question'),301);
		}
		
		return $app['twig']->render('admin\question\add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Détail d'une question
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function detailAction(Request $request, Application $app, Question $question)
	{
		return $app['twig']->render('admin\question\detail.twig', array(
				'question' => $question,
		));
	}
	
	/**
	 * Mise à jour d'une question
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function updateAction(Request $request, Application $app, Question $question)
	{
		$form = $app['form.factory']->createBuilder(new QuestionForm(), $question)
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$question = $form->getData();

			$app['orm.em']->persist($question);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La question a été mise à jour.');
			return $app->redirect($app['url_generator']->generate('question'),301);
		}
		
		return $app['twig']->render('admin\question\update.twig', array(
				'form' => $form->createView(),
				'question' => $question,
		));
	}
	
	/**
	 * Suppression d'une question
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Billet $billet
	 */
	public function deleteAction(Request $request, Application $app, Question $question)
	{
		$form = $app['form.factory']->createBuilder(new QuestionDeleteForm(), $question)
			->add('submit', 'submit', array('label' => 'Supprimer'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$question = $form->getData();
			
			$app['orm.em']->remove($question);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'La question a été supprimée.');
			return $app->redirect($app['url_generator']->generate('question'),301);
		}
		
		return $app['twig']->render('admin\question\delete.twig', array(
				'form' => $form->createView(),
				'question' => $question,
		));
	}
}