<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\Potion
 */
class PotionConverter
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
     * Fourni une potion à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Domaine
     */
    public function convert($id)
    {
    	$potion = $this->em->find('\LarpManager\Entities\Potion',(int) $id);
    	
        if (null === $potion) {
            throw new NotFoundHttpException(sprintf('La potion %d n\'existe pas', $id));
        }

        return $potion;
    }
}