<?php

namespace LarpManager\Services;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;

use LarpManager\Twig\LarpManagerExtension;
use LarpManager\Services\ForumRightManager;
use LarpManager\Services\LarpManagerVoter;
use LarpManager\Services\fpdf\FPDF;

/**
 * LarpManager\LarpManagerServiceProvider
 * 
 * @author kevin
 *
 */
class LarpManagerServiceProvider implements ServiceProviderInterface
{
	/**
	 * 
	 * @param Application $app
	 */
	public function register(Application $app)
	{
		// Ajoute la gestion des droits propre à larpmanager
		$app['security.voters'] = $app->extend('security.voters', function($voters) use ($app) {
			foreach ($voters as $voter) {
				if ($voter instanceof RoleHierarchyVoter) {
					$roleHierarchyVoter = $voter;
					break;
				}
			}
			$voters[] = new LarpManagerVoter($roleHierarchyVoter);
			return $voters;
		});
		
		// manager
		$app['larp.manager'] = $app->share(function($app) {
			$larpManagerManager = new LarpManagerManager($app);
			return $larpManagerManager;
		});
		
		// Forum right manager
		$app['forum.manager'] = $app->share(function($app) {
			$forumRightManager = new ForumRightManager($app);
			return $forumRightManager;
		});
		
		// fpdf
		$app['pdf.manager'] = $app->share(function($app) {
			$fpdf = new FPDF();
			$fpdf->FPDF();
			return $fpdf;
		});
	}

	/**
	 * Ajoute les extensions Twig de LarpManager
	 * 
	 * @param Application $app
	 */
	public function boot(Application $app)
	{
		$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
			$twig->addExtension(new LarpManagerExtension($app));
			return $twig;
		}));
	}
}