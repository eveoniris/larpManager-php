<?php 
namespace Tests\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class UserFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userArray=array();
        {
            $user = new \LarpManager\Entities\User("testuser1@toto.com");
            //$encoder = $app['security.encoder_factory']->getEncoder($user);
            $user->setPassword("1234");
            $user->setUsername("testuser1");
            $user->setRoles(array('ROLE_USER'));
            $user->setCreationDate(new \Datetime('NOW'));
            $user->setIsEnabled(true);
            $manager->persist($user);
            $this->userArray["user"][] = $user;
        }
        {
            $user = new \LarpManager\Entities\User("testuser2@toto.com");
            //$encoder = $app['security.encoder_factory']->getEncoder($user);
            $user->setPassword("1234");
            $user->setUsername("testuser2");
            $user->setRoles(array('ROLE_USER'));
            $user->setCreationDate(new \Datetime('NOW'));
            $user->setIsEnabled(true);
            $manager->persist($user);
            $this->userArray["user"][] = $user;
        }
        {
            $user = new \LarpManager\Entities\User("testuser3@toto.com");
            //$encoder = $app['security.encoder_factory']->getEncoder($user);
            $user->setPassword("1234");
            $user->setUsername("testuser3");
            $user->setRoles(array('ROLE_USER'));
            $user->setCreationDate(new \Datetime('NOW'));
            $user->setIsEnabled(true);
            $manager->persist($user);
            $this->userArray["user"][] = $user;
        }

        {
            $user = new \LarpManager\Entities\User("testuser4@toto.com");
            //$encoder = $app['security.encoder_factory']->getEncoder($user);
            $user->setPassword("1234");
            $user->setUsername("testuser4");
            $user->setRoles(array('ROLE_USER'));
            $user->setCreationDate(new \Datetime('NOW'));
            $user->setIsEnabled(true);
            $manager->persist($user);
            $this->userArray["user"][] = $user;
        }
        
        {
            $user = new \LarpManager\Entities\User("testuser5@toto.com");
            //$encoder = $app['security.encoder_factory']->getEncoder($user);
            $user->setPassword("1234");
            $user->setUsername("testuser5");
            $user->setRoles(array('ROLE_USER'));
            $user->setCreationDate(new \Datetime('NOW'));
            $user->setIsEnabled(true);
            $manager->persist($user);
            $this->userArray["user"][] = $user;
        }
        
        {
            $user = new \LarpManager\Entities\User("testadmin@toto.com");
            //$encoder = $app['security.encoder_factory']->getEncoder($user);
            $user->setPassword("1234");
            $user->setUsername("testadmin");
            $user->setRoles(array('ROLE_ADMIN'));
            $user->setCreationDate(new \Datetime('NOW'));
            $user->setIsEnabled(true);
            $manager->persist($user);
            $this->userArray["admin"][] = $user;
        }
        
        {
            $user = new \LarpManager\Entities\User("testorga@toto.com");
            //$encoder = $app['security.encoder_factory']->getEncoder($user);
            $user->setPassword("1234");
            $user->setUsername("testorga");
            $user->setRoles(array('ROLE_ORGA'));
            $user->setCreationDate(new \Datetime('NOW'));
            $user->setIsEnabled(true);
            $manager->persist($user);
            $this->userArray["orga"][] = $user;
        }
        
        $manager->flush();
        return $this->userArray;
    }
}

?>