<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\Domaine
 */
class DomaineConverter
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
     * Fourni un domaine de magie à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Domaine
     */
    public function convert($id)
    {
    	$domaine = $this->em->find('\LarpManager\Entities\Domaine',(int) $id);
    	
        if (null === $domaine) {
            throw new NotFoundHttpException(sprintf('Le domaine de magie %d n\'existe pas', $id));
        }

        return $domaine;
    }
}