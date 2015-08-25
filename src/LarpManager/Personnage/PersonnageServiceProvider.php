<?php

namespace LarpManager\Personnage;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\SecurityContextInterface;

use LarpManager\Personnage\PersonnageVoter;
use LarpManager\Personnage\PersonnageManager;


class PersonnageServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{
		// Personnage manager
		$app['personnage.manager'] = $app->share(function($app) {
			$personnageManager = new PersonnageManager($app);
			return $personnageManager;
		});
		
		// Ajoute une nouvelle gestion des droits pour les personnages
		$app['security.voters'] = $app->extend('security.voters', function($voters) use ($app) {
			foreach ($voters as $voter) {
				if ($voter instanceof RoleHierarchyVoter) {
					$roleHierarchyVoter = $voter;
					break;
				}
			}
			$voters[] = new PersonnageVoter($roleHierarchyVoter);
			return $voters;
		});
	}

	public function boot(Application $app)
	{
	}
}