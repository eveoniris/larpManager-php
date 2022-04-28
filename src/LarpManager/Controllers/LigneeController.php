<?php

namespace LarpManager\Controllers;

use JasonGrimes\Paginator;
use LarpManager\Form\Lignee\LigneeFindForm;
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
     * Liste des groupes
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
}