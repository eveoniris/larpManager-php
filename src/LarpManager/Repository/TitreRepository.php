<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\TitreRepository
 *
 * @author kevin
 */
class TitreRepository extends EntityRepository
{
	/**
	 * Trouve tous les titres classé par renommé
	 * @return ArrayCollection $sorts
	 */
	public function findByRenomme()
	{
		$titres = $this->getEntityManager()
			->createQuery('SELECT t FROM LarpManager\Entities\Titre t ORDER BY t.renomme ASC')
			->getResult();

		return $titres;
	}
}