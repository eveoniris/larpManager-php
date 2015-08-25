<?php

namespace LarpManager\Niveau;

use Silex\Application;
use LarpManager\Entities\Niveau;

class NiveauManager
{
	/** @var \Silex\Application */
	protected $app;
	
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	
	/**
	 * Fourni un niveau en fonction de son index
	 * 
	 * @param integer $index
	 * @return Niveau $niveau
	 */
	public function get($index)
	{
		$repoNiveau = $this->app['orm.em']->getRepository('\LarpManager\Entities\Niveau');
		$niveau = $repoNiveau->findOneByNiveau(1);
		return $niveau;
	}

	/**
	 * Fourni le niveau suivant
	 * 
	 * @param Niveau $niveau
	 */
	public function next(Niveau $niveau)
	{
		$query = $this->app['orm.em']->createQuery('SELECT n FROM LarpManager\Entities\Niveau n WHERE n.niveau = :niveau')
									->setParameter('niveau', $niveau->getNiveau() + 1);
		$nextNiveau = $query->getSingleResult();
		return $nextNiveau;
	}
	
	/**
	 * Fourni le niveau precedent
	 * 
	 * @param Niveau $niveau
	 */
	public function previous(Niveau $niveau)
	{
		$query = $this->app['orm.em']->createQuery('SELECT n FROM LarpManager\Entities\Niveau n WHERE n.niveau = :niveau')
									->setParameter('niveau', $niveau->getNiveau() - 1);
		$previousNiveau = $query->getSingleResult();
		return $previousNiveau;		
	}
}
		