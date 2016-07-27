<?php

namespace LarpManager\Personnage;

use Silex\Application;
use LarpManager\Entities\Personnage;
use LarpManager\Entities\CompetenceFamily;
use LarpManager\Entities\Competence;
use LarpManager\Entities\Religion;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * LarpManager\Personnage\PersonnageManager
 * 
 * @author kevin
 *
 */
class PersonnageManager
{
	/** @var \Silex\Application */
	protected $app;
	
	/**
	 * Constructeur
	 * 
	 * @param Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	
	/**
	 * Calcul le cout d'une compétence en fonction de la classe du personnage
	 * 
	 * @param Personnage $personnage
	 * @param Competence $competence
	 */
	public function getCompetenceCout(Personnage $personnage, Competence $competence)
	{
		$classe = $personnage->getClasse();
		if ($classe->getCompetenceFamilyFavorites()->contains($competence->getCompetenceFamily()))
		{
			return $competence->getLevel()->getCoutFavori();
		}
		else if ($classe->getCompetenceFamilyNormales()->contains($competence->getCompetenceFamily()))
		{
			return $competence->getLevel()->getCout();
		}

		return $competence->getLevel()->getCoutMeconu();

	}

	/**
	 * Fourni le titre du personnage en fonction de sa renommée
	 * 
	 * @param Personnage $personnage
	 */
	public function titre(Personnage $personnage)
	{

		$result = null;
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\Titre');
		$titres = $repo->findByRenomme();
		foreach ($titres as $titre )
		{
			if ( $personnage->getRenomme() >= $titre->getRenomme() )
			{
				$result = $titre;
			}
		}
		
		return $result;
	}
	
	/**
	 * Fourni le score de pugilat du personnage
	 * 
	 * @param Personnage $personnage
	 */
	public function pugilat(Personnage $personnage)
	{		
		$pugilat = $personnage->getCompetencePugilat('Agilité') 
			+ $personnage->getCompetencePugilat('Armes à distance')
			+ $personnage->getCompetencePugilat('Armes à 1 main')
			+ $personnage->getCompetencePugilat('Armes à 2 mains')
			+ $personnage->getCompetencePugilat('Armes d\'hast')
			+ $personnage->getCompetencePugilat('Armure')
			+ $personnage->getCompetencePugilat('Attaque sournoise')
			+ $personnage->getCompetencePugilat('Protection')
			+ $personnage->getCompetencePugilat('Résistance')
			+ $personnage->getCompetencePugilat('Sauvagerie')
			+ $personnage->getCompetencePugilat('Stratégie')
			+ $personnage->getCompetencePugilat('Survie');
		
		// armurerie au niveau initié double le score de pugilat
		if ( $personnage->getCompetenceNiveau('Armurerie') >= 2 )
		{
			$pugilat = $pugilat * 2;			
		}
		return $pugilat;
		
	}
	
	/**
	 * Indique si un personnage connait une famille de competence
	 * 
	 * @param Personnage $personnage
	 * @param CompetenceFamily $competenceFamily
	 * @return boolean
	 */
	public function knownCompetenceFamily(Personnage $personnage, CompetenceFamily $competenceFamily)
	{
		$competences = $personnage->getCompetences();
		
		foreach ( $competences as $competence)
		{
			if ( $competence->getCompetenceFamily() == $competenceFamily)
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Indique si un personnage connait une religion
	 * 
	 * @param Personnage $personnage
	 * @param Religion $religion
	 */
	public function knownReligion(Personnage $personnage, Religion $religion)
	{
		$personnageReligions = $personnage->getPersonnagesReligions();
		
		foreach ( $personnageReligions as $personnageReligion )
		{
			if ( $personnageReligion->getReligion() == $religion)
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Fourni la liste des compétences inconnues d'un personnage
	 * 
	 * @param Personnage $personnage
	 * @return Collection $competences
	 */
	public function getUnknownCompetences(Personnage $personnage)
	{
		$unknownCompetences = new ArrayCollection();
		
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\CompetenceFamily');
		$competenceFamilies = $repo->findAll();
				
		foreach ( $competenceFamilies as $competenceFamily)
		{
			if ( ! $this->knownCompetenceFamily($personnage, $competenceFamily))
			{
				$competence = $competenceFamily->getFirstCompetence();
				if ( $competence )
				{
					$unknownCompetences->add($competence);
				}
			}
		}
		
		return $unknownCompetences;
	}

	/**
	 * Récupére la liste des toutes les compétences accessibles pour un personnage
	 * 
	 * @param Personnage $personnage
	 * @return Collection $competenceNiveaux
	 */
	public function getAvailableCompetences(Personnage $personnage)
	{
		$availableCompetences = new ArrayCollection();
		
		// les compétences de niveau supérieur sont disponibles
		$competences = $personnage->getCompetences();
		foreach ( $competences as $competence )
		{
			$nextCompetence = $competence->getNext();
			if ( $nextCompetence &&  ! $personnage->getCompetences()->contains($nextCompetence) )
			{
				$availableCompetences->add($nextCompetence);
			}
		}
		
		// les compétences inconnue du personnage sont disponible au niveau 1
		$competences = $this->getUnknownCompetences($personnage);
		
		foreach ($competences as $competence )
		{
			$availableCompetences->add($competence);		
		}
		
		// trie des competences disponibles
		$iterator = $availableCompetences->getIterator();
		$iterator->uasort(function ($a, $b) {
			return ($a->getLabel() < $b->getLabel()) ? -1 : 1;
		});
		
		return  new ArrayCollection(iterator_to_array($iterator));
	}
	
	/**
	 * Trouve toutes les langues non connus d'un personnages en fonction du niveau de diffusion voulu
	 * @param Personnage $langue
	 * @param unknown $diffusion
	 */
	public function getAvailableLangues(Personnage $personnage, $diffusion)
	{
		$availableLangues = new ArrayCollection();
		
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\Langue');
		$langues = $repo->findAll();
		
		foreach ( $langues as $langue)
		{
			if ( $langue->getDiffusion() >= $diffusion 
			&& ! $personnage->isKnownLanguage($langue) )
			{
				$availableLangues[] = $langue;
			}
		}
		return $availableLangues;
	}
	
	/**
	 * Récupére la liste de toutes les religions non connue du personnage
	 * @param Personnage $personnage
	 */
	public function getAvailableReligions(Personnage $personnage)
	{
		$availableReligions = new ArrayCollection();
		
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\Religion');
		$religions = $repo->findAll();
		
		foreach ( $religions as $religion)
		{
			if ( ! $this->knownReligion($personnage, $religion))
			{
				$availableReligions->add($religion);
			}
		}
		
		return $availableReligions;
	}
			
	
	/**
	 * Fourni la dernière compétence acquise par un presonnage
	 * 
	 * @param Personnage $personnage
	 */
	public function getLastCompetence(Personnage $personnage)
	{
		$competence = null;
		$operationDate = null;
		
		foreach ( $personnage->getExperienceUsages() as $experienceUsage)
		{
			if ( $personnage->getCompetences()->contains($experienceUsage->getCompetence()) )
			{
				if ( ! $operationDate )
				{
					$operationDate = $experienceUsage->getOperationDate();
					$competence = $experienceUsage->getCompetence();
				}	
				else if ( $operationDate <  $experienceUsage->getOperationDate() )
				{
					$operationDate = $experienceUsage->getOperationDate();
					$competence = $experienceUsage->getCompetence();
				}
			}
		}
		
		return $competence;
			
	}
}
