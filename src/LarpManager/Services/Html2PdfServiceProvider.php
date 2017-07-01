<?php

namespace LarpManager\Services;
		
use Silex\Application;
use Silex\ServiceProviderInterface;

use Spipu\Html2Pdf\Html2Pdf;

/**
 * Silex service provider to integrate Html2pdf library.
 */
class Html2PdfServiceProvider implements ServiceProviderInterface
{
	public function boot(Application $app)
	{
	}

	public function register(Application $app)
	{
		$app['html2pdf'] = $app->share(function ($app) {
				return new Html2Pdf('P','A4','fr');
		});
	}
}

