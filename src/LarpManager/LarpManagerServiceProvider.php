<?php

namespace LarpManager;

use Silex\Application;
use Silex\ServiceProviderInterface;

use LarpManager\Twig\LarpManagerExtension;

/**
 * LarpManager\LarpManagerServiceProvider
 * 
 * @author kevin
 *
 */
class LarpManagerServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{
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