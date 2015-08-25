<?php

namespace LarpManager\Personnage;

use Silex\Application;
use LarpManager\Entities\Personnage;
use LarpManager\Entities\PersonnageCompetence;
use LarpManager\Entities\Competence;
use LarpManager\Entities\Niveau;

use Doctrine\Common\Collections\Collection;

class PersonnageManager
{
	/** @var \Silex\Application */
	protected $app;
	
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	
	/**
	 * Ajoute une competence à la liste des compétence d'un personnage
	 * 
	 * @param Personnage $personnage
	 * @param Competence $competence
	 * @param Niveau $niveau
	 */
	public function addCompetence(Personnage $personnage, Competence $competence, Niveau $niveau)
	{
		
		$competenceNiveau = $this->findCompetenceNiveau($competence, $niveau);
		
		// vérifie si le personnage dispose bien de la competence de niveau inférieur.
		if ( $niveau->getNiveau() > 1 ) 
		{
			$previousNiveau = $app['niveau.manager']->getPrevious($niveau);
			if ( $previousNiveau )
			{
				if ( ! $this->hasCompetence($competence, $previousNiveau ) ) return null; // erreur. retourner une exception ?
			}
		}
		
		
		// vérifie si le joueur dispose de suffisement de point d'expérience
		$joueur = $personnage->getJoueur();
		
		if ( ! $joueur ) return null; // erreur. retourner une exception ?
		$xpAvailable = $joueur->getXp();
			
		
	}
	
	/**
	 * Determine si un personnage dispose d'une competence au niveau demandé.
	 * @param Personnage $personnage
	 * @param Competence $competence
	 * @param Niveau $niveau
	 */
	public function hasCompetence(Personnage $personnage, Competence $competence, Niveau $niveau )
	{
		$query = $this->app['orm.em']->createQuery('SELECT 1 FROM LarpManager\Entities\PersonnageCompetence pc WHERE pc.competence = :competence AND pc.niveau = :niveau')
									->setParameter('competence',$competence)
									->setParameter('niveau', $niveau);
		
		$result = $query->getSingleResult();
		
		if ( $result == 1 ) return true;
		return false;
	}
	
	/**
	 * Récupére l'objet competenceNiveau
	 * @param Competence $competence
	 * @param Niveau $niveau
	 */
	public function findCompetenceNiveau(Competence $competence, Niveau $niveau)
	{
		$query = $this->app['orm.em']->createQuery('SELECT cn FROM LarpManager\Entities\CompetenceNiveau cn WHERE cn.competence = :competence AND cn.niveau = :niveau')
									->setParameter('competence',$competence)
									->setParameter('niveau', $niveau);
		$result = $query->getSingleResult();
		return $result;
	}
}
