<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

class CompetenceRepository extends EntityRepository
{
	public function findAllOrderedByLabel()
	{
		return $this->getEntityManager()
				->createQuery('SELECT c FROM LarpManager\Entities\Competence c JOIN c.competenceFamily cf JOIN c.level l ORDER BY cf.label ASC, l.index ASC')
				->getResult();

	}
}