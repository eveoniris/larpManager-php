<?php
namespace LarpManager\Services;

use LarpManager\Services\Markdown\Markdown;
use LarpManager\Services\Markdown\MarkdownExtra;
use LarpManager\Twig\IMarkdownParser;
use LarpManager\Twig\MarkdownExtension;
use Silex\Application;
use Silex\ServiceProviderInterface;

class MarkdownServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app): ?IMarkdownParser
    {
        $app['markdown'] = $app->share(function() use($app) {
            if (!empty($app['markdown.factory'])) {
                return $app[$app['markdown.factory']];
            }

            $parser = !(empty($app['markdown.parser'])) ? $app['markdown.parser'] : 'markdown';

            switch ($parser) {
                case 'markdown':
                    return new Markdown();
                case 'extra':
                    return new MarkdownExtra();
                default:
                    throw new \RuntimeException("Unknown Markdown parser '$parser' specified");
            }
        });

        return null;
    }

    public function boot(Application $app): void
    {
        if (isset($app['twig']))
        {
            $app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
               $twig->addExtension(new MarkdownExtension($app['markdown']));

                return $twig;
            }));
        }
    }
}