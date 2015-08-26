<?php

namespace LarpManager\Appelation;

use Silex\Application;
use Silex\ServiceProviderInterface;

use LarpManager\Appelation\AppelationManager;


class AppelationServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{
		// Territoire manager
		$app['appelation.manager'] = $app->share(function($app) {
			$appelationManager = new AppelationManager($app);
			return $appelationManager;
		});
	}

	public function boot(Application $app)
	{
	}
}