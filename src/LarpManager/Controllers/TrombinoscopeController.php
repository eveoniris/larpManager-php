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
use JasonGrimes\Paginator;

use Doctrine\Common\Collections\ArrayCollection;
use LarpManager\Form\Trombinoscope\TrombinoscopeForm;

/**
 * LarpManager\Controllers\TrombinoscopeController
 *
 * @author kevin
 *
 */
class TrombinoscopeController
{
	/**
	 * Le trombinoscope général
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{				
		$gnRepo = $app['orm.em']->getRepository('\LarpManager\Entities\Gn');
		$gn = $gnRepo->findNext();
			
		$form = $app['form.factory']->createBuilder(new TrombinoscopeForm())->getForm();
		
		$form->handleRequest($request);
		
		$renomme = 0;
		$territoire = null;
		$competence = null;
		$classe = null;
		$religion = null;
		$language = null;
		$groupe = null;
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			if ( $data['renomme'] )
			{
				$renomme = $data['renomme'];
			}
			if ( $data['territoire'])
			{
				$territoire = $data['territoire'];
			}
			if ( $data['classe'])
			{
				$classe = $data['classe'];
			}
				
			if ( $data['competence'])
			{
				$competence = $data['competence'];
			}
			
			if ( $data['religion'])
			{
				$religion = $data['religion'];
			}
			
			if ( $data['language'])
			{
				$language = $data['language'];
			}
			
			if ( $data['groupe'])
			{
				$groupe = $data['groupe'];
			}
		}
		$participants = new ArrayCollection();
		foreach ( $gn->getParticipants() as $participant)
		{
			if ( $participant->getPersonnage() 
				&& $participant->getPersonnage()->getRenomme() >= $renomme
				&& ( ! $territoire || ($participant->getGroupeGn() && $territoire == $participant->getGroupeGn()->getGroupe()->getTerritoire() ) )
				&& ( ! $classe || ($participant->getPersonnage()->getClasse() == $classe) )
				&& ( ! $competence || ( $participant->getPersonnage()->isKnownCompetence($competence)  ) ) 
				&& ( ! $religion || ( $participant->getPersonnage()->isKnownReligion($religion)  ) ) 
				&& ( ! $language || ( $participant->getPersonnage()->isKnownLanguage($language)  ) ) 
				&& ( ! $groupe || ( $participant->getGroupeGn() && $groupe ==  $participant->getGroupeGn()->getGroupe() ) ) )
				$participants[] = $participant;
		}
		
		return $app['twig']->render('admin/trombinoscope.twig', array(
				'gn' => $gn,
				'participants' => $participants,
				'form' => $form->createView(),
				'renomme' => $renomme,
				'territoire' => $territoire,
				'classe' => $classe,
				'competence' => $competence,
				'religion' => $religion,
				'language' => $language,
				'groupe' => $groupe,
		));
	}
	
	/**
	 * Permet de selectionner des personnages pour faire un trombinoscope
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function persoAction(Request $request, Application $app)
	{
		$personnages = null;
		$titre = null;
		
		$form = $app['form.factory']->createBuilder()
			->add('titre', 'text', array(
					'label' => 'Le titre de votre sélection',
			))
			->add('ids', 'textarea', array(
					'label' => 'Indiquez les numéros des personnages séparé d\'un espace',
			))
			->add('send','submit', array('label' => 'Envoyer'))
			->add('print','submit', array('label' => 'Imprimer'))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			$titre = $data['titre'];
			$ids = $data['ids'];
			$ids = explode(' ',$ids);
			$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Personnage');
			$personnages = $repo->findByIds($ids);
			
			if ( $form->get('print')->isClicked())
			{
				return $app['twig']->render('admin/trombinoscopePersoPrint.twig', array(
						'titre' => $titre,
						'personnages' => $personnages,
				));
			}
		}
		
		return $app['twig']->render('admin/trombinoscopePerso.twig', array(
			'titre' => $titre,
			'personnages' => $personnages,
			'form' => $form->createView(),
		));
	}
}