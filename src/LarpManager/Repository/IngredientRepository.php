<?php

namespace LarpManager\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Repository\IngredientRepository
 *  
 * @author Kevin F.
 */
class IngredientRepository extends EntityRepository
{
	/**
	 * Find all ingredients ordered by label
	 * @return ArrayCollection $ingredients
	 */
	public function findAllOrderedByLabel()
	{
		$ingredients = $this->getEntityManager()
				->createQuery('SELECT i FROM LarpManager\Entities\Ingredient i ORDER BY i.label ASC, i.niveau ASC')
				->getResult();
		
		return $ingredients;
	}
}