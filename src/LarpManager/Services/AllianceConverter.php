<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\AllianceConverter
 */
class AllianceConverter
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
     * @return LarpManager\Entities\GroupeAllie
     */
    public function convert($id)
    {
    	$alliance = $this->em->find('\LarpManager\Entities\GroupeAllie',(int) $id);
    	
        if (null === $alliance) {
            throw new NotFoundHttpException(sprintf('L\'alliance %d n\'existe pas', $id));
        }

        return $alliance;
    }
}