<?php

namespace LarpManager\Controllers;

use JasonGrimes\Paginator;
use LarpManager\Entities\Lignee;
use LarpManager\Form\Lignee\LigneeFindForm;
use LarpManager\Form\Lignee\LigneeForm;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * LarpManager\Controllers\LigneeController
 *
 * @author gerald
 */
class LigneeController
{

    /**
     * Liste des lignées
     *
     * @param Request $request
     * @param Application $app
     */
    public function adminListAction(Request $request, Application $app)
    {
        $order_by = $request->get('order_by') ?: 'id';
        $order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
        $limit = (int)($request->get('limit') ?: 50);
        $page = (int)($request->get('page') ?: 1);
        $offset = ($page - 1) * $limit;
        $type= null;
        $value = null;

        $form = $app['form.factory']->createBuilder(new LigneeFindForm())->getForm();

        $form->handleRequest($request);

        if ( $form->isValid() && $form->isSubmitted() )
        {
            $data = $form->getData();
            $type = $data['type'];
            $value = $data['search'];
        }

        $repo = $app['orm.em']->getRepository('\LarpManager\Entities\Lignee');

        $lignees = $repo->findList(
            $type,
            $value,
            array( 'by' =>  $order_by, 'dir' => $order_dir),
            $limit,
            $offset);

        $numResults = $repo->findCount($type, $value);

        $paginator = new Paginator($numResults, $limit, $page,
            $app['url_generator']->generate('lignee.admin.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
        );

        return $app['twig']->render('admin/lignee/list.twig', array(
            'form' => $form->createView(),
            'lignees' => $lignees,
            'paginator' => $paginator,
        ));
    }

    /**
     * Affiche le détail d'une lignée
     *
     * @param Request $request
     * @param Application $app
     */
    public function detailAction(Request $request, Application $app)
    {
        $id = $request->get('lignee');

        $lignee = $app['orm.em']->find('\LarpManager\Entities\Lignee',$id);

        /**
         * Si la lignee existe, on affiche ses détails
         * Sinon on envoie une erreur
         */
        if ( $lignee )
        {
            return $app['twig']->render('admin/lignee/details.twig', array('lignee' => $lignee));
        }
        else
        {
            $app['session']->getFlashBag()->add('error', 'La lignee n\'a pas été trouvée.');
            return $app->redirect($app['url_generator']->generate('lignee'));
        }
    }

    /**
     * Ajout d'une lignée
     *
     * @param Request $request
     * @param Application $app
     */
    public function adminAddAction(Request $request, Application $app)
    {
        $lignee = new Lignee();

        $form = $app['form.factory']->createBuilder(new LigneeForm(), $lignee)
            ->add('save','submit', array('label' => 'Sauvegarder et retour à la liste'))
            ->add('save_continue','submit',array('label' => 'Sauvegarder et nouvelle lignée'))
            ->getForm();

        $form->handleRequest($request);

        if ( $form->isValid() && $form->isSubmitted())
        {
            $lignee = $form->getData();

            $app['orm.em']->persist($lignee);
            $app['orm.em']->flush();
            $app['session']->getFlashBag()->add('success', 'La lignée a été enregistrée.');

            /**
             * Si l'utilisateur a cliqué sur "save", renvoi vers la liste des lignee
             * Si l'utilisateur a cliqué sur "save_continue", renvoi vers un nouveau formulaire d'ajout
             */
            if ( $form->get('save')->isClicked())
            {
                return $app->redirect($app['url_generator']->generate('lignee.admin.list'),303);
            }
            else if ( $form->get('save_continue')->isClicked())
            {
                return $app->redirect($app['url_generator']->generate('lignee.admin.add'),303);
            }
        }

        return $app['twig']->render('admin/lignee/add.twig', array('form' => $form->createView()));
    }

    /**
     * Modification d'une lignée
     *
     * @param Request $request
     * @param Application $app
     */
    public function adminUpdateAction(Request $request, Application $app)
    {
        $id = $request->get('lignee');

        $lignee = $app['orm.em']->find('\LarpManager\Entities\Lignee',$id);

        $form = $app['form.factory']->createBuilder(new LigneeForm(), $lignee)
            ->add('update','submit', array('label' => "Sauvegarder"))
            ->add('delete','submit', array('label' => "Supprimer"))
            ->getForm();

        $form->handleRequest($request);

        if ( $form->isValid() && $form->isSubmitted() )
        {
            $lignee = $form->getData();

            /**
             * Si l'utilisateur a cliquer sur "update", on met à jour la lignée
             * Si l'utilisateur a cliquer sur "delete", on supprime la lignée
             */
            if ($form->get('update')->isClicked())
            {
                $app['orm.em']->persist($lignee);
                $app['orm.em']->flush();
                $app['session']->getFlashBag()->add('success', 'La lignée a été mise à jour.');
                return $app->redirect($app['url_generator']->generate('lignee.admin.details', array('lignee' => $id)));
            }
            else if ($form->get('delete')->isClicked())
            {
                // supprime le lien entre les personnages et le groupe
                foreach ( $lignee->getPersonnageLignees() as $personnage)
                {
                    /*TODO /suppimer le ligne dans PersonnageLignee*/
                    $app['orm.em']->persist($personnage);
                }
                $app['orm.em']->remove($lignee);
                $app['orm.em']->flush();

                $app['session']->getFlashBag()->add('success', 'La lignée a été supprimée.');
                return $app->redirect($app['url_generator']->generate('lignee.admin.list'));
            }


        }

        return $app['twig']->render('admin/lignee/update.twig', array(
            'lignee' => $lignee,
            'form' => $form->createView(),
        ));
    }
}