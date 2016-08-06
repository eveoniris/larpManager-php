<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\PersonnageTokenConverter
 */
class PersonnageTokenConverter
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
     * Fourni un personnage à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Personnage
     */
    public function convert($id)
    {
    	$personnageToken = $this->em->find('\LarpManager\Entities\PersonnageToken',(int) $id);
    	
        if (null === $personnageToken) {
            throw new NotFoundHttpException(sprintf('PersonnnageToken %d n\'existe pas', $id));
        }

        return $personnageToken;
    }
}