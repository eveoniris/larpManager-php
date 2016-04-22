<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\PersonnageReligionConverter
 */
class PersonnageLangueConverter
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
    	$personnageLangue = $this->em->find('\LarpManager\Entities\PersonnageLangues',(int) $id);
    	
        if (null === $personnageLangue) {
            throw new NotFoundHttpException(sprintf('La langue %d n\'existe pas', $id));
        }

        return $personnageLangue;
    }
}