<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

class LangueRepository extends EntityRepository
{
	public function findAllOrderedByLabel()
	{
		return $this->getEntityManager()
				->createQuery('SELECT l FROM LarpManager\Entities\Langue l ORDER BY l.label ASC')
				->getResult();

	}
}