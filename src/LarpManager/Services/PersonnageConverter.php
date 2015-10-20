<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\PersonnageConverter
 */
class PersonnageConverter
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
    	$personnage = $this->em->find('\LarpManager\Entities\Personnage',(int) $id);
    	
        if (null === $personnage) {
            throw new NotFoundHttpException(sprintf('Personnnage %d n\'existe pas', $id));
        }

        return $personnage;
    }
}