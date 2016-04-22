<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\PersonnageReligionConverter
 */
class PersonnageReligionConverter
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
     * Fourni la religion d'un personnage à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\PersonnageReligion
     */
    public function convert($id)
    {
    	$personnageReligion = $this->em->find('\LarpManager\Entities\PersonnagesReligions',(int) $id);
    	
        if (null === $personnageReligion) {
            throw new NotFoundHttpException(sprintf('La religione %d n\'existe pas', $id));
        }

        return $personnageReligion;
    }
}