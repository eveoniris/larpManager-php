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
				
		return $app['twig']->render('admin/statistique/index.twig', array(
				'langues' => json_encode($stats)));
	}
}