<?php

namespace LarpManager\Niveau;

use Silex\Application;
use Silex\ServiceProviderInterface;

use LarpManager\Niveau\NiveauManager;


class NiveauServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{
		// Territoire manager
		$app['niveau.manager'] = $app->share(function($app) {
			$niveauManager = new NiveauManager($app);
			return $niveauManager;
		});
	}

	public function boot(Application $app)
	{
	}
}