<?php

namespace LarpManager\Joueur;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;

use LarpManager\Joueur\JoueurVoter;


class JoueurServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{
		// Ajoute une nouvelle gestion des droits pour les joueurs
		$app['security.voters'] = $app->extend('security.voters', function($voters) use ($app) {
			foreach ($voters as $voter) {
				if ($voter instanceof RoleHierarchyVoter) {
					$roleHierarchyVoter = $voter;
					break;
				}
			}
			$voters[] = new JoueurVoter($roleHierarchyVoter);
			return $voters;
		});
	}
	
	public function boot(Application $app)
	{
	}
}