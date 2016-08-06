<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\TokenRepository
 * 
 * @author kevin
 */
class TokenRepository extends EntityRepository
{
	/**
	 * Fourni tous les tokens classé par ordre alphabétique
	 */
	public function findAllOrderedByLabel()
	{
		return $this->findBy(array(), array('label' => 'ASC'));
	}
}