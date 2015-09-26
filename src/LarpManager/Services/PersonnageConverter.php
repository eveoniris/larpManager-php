<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PersonnageConverter
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function convert($id)
    {
    	$personnage = $this->em->find('\LarpManager\Entities\Personnage',(int) $id);
    	
        if (null === $personnage) {
            throw new NotFoundHttpException(sprintf('Personnnage %d n\'existe pas', $id));
        }

        return $personnage;
    }
}