<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * LarpManager\Controllers\ForumController
 *
 * @author kevin
 *
 */
class ForumController
{	
	/**
	 * Liste des topics
	 * TODO  : ne lister que les topics correspondants aux droits de l'utilisateur (territoire, gn, groupe, religion, groupe secondaire)
	 *  
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @return View $view
	 */
	public function topicAction(Request $request, Application $app)
	{
		$joueur = $app['user']->getJoueur();
		
		if ( ! $joueur )
		{
			// erreur, l'utilisateur doit avoir un joueur pour acceder aux forums
		}
		
		$personnage = $joueur->getPersonnage();
		$groupe = $personnage->getGroupe();
		$territoire = $groupe->getTerritoire();
			
		
		if ( $joueur->getGns() )
		{
			$topicGns = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')
						->findAllRelatedToJoueurReferedGn($joueur->getId());
		}
		
		if ( $personnage->getTerritoire() )
		{
		
			$topicTerritories = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')
						->findAllRelatedToTerritory();
		}
		
		$topicGroupes = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')
					->findAllRelatedToGroupe();
		
		$topicGroupeSecondary = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')
					->findAllRelatedToGroupeSecondary();
		
		$topicReligions = $app['orm.em']->getRepository('\LarpManager\Entities\Topic')
					->findAllRelatedToReligion();
					
		
		$view = $app['twig']->render('forum/topic.twig', array('topics' => $topics));
		return $view;
	}
}