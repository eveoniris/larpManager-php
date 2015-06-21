<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\EntityManager;

$app = require_once __DIR__.'/../app/bootstrap.php';

$entityManager = $app['orm.em'];

return ConsoleRunner::createHelperSet($entityManager);