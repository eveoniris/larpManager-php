<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\DocumentConverter
 */
class DocumentConverter
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
     * Fourni un lieu à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Membre
     */
    public function convert($id)
    {
    	$document = $this->em->find('\LarpManager\Entities\Document',(int) $id);
    	
        if ( null === $document ) {
            throw new NotFoundHttpException(sprintf('Le document %d n\'existe pas', $id));
        }

        return $document;
    }
}