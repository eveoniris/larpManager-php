<?php

namespace LarpManager\Niveau;

use Silex\Application;
use LarpManager\Entities\Niveau;

use Doctrine\Common\Collections\Collection;

class NiveauManager
{
	/** @var \Silex\Application */
	protected $app;
	
	public function __construct(Application $app)
	{
		$this->app = $app;
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
		