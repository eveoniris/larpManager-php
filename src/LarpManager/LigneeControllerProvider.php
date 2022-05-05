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
     * Initialise les routes pour les lignées
     * Routes :
     *  - lignee.admin.list
     *  - lignee.admin.details
     *  - lignee.admin.add
     *  - lignee.admin.update
     *  - lignee.admin.addMembre
     *  - lignee.admin.membre.remove
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
         * Création d'une lignée
         */
        $controllers->match('/admin/add','LarpManager\Controllers\LigneeController::adminAddAction')
            ->bind("lignee.admin.add")
            ->method('GET|POST')
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
         * Ajout d'un membre
         */
        $controllers->match('/admin/{lignee}/addMembre','LarpManager\Controllers\LigneeController::adminAddMembreAction')
            ->assert('lignee', '\d+')
            ->bind("lignee.admin.addMembre")
            ->method('GET|POST')
            ->before($mustBeOrga);

        /**
         * Suppression d'un membre
         */
        $controllers->match('/admin/{lignee}/membre/{membre}/remove','LarpManager\Controllers\LigneeController::adminRemoveMembreAction')
            ->assert('lignee', '\d+')
            ->assert('membre', '\d+')
            ->bind("lignee.admin.membre.remove")
            ->method('GET')
            ->before($mustBeOrga);

        return $controllers;
    }
}

