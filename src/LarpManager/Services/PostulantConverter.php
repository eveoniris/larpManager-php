<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\PostulantConverter
 */
class PostulantConverter
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
     * Fourni un groupe secondaire à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Postulant
     */
    public function convert($id)
    {
    	$postulant = $this->em->find('\LarpManager\Entities\Postulant',(int) $id);
    	
        if ( null === $postulant ) {
            throw new NotFoundHttpException(sprintf('Le postulant %d n\'existe pas', $id));
        }

        return $postulant;
    }
}