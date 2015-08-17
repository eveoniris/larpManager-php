<?php

namespace LarpManager\Groupe;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\SecurityContextInterface;

use LarpManager\Groupe\GroupeVoter;
use LarpManager\Groupe\GroupeManager;


class GroupeServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{
		// Groupe manager
		$app['groupe.manager'] = $app->share(function($app) {
			$groupeManager = new GroupeManager($app);
			return $groupeManager;
		});
		
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
	
	public function boot(Application $app)
	{
	}
}