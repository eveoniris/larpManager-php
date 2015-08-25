<?php

namespace LarpManager\Competence;

use Silex\Application;
use Silex\ServiceProviderInterface;

use LarpManager\Competence\CompetenceManager;


class CompetenceServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{
		// Competence manager
		$app['competence.manager'] = $app->share(function($app) {
			$competenceManager = new CompetenceManager($app);
			return $competenceManager;
		});
	}

	public function boot(Application $app)
	{
	}
}