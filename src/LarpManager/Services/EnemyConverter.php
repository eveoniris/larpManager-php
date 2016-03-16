<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\EnemyConverter
 */
class EnemyConverter
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
     * Fourni une alliance à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\GroupeEnemy
     */
    public function convert($id)
    {
    	$war = $this->em->find('\LarpManager\Entities\GroupeEnemy',(int) $id);
    	
        if (null === $war) {
            throw new NotFoundHttpException(sprintf('La déclaration de guerre %d n\'existe pas', $id));
        }

        return $war;
    }
}