<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\TokenConverter
 */
class TokenConverter
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
     * Fourni un token à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Background
     */
    public function convert($id)
    {
    	$token = $this->em->find('\LarpManager\Entities\Token',(int) $id);
    	
        if (null === $token) {
            throw new NotFoundHttpException(sprintf('Le token %d n\'existe pas', $id));
        }

        return $token;
    }
}