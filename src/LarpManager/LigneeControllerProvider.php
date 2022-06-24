<?php

namespace LarpManager;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * LarpManager\LigneeControllerProvider
 *
 * @author gerald
 */
class LigneeControllerProvider implements ControllerProviderInterface
{

    /**
     * Initialise les routes pour les lignées
     * Routes :
     *  - lignee.list
     *  - lignee.details
     *  - lignee.add
     *  - lignee.update
     *  - lignee.addMembre
     *  - lignee.membre.remove
     *
     * @param Application $app
     * @return Controllers $controllers
     */
    public function connect(Application $app)
    {
        /**
         * Vérifie que l'utilisateur dispose du role Orga
         */
        $mustBeScenariste = function (Request $request) use ($app) {
            if (!$app['security.authorization_checker']->isGranted('ROLE_REGLE')) {
                throw new AccessDeniedException();
            }
        };

        $controllers = $app['controllers_factory'];

        /**
         * Liste des lignées
         */
        $controllers->match('/list','LarpManager\Controllers\LigneeController::listAction')
            ->bind("lignee.list")
            ->method('GET|POST')
            ->before($mustBeScenariste);

        /**
         * Detail d'une lignée
         */
        $controllers->match('/{lignee}','LarpManager\Controllers\LigneeController::detailAction')
            ->assert('lignee', '\d+')
            ->bind("lignee.details")
            ->method('GET')
            ->before($mustBeScenariste);

        /**
         * Création d'une lignée
         */
        $controllers->match('/add','LarpManager\Controllers\LigneeController::addAction')
            ->bind("lignee.add")
            ->method('GET|POST')
            ->before($mustBeScenariste);

        /**
         * Modification d'une lignée
         */
        $controllers->match('/{lignee}/update','LarpManager\Controllers\LigneeController::updateAction')
            ->assert('lignee', '\d+')
            ->bind("lignee.update")
            ->method('GET|POST')
            ->before($mustBeScenariste);

        /**
         * Ajout d'un membre
         */
        $controllers->match('/{lignee}/addMembre','LarpManager\Controllers\LigneeController::addMembreAction')
            ->assert('lignee', '\d+')
            ->bind("lignee.addMembre")
            ->method('GET|POST')
            ->before($mustBeScenariste);

        /**
         * Suppression d'un membre
         */
        $controllers->match('/{lignee}/membre/{membre}/remove','LarpManager\Controllers\LigneeController::removeMembreAction')
            ->assert('lignee', '\d+')
            ->assert('membre', '\d+')
            ->bind("lignee.membre.remove")
            ->method('GET')
            ->before($mustBeScenariste);

        return $controllers;
    }
}

