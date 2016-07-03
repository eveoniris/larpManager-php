<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\Titre
 */
class TitreConverter
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
     * Fourni un titre à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Titre
     */
    public function convert($id)
    {
    	$titre = $this->em->find('\LarpManager\Entities\Titre',(int) $id);
    	
        if (null === $titre) {
            throw new NotFoundHttpException(sprintf('Le titre %d n\'existe pas', $id));
        }

        return $titre;
    }
}