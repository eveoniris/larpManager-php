<?php

namespace LarpManager\Territoire;

use Silex\Application;
use Silex\ServiceProviderInterface;

use LarpManager\Territoire\TerritoireManager;


class TerritoireServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{
		// Territoire manager
		$app['territoire.manager'] = $app->share(function($app) {
			$territoireManager = new TerritoireManager($app);
			return $territoireManager;
		});
	}

	public function boot(Application $app)
	{
	}
}