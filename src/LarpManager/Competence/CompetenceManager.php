<?php

namespace LarpManager\Competence;

use Silex\Application;
use LarpManager\Entities\Competence;
use LarpManager\Entities\CompetenceNiveau;
use LarpManager\Entities\Niveau;
use Doctrine\Common\Collections\Collection;

class CompetenceManager
{
	/** @var \Silex\Application */
	protected $app;
	
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	
	/**
	 * Fourni le niveau d'une compétence
	 * 
	 * @param Competence $competence
	 * @param Niveau $niveau
	 * @return CompetenceNiveau $competenceNiveau
	 */
	public function getNiveau(Competence $competence,Niveau $niveau)
	{
		$competenceNiveaux = $competence->getCompetenceNiveaux();
		foreach ( $competenceNiveaux as $competenceNiveau )
		{
			if ( $competenceNiveau->getNiveau() == $niveau)
			{
				return $competenceNiveau;
			}
				
		}
		return null;
	}
	
	/**
	 * Fourni le niveau d'une compétence
	 *
	 * @param Competence $competence
	 * @param integer $niveau
	 * @return CompetenceNiveau $competenceNiveau
	 */	
	public function getNiveau(Competence $competence, $niveau)
	{
		$competenceNiveaux = $competence->getCompetenceNiveaux();
		foreach ( $competenceNiveaux as $competenceNiveau )
		{
			if ( $competenceNiveau->getNiveau()->getNiveau() == $niveau)
			{
				return $competenceNiveau;
			}
				
		}
		return null;
	}
	
	/**
	 * Fourni le prochain niveau d'une compétence
	 *
	 * @param CompetenceNiveau $competenceNiveau
	 * @return CompetenceNiveau $competenceNiveau
	 */
	public function next(CompetenceNiveau $competenceNiveau)
	{
		$niveau = $competenceNiveau->getNiveau();
		$competence = $competenceNiveau->getCompetence();
		$nextNiveau = $app['niveau.manager']->next($niveau);
	
		$competenceNiveau = $this->getCompetenceNiveau($competence,$nextNiveau);
		return $competenceNiveau;
	}
}