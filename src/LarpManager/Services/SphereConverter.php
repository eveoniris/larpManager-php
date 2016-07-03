<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\Sphere
 */
class SphereConverter
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
     * Fourni une sphere à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Domaine
     */
    public function convert($id)
    {
    	$sphere = $this->em->find('\LarpManager\Entities\Sphere',(int) $id);
    	
        if (null === $sphere) {
            throw new NotFoundHttpException(sprintf('La sphere %d n\'existe pas', $id));
        }

        return $sphere;
    }
}