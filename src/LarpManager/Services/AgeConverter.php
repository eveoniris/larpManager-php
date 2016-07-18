<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\AgeConverter
 */
class AgeConverter
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
     * Fourni une alliance à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\GroupeEnemy
     */
    public function convert($id)
    {
    	$age = $this->em->find('\LarpManager\Entities\Age',(int) $id);
    	
        if (null === $age) {
            throw new NotFoundHttpException(sprintf('L\'age %d n\'existe pas', $id));
        }

        return $age;
    }
}