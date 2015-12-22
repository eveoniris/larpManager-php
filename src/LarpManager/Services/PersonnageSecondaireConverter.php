<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\PersonnageSecondaireConverter
 */
class PersonnageSecondaireConverter
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
     * Fourni un personnage secondaire à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\PersonnageSecondaire
     */
    public function convert($id)
    {
    	$personnageSecondaire = $this->em->find('\LarpManager\Entities\PersonnageSecondaire',(int) $id);
    	
        if ( null === $personnageSecondaire ) {
            throw new NotFoundHttpException(sprintf('Le personnnage secondaire %d n\'existe pas', $id));
        }

        return $personnageSecondaire;
    }
}