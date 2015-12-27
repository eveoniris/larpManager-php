<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\BackgroundConverter
 */
class BackgroundConverter
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
     * Fourni un background à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Background
     */
    public function convert($id)
    {
    	$background = $this->em->find('\LarpManager\Entities\Background',(int) $id);
    	
        if (null === $background) {
            throw new NotFoundHttpException(sprintf('Le background %d n\'existe pas', $id));
        }

        return $background;
    }
}