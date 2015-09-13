<?php

namespace LarpManager\Services;

use Silex\Application;
use Doctrine\Common\Collections\ArrayCollection;

class LarpManagerManager
{
	
	/** @var \Silex\Application */
	protected $app;

	/**
	 * Constructeur
	 * 
	 * @param \Silex\Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Fourni la liste des compétences de premier niveau
	 * @return Collection $competences
	 */
	public function getRootCompetences()
	{
		$rootCompetences = new ArrayCollection();
	
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\CompetenceFamily');
		$competenceFamilies = $repo->findAll();
	
		foreach ( $competenceFamilies as $competenceFamily)
		{
			$competence = $competenceFamily->getFirstCompetence();
			if ( $competence )
			{
				$rootCompetences->add($competence);
			}
		}
	
		return $rootCompetences;
	}
	
	/**
	 * Trouve un topic en fonction de sa clé
	 * 
	 * @param unknown $topicKey
	 */
	public function findTopic($topicKey)
	{
		$repoTopic = $this->app['orm.em']->getRepository('\LarpManager\Entities\Topic');
		return $repoTopic->findOneByKey($topicKey);
	}

	/**
	 * Il faut classer les territoires par groupe de territoire
	 *
	 * @param Array $territoires
	 * @return Array $territoires
	 */
	public function sortTerritoire( Array $territoires)
	{
		$root = array();
		$result = array();
	
		// recherche des racines ( territoires n'ayant pas de parent
		// dans la liste des territoires fournis)
		foreach ( $territoires as $territoire)
		{
			if ( ! in_array($territoire->getTerritoire(),$territoires) )
			{
				$root[] = $territoire;
			}
		}
	
		foreach ( $root as $territoire)
		{
			if ( count($territoire->getTerritoires()) > 0 )
			{
				$childs = array_merge(
						array($territoire),
						$this->sortTerritoire($territoire->getTerritoires()->toArray())
						);
	
				$result = array_merge($result, $childs);
			}
			else
			{
				$result[] = $territoire;
			}
		}
	
		return $result;
	}
	
	/**
	 * Classement des appelations par groupe
	 *
	 * @param Array $appelations
	 * @return Array $appelations
	 */
	public function sortAppelation( Array $appelations)
	{
		$root = array();
		$result = array();
	
		// recherche des racines ( appelations n'ayant pas de parent
		// dans la liste des appelations fournis)
		foreach ( $appelations as $appelation)
		{
			if ( ! in_array($appelation->getAppelation(),$appelations) )
			{
				$root[] = $appelation;
			}
		}
	
		foreach ( $root as $appelation)
		{
			if ( count($appelation->getAppelations()) > 0 )
			{
				$childs = array_merge(
						array($appelation),
						$this->sortAppelation($appelation->getAppelations()->toArray())
						);
	
				$result = array_merge($result, $childs);
			}
			else
			{
				$result[] = $appelation;
			}
		}
	
		return $result;
	}
}