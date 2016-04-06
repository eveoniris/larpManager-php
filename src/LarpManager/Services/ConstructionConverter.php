<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\ConstructionConverter
 */
class ConstructionConverter
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
     * Fourni une construction à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Construction
     */
    public function convert($id)
    {
    	$construction = $this->em->find('\LarpManager\Entities\Construction',(int) $id);
    	
        if ( null === $construction ) {
            throw new NotFoundHttpException(sprintf('La construction %d n\'existe pas', $id));
        }

        return $construction;
    }
}