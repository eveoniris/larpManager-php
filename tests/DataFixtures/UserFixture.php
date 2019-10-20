<?php 
namespace LarpManager\Tests\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class UserFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        {
            $user = new \LarpManager\Entities\User("testuser@toto.com");
            //$encoder = $app['security.encoder_factory']->getEncoder($user);
            $user->setPassword("1234");
            $user->setUsername("testuser");
            $user->setRoles(array('ROLE_USER'));
            $user->setCreationDate(new \Datetime('NOW'));
            $user->setIsEnabled(true);
            $manager->persist($user);
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
        }
        
        $manager->flush();
    }
}

?>