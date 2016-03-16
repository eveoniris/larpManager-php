<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\TerritoireConverter
 */
class TerritoireConverter
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
     * Fourni un territoire à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Territoire
     */
    public function convert($id)
    {
    	$territoire = $this->em->find('\LarpManager\Entities\Territoire',(int) $id);
    	
        if (null === $territoire) {
            throw new NotFoundHttpException(sprintf('Territoire %d n\'existe pas', $id));
        }

        return $territoire;
    }
}