<?php

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
		foreach( $langues as $langue)
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
				
		foreach( $classes as $classe)
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
		
		foreach( $genres as $genre)
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
				'personnageCount' => count($personnages),
				'userCount' => count($users),
				'participantCount' => count($participants), 
				'places' => $places,
		));
	}
}