<?php

namespace LarpManager\Controllers;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

/**
 * Gestion du stock
 * @author kevin
 */
class StockController
{
	/**
	 * @description affiche la vue index.twig
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Objet');
		
		$qb = $repo->createQueryBuilder('objet');
		$qb->select('COUNT(objet)');
		$objet_count = $qb->getQuery()->getSingleScalarResult();
		
		$qb = $repo->createQueryBuilder('o');
		$qb->select('COUNT(o)');
		$qb->where('o.proprietaire_id IS NULL');
		$objet_without_proprio_count = $qb->getQuery()->getSingleScalarResult();
		
		$qb = $repo->createQueryBuilder('o');
		$qb->select('COUNT(o)');
		$qb->where('o.responsable_id IS NULL');
		$objet_without_responsable_count = $qb->getQuery()->getSingleScalarResult();
		
		$qb = $repo->createQueryBuilder('o');
		$qb->select('COUNT(o)');
		$qb->where('o.localisation_id IS NULL');
		$objet_without_localisation_count = $qb->getQuery()->getSingleScalarResult();
		
		
		$last_add = $repo->findBy(array(), array('creation_date' => 'DESC'),10,0);
		
		return $app['twig']->render('stock/index.twig', array(
				'objet_count' => $objet_count,
				'last_add' => $last_add,
				'objet_without_proprio_count' => $objet_without_proprio_count,
				'objet_without_responsable_count' => $objet_without_responsable_count,
				'objet_without_localisation_count' => $objet_without_localisation_count,
		));
	}	
}