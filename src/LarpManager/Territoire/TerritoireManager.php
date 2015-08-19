<?php

namespace LarpManager\Territoire;

use Silex\Application;
use LarpManager\Entities\Territoire;

class TerritoireManager
{
	/** @var \Silex\Application */
	protected $app;
	
	public function __construct(Application $app)
	{
		$this->app = $app;
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
