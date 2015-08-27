<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

class ClasseRepository extends EntityRepository
{
	public function findAllOrderedByLabel()
	{
		return $this->getEntityManager()
				->createQuery('SELECT c FROM LarpManager\Entities\Classe c ORDER BY c.label_masculin ASC')
				->getResult();
	}
}