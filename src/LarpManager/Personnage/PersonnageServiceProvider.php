<?php

namespace LarpManager\Personnage;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * May be deprecated
 */
class PersonnageServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{
		// Personnage manager
		$app['personnage.manager'] = $app->share(function($app) {
			return new \LarpManager\Services\Manager\PersonnageManager($app);
		});

	}

	public function boot(Application $app)
	{
	}
}