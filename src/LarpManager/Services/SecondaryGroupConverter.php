<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\SecondaryGroupConverter
 */
class SecondaryGroupConverter
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
     * @return LarpManager\Entities\SecondaryGroup
     */
    public function convert($id)
    {
    	$groupeSecondaire = $this->em->find('\LarpManager\Entities\SecondaryGroup',(int) $id);
    	
        if ( null === $groupeSecondaire ) {
            throw new NotFoundHttpException(sprintf('Le groupe secondaire %d n\'existe pas', $id));
        }

        return $groupeSecondaire;
    }
}