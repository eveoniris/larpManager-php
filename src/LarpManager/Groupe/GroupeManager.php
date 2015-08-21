<?php

namespace LarpManager\Groupe;

use Silex\Application;
use LarpManager\Entities\Groupe;
use LarpManager\Entities\User;

class GroupeManager
{
	/** @var \Silex\Application */
	protected $app;
	
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	
	/**
	 * Fourni la liste de tous les groupes classé par numéro
	 */
	public function findAllOrderByNumero()
	{
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\Groupe');
		
		$query = $repo->createQueryBuilder('g')
				->orderBy('g.numero','ASC')
				->getQuery();
		
		$groupes = $query->getResult();
		return $groupes;
	}
	
	/**
	 * Trouve un groupe en fonction de son code
	 * 
	 * @param string $code
	 * @return LarpManager\Entities\Groupe $groupe
	 */
	public function findByCode($code)
	{
		$query = $this->app['orm.em']->createQuery('SELECT g FROM LarpManager\Entities\Groupe g WHERE g.code = :code');
		$query->setParameter('code',$code);
		$groupes = $query->getResult();
		
		// aucun groupe avec ce code
		if ( count($groupes) == 0 )
		{
			return null;
		}
			
		// plusieurs groupe avec le même code. Ne devrait jamais arriver
		if ( count($groupes) > 1 )
		{
			return null;
		}

		return reset($groupes);
	}
	
	/**
	 * Trouve le ou les groupes dont l'utilisateur est membre
	 * 
	 * @param LarpManager\Entities\User $user
	 * @return boolean
	 */
	public function findUserGroupe(User $user)
	{
		return $user->groupes();
	}
	
	/**
	 * Determine si l'utilisateur est le responsable du groupe
	 * 
	 * @param LarpManager\Entities\User $user
	 * @param LarpManager\Entities\Groupe $groupe
	 * @return boolean
	 */
	public function isResponsable(User $user, Groupe $groupe)
	{
		return $groupe->getResponsable() == $user;
	}
	
	/**
	 * Ajoute un utilisateur dans un groupe
	 * 
	 * @param LarpManager\Entities\User $user
	 * @param LarpManager\Entities\Groupe $groupe
	 */
	public function addOnGroupe(User $user, Groupe $groupe)
	{
		$groupe->addUser($user);
		
		$this->app['orm.em']->persist($groupe);
		$this->app['orm.em']->flush();
	}
}