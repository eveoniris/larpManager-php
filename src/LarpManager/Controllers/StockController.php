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
		$qb->where('o.proprietaire IS NULL');
		$objet_without_proprio_count = $qb->getQuery()->getSingleScalarResult();
		
		$qb = $repo->createQueryBuilder('o');
		$qb->select('COUNT(o)');
		$qb->where('o.usersRelatedByResponsableId IS NULL');
		$objet_without_responsable_count = $qb->getQuery()->getSingleScalarResult();
		
		$qb = $repo->createQueryBuilder('o');
		$qb->select('COUNT(o)');
		$qb->where('o.rangement IS NULL');
		$objet_without_rangement_count = $qb->getQuery()->getSingleScalarResult();
		
		
		$last_add = $repo->findBy(array(), array('creation_date' => 'DESC'),10,0);
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Etat');
		$etats = $repo->findAll();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Tag');
		$tags = $repo->findAll();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Localisation');
		$localisations = $repo->findAll();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Rangement');
		$rangements = $repo->findAll();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Proprietaire');
		$proprietaires = $repo->findAll();
		
		return $app['twig']->render('stock/index.twig', array(
				'objet_count' => $objet_count,
				'last_add' => $last_add,
				'objet_without_proprio_count' => $objet_without_proprio_count,
				'objet_without_responsable_count' => $objet_without_responsable_count,
				'objet_without_rangement_count' => $objet_without_rangement_count,
				'etats' => $etats,
				'tags' => $tags,
				'localisations' => $localisations,
				'rangements' => $rangements,
				'proprietaires' => $proprietaires,
		));
	}	
}