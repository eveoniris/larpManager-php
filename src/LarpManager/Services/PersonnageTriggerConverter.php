<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\PersonnageTriggerConverter
 */
class PersonnageTriggerConverter
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
     * Fourni un trigger partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\PersonnageTrigger
     */
    public function convert($id)
    {
    	$trigger = $this->em->find('\LarpManager\Entities\PersonnageTrigger',(int) $id);
    	
        if ( null === $trigger ) {
            throw new NotFoundHttpException(sprintf('Le trigger %d n\'existe pas', $id));
        }

        return $trigger;
    }
}