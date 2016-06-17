<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\Sort
 */
class SortConverter
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
     * Fourni un sortilège à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Domaine
     */
    public function convert($id)
    {
    	$sort = $this->em->find('\LarpManager\Entities\Sort',(int) $id);
    	
        if (null === $sort) {
            throw new NotFoundHttpException(sprintf('Le sortilège %d n\'existe pas', $id));
        }

        return $sort;
    }
}