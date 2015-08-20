<?php

namespace LarpManager\Territoire;

use Silex\Application;
use LarpManager\Entities\Territoire;
use Doctrine\Common\Collections\Collection;

class TerritoireManager
{
	/** @var \Silex\Application */
	protected $app;
	
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	
	/**
	 * Il faut classer les territoires par groupe de territoire
	 * 
	 * @param Array $territoires
	 * @return Array $territoires
	 */
	public function sort( Array $territoires)
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
								$this->sort($territoire->getTerritoires()->toArray())
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
	 * Calcule le nombre d'étape necessaire pour revenir au parent le plus ancien
	 */
	public function stepCount(Territoire $territoire, $count = 0)
	{
		if ( $territoire->getTerritoire() )
		{
			return $this->stepCount($territoire->getTerritoire(),$count+1);
		}
		return $count;
	}
	
	/**
	 * Fourni la liste des territoires n'étant pas dépendant d'un autre territoire
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function findRoot()
	{
		$query = $this->app['orm.em']->createQuery('SELECT t FROM LarpManager\Entities\Territoire t WHERE t.territoire IS NULL');
		$territoires = $query->getResult();
		
		return $territoires;
	}
	

	/**
	 * Fourni la liste de tous les territoires 
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function findAll()
	{
		$query = $this->app['orm.em']->createQuery('SELECT t FROM LarpManager\Entities\Territoire t');
		$territoires = $query->getResult();
	
		return $territoires;
	
	}
	
	/**
	 * Trouve un territoire en fonction de son id
	 * 
	 * @param integer $id
	 * @return \LarpManager\Entities\Territoire
	 */
	public function find($id)
	{		
		$territoire = $this->app['orm.em']->find('\LarpManager\Entities\Territoire',$id);
		return $territoire;
	}
}
