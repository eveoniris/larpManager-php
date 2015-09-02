<?php

namespace LarpManager\Groupe;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;

use LarpManager\Groupe\GroupeVoter;

/**
 * LarpManager\Groupe\GroupeServiceProvider
 * 
 * @author kevin
 *
 */
class GroupeServiceProvider implements ServiceProviderInterface
{
	/**
	 * Enregistrement du service
	 * 
	 * @param Application $app
	 */
	public function register(Application $app)
	{		
		// Ajoute une nouvelle gestion des droits pour les groupes
		$app['security.voters'] = $app->extend('security.voters', function($voters) use ($app) {
			foreach ($voters as $voter) {
				if ($voter instanceof RoleHierarchyVoter) {
					$roleHierarchyVoter = $voter;
					break;
				}
			}
			$voters[] = new GroupeVoter($roleHierarchyVoter);
			return $voters;
		});
	}
	
	/**
	 * Evénement boot
	 * 
	 * @param Application $app
	 */
	public function boot(Application $app)
	{
	}
}