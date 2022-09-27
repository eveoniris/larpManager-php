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
use LarpManager\Services\RandomColor\RandomColor;

/**
 * LarpManager\Controllers\StatistiqueController
 *
 * @author kevin
 *
 */
class StatistiqueController
{
	
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('LarpManager\Entities\Langue');
		$langues = $repo->findAll();
		$stats = array();
		foreach($langues as $langue)
		{
			$colors = RandomColor::many(2, array(
					'luminosity' => array('light', 'bright'),
					'hue' => 'random'
			));
			
			$stats[] = array(
				'value' => $langue->getTerritoires()->count(),
				'color' => $colors[0],
				'highlight' => $colors[1],
				'label' => $langue->getLabel(),
			);
		}
		
		$repo = $app['orm.em']->getRepository('LarpManager\Entities\Classe');
		$classes = $repo->findAll();
		$statClasses = array();
		foreach($classes as $classe)
		{
			$colors = RandomColor::many(2, array(
					'luminosity' => array('light', 'bright'),
					'hue' => 'random'
			));
			$statClasses[] = array(
					'value' => $classe->getPersonnages()->count(),
					'color' => $colors[0],
					'highlight' => $colors[1],
					'label' => $classe->getLabel(),
			);
		}
		
		$repo = $app['orm.em']->getRepository('LarpManager\Entities\Construction');
		$constructions = $repo->findAll();
		$statConstructions = array();
		foreach($constructions as $construction)
		{
			$colors = RandomColor::many(2, array(
					'luminosity' => array('light', 'bright'),
					'hue' => 'random'
			));
			$statConstructions[] = array(
					'value' => $construction->getTerritoires()->count(),
					'color' => $colors[0],
					'highlight' => $colors[1],
					'label' => $construction->getLabel(),
			);
		}

		$repo = $app['orm.em']->getRepository('LarpManager\Entities\Competence');
		$competences = $repo->findAllOrderedByLabel();
		$statCompetences = array();
		$statCompetencesFamily = array();
		$valueFamily = 0;
		$previousFamily = '';
		foreach($competences as $competence)
		{
			$colors = RandomColor::many(2, array(
					'luminosity' => array('light', 'bright'),
					'hue' => 'random'
			));
			$statCompetences[] = array(
					'value' => $competence->getPersonnages()->count(),
					'color' => $colors[0],
					'highlight' => $colors[1],
					'label' => $competence->getCompetenceFamily()->getLabel() ." - ".$competence->getLevel()->getLabel(),
			);
			
			if ($previousFamily != $competence->getCompetenceFamily()->getLabel()){
				$statCompetencesFamily[] = array(
					'value' => $valueFamily,
					'color' => $colors[0],
					'highlight' => $colors[1],
					'label' => $previousFamily,
				);
				$valueFamily = $competence->getPersonnages()->count();
				$previousFamily = $competence->getCompetenceFamily()->getLabel();
			}
			else {
				$valueFamily += $competence->getPersonnages()->count();
			}

		}		
		
		$repo = $app['orm.em']->getRepository('LarpManager\Entities\Personnage');
		$personnages = $repo->findAll();
		
		$repo = $app['orm.em']->getRepository('LarpManager\Entities\User');
		$users = $repo->findAll();
		
		$repo = $app['orm.em']->getRepository('LarpManager\Entities\Participant');
		$participants = $repo->findAll();
		
		$repo = $app['orm.em']->getRepository('LarpManager\Entities\Groupe');
		$groupes = $repo->findAll();
		$places = 0;
		foreach( $groupes as $groupe)
		{
			$places += $groupe->getClasseOpen();	
		}
		
		$repo = $app['orm.em']->getRepository('LarpManager\Entities\Genre');
		$genres = $repo->findAll();
		$statGenres = array();
		foreach($genres as $genre)
		{
			$colors = RandomColor::many(2, array(
					'luminosity' => array('light', 'bright'),
					'hue' => 'random'
			));
			$statGenres[] = array(
					'value' => $genre->getPersonnages()->count(),
					'color' => $colors[0],
					'highlight' => $colors[1],
					'label' => $genre->getLabel(),
			);
		}
		
		return $app['twig']->render('admin/statistique/index.twig', array(
				'langues' => json_encode($stats),
				'classes' => json_encode($statClasses),
				'genres' => json_encode($statGenres),
				'competences' => json_encode($statCompetences),
				'competencesFamily' => json_encode($statCompetencesFamily),
				'constructions' => json_encode($statConstructions),
				'personnageCount' => count($personnages),
				'userCount' => count($users),
				'participantCount' => count($participants), 
				'places' => $places,
		));
	}
}