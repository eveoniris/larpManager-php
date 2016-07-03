<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\Ingredient
 */
class IngredientConverter
{
	/**
	 * 
	 * @var EntityManager
	 */
    private $em;

    /**
     * Constructeur
     * 
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Fourni un ingredient à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Titre
     */
    public function convert($id)
    {
    	$ingredient = $this->em->find('\LarpManager\Entities\Ingredient',(int) $id);
    	
        if (null === $ingredient) {
            throw new NotFoundHttpException(sprintf('L\'ingredient %d n\'existe pas', $id));
        }

        return $ingredient;
    }
}