<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

class CompetenceFamilyRepository extends EntityRepository
{
	public function findAllOrderedByLabel()
	{
		return $this->getEntityManager()
				->createQuery('SELECT cf FROM LarpManager\Entities\CompetenceFamily cf ORDER BY cf.label ASC')
				->getResult();
	}
}