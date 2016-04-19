<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\ReligionConverter
 */
class ReligionConverter
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
     * Fourni une religion à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Religion
     */
    public function convert($id)
    {
    	$religion = $this->em->find('\LarpManager\Entities\Religion',(int) $id);
    	
        if ( null === $religion ) {
            throw new NotFoundHttpException(sprintf('La religion %d n\'existe pas', $id));
        }

        return $religion;
    }
}