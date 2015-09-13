<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\CompetenceRepository
 * @author kevin
 *
 */
class CompetenceRepository extends EntityRepository
{
	/**
	 * Find all competences ordered by label
	 * @return ArrayCollection $competences
	 */
	public function findAllOrderedByLabel()
	{
		$competences = $this->getEntityManager()
				->createQuery('SELECT c FROM LarpManager\Entities\Competence c JOIN c.competenceFamily cf JOIN c.level l ORDER BY cf.label ASC, l.index ASC')
				->getResult();
		
		return $competences;

	}
}