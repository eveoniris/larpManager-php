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
     *  - lignee.admin.list
     *  - lignee.admin.details
     *  - lignee.admin.update
     *  - lignee.admin.delete
     *
     * @param Application $app
     * @return Controllers $controllers
     * @throws AccessDeniedException
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        /**
         * Vérifie que l'utilisateur dispose du role Orga
         */
        $mustBeOrga = function (Request $request) use ($app) {
            if (!$app['security.authorization_checker']->isGranted('ROLE_ORGA')) {
                throw new AccessDeniedException();
            }
        };

        /**
         * Liste des lignées
         */
        $controllers->match('/admin/list','LarpManager\Controllers\LigneeController::adminListAction')
            ->bind("lignee.admin.list")
            ->method('GET|POST')
            ->before($mustBeOrga);

        /**
         * Detail d'une lignée
         */
        $controllers->match('/admin/{lignee}','LarpManager\Controllers\LigneeController::detailAction')
            ->assert('lignee', '\d+')
            ->bind("lignee.admin.details")
            ->method('GET')
            ->before($mustBeOrga);

        /**
         * Modification d'une lignée
         */
        $controllers->match('/admin/{lignee}/update','LarpManager\Controllers\LigneeController::adminUpdateAction')
            ->assert('lignee', '\d+')
            ->bind("lignee.admin.update")
            ->method('GET|POST')
            ->before($mustBeOrga);

        /**
         * Suppression d'une lignée
         */
        $controllers->match('/admin/{lignee}/delete','LarpManager\Controllers\LigneeController::adminDeleteAction')
            ->assert('lignee', '\d+')
            ->bind("lignee.admin.delete")
            ->method('GET|POST')
            ->before($mustBeOrga);

        return $controllers;
    }

}