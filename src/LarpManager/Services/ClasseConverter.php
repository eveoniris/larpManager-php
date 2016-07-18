<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\ClasseConverter
 */
class ClasseConverter
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
     * Fourni une classe à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\GroupeEnemy
     */
    public function convert($id)
    {
    	$classe = $this->em->find('\LarpManager\Entities\Classe',(int) $id);
    	
        if (null === $classe) {
            throw new NotFoundHttpException(sprintf('La classe %d n\'existe pas', $id));
        }

        return $classe;
    }
}