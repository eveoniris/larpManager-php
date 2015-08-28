<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

class ReligionRepository extends EntityRepository
{
	public function findAllOrderedByLabel()
	{
		return $this->getEntityManager()
				->createQuery('SELECT r FROM LarpManager\Entities\Religion r ORDER BY r.label ASC')
				->getResult();

	}
}