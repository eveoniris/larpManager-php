<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\Priere
 */
class PriereConverter
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
     * Fourni une priere à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Domaine
     */
    public function convert($id)
    {
    	$priere = $this->em->find('\LarpManager\Entities\Priere',(int) $id);
    	
        if (null === $priere) {
            throw new NotFoundHttpException(sprintf('La priere %d n\'existe pas', $id));
        }

        return $priere;
    }
}