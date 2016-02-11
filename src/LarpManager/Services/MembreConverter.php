<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\MembreConverter
 */
class MembreConverter
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
     * Fourni un membre groupe secondaire à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Membre
     */
    public function convert($id)
    {
    	$membre = $this->em->find('\LarpManager\Entities\Membre',(int) $id);
    	
        if ( null === $membre ) {
            throw new NotFoundHttpException(sprintf('Le membre %d n\'existe pas', $id));
        }

        return $membre;
    }
}