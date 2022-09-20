<?php

namespace LarpManager\Services\Converter;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\Converter\Connaissance
 */
class ConnaissanceConverter
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
     * Fourni une potion à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Connaissance
     */
    public function convert($id)
    {
    	$connaissance = $this->em->find('\LarpManager\Entities\Connaissance',(int) $id);
    	
        if (null === $connaissance) {
            throw new NotFoundHttpException(sprintf('La connaissance %d n\'existe pas', $id));
        }

        return $connaissance;
    }
}