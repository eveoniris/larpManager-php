<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\LieuConverter
 */
class LieuConverter
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
     * Fourni un lieu à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Membre
     */
    public function convert($id)
    {
    	$lieu = $this->em->find('\LarpManager\Entities\Lieu',(int) $id);
    	
        if ( null === $lieu ) {
            throw new NotFoundHttpException(sprintf('Le lieu %d n\'existe pas', $id));
        }

        return $lieu;
    }
}