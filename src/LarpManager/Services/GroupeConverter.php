<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\GroupeConverter
 */
class GroupeConverter
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
     * Fourni un groupe à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Groupe
     */
    public function convert($id)
    {
    	$groupe = $this->em->find('\LarpManager\Entities\Groupe',(int) $id);
    	
        if ( null === $groupe ) {
            throw new NotFoundHttpException(sprintf('Le groupe %d n\'existe pas', $id));
        }

        return $groupe;
    }
}