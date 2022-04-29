<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * LarpManager\LigneeControllerProvider
 *
 * @author gerald
 *
 */
class LigneeControllerProvider implements ControllerProviderInterface
{

    /**
     * Initialise les routes pour les lignees
     * Routes :
     *  - lignee.list
     *
     * @param Application $app
     * @return Controllers $controllers
     * @throws AccessDeniedException
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        /**
         * Vérifie que l'utilisateur dispose du role SCENARISTE
         */
        $mustBeScenariste = function (Request $request) use ($app) {
            if (!$app['security.authorization_checker']->isGranted('ROLE_SCENARISTE')) {
                throw new AccessDeniedException();
            }
        };

        /**
         * Liste des lignées
         */
        $controllers->match('/admin/list','LarpManager\Controllers\LigneeController::adminListAction')
            ->bind("lignee.admin.list")
            ->method('GET|POST')
            ->before($mustBeScenariste);

        return $controllers;
    }
}