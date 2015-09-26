<?php

namespace LarpManager\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserConverter
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function convert($id)
    {
    	$user = $this->em->find('\LarpManager\Entities\User',(int) $id);
    	
        if (null === $user) {
            throw new NotFoundHttpException(sprintf('User %d n\'existe pas', $id));
        }

        return $user;
    }
}