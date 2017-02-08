<?php

use LarpManager\Modules\Example\ExampleControllerProvider;

// Ajout des templates twigs et définition du namespace twig du module
$app['twig.loader.filesystem']->addPath(__DIR__.'/Views','Example');

// Ajout du controlleur provider
$app->mount('/example', new ExampleControllerProvider());