<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LarpManager\Services\DebriefingConverter
 */
class DebriefingConverter
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
     * Fourni un  debriefing à partir de son identifiant
     * 
     * @param unknown $id
     * @throws NotFoundHttpException
     * @return LarpManager\Entities\Debriefing
     */
    public function convert($id)
    {
    	$debriefing = $this->em->find('\LarpManager\Entities\Debriefing',(int) $id);
    	
        if (null === $debriefing) {
            throw new NotFoundHttpException(sprintf('Le debriefing %d n\'existe pas', $id));
        }

        return $debriefing;
    }
}