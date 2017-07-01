<?php
namespace LarpManager\Services;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Knp\Snappy\Image;
use Knp\Snappy\Pdf;

/**
 * Silex service provider to integrate Snappy library.
 *
 * @author Jérôme Tamarelle <jerome@tamarelle.net>
 */
class SnappyServiceProvider implements ServiceProviderInterface
{
    public function boot(Application $app)
    {
    }
    
    public function register(Application $app)
    {    	
        $app['snappy.image'] = $app->share(function ($app) {
            return new Image(
                isset($app['snappy.image_binary']) ? $app['snappy.image_binary'] : '/usr/local/bin/wkhtmltoimage',
                isset($app['snappy.image_options']) ? $app['snappy.image_options'] : array()
            );
        });
        $app['snappy.pdf'] = $app->share(function ($app) {
            return new Pdf(
                isset($app['snappy.pdf_binary']) ? $app['snappy.pdf_binary'] : __DIR__.'/../../../vendor/bin/wkhtmltopdf-i386',
                isset($app['snappy.pdf_options']) ? $app['snappy.pdf_options'] : array()
            );
        });
    }
}