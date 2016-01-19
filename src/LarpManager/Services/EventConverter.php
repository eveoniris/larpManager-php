<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\EventConverter
 */
class EventConverter
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
     * Fourni un événement à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Chronologie
     */
    public function convert($id)
    {
    	$event = $this->em->find('\LarpManager\Entities\Chronologie',(int) $id);
    	
        if (null === $event) {
            throw new NotFoundHttpException(sprintf('L\'événement %d n\'existe pas', $id));
        }

        return $event;
    }
}