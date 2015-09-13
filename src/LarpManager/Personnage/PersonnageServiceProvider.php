<?php

namespace LarpManager\Personnage;

use Silex\Application;
use Silex\ServiceProviderInterface;
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

	}

	public function boot(Application $app)
	{
	}
}