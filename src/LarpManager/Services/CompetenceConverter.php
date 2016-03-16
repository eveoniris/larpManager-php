<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\CompetenceConverter
 */
class CompetenceConverter
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
     * Fourni une competence à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Competence
     */
    public function convert($id)
    {
    	$competence = $this->em->find('\LarpManager\Entities\Competence',(int) $id);
    	
        if ( null === $competence ) {
            throw new NotFoundHttpException(sprintf('La competence %d n\'existe pas', $id));
        }

        return $competence;
    }
}