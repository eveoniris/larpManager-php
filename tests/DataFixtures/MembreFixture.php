<?php
namespace Tests\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class MembreFixture implements FixtureInterface
{
    var $persoArray;
    var $secondaryGroup;
    public function __construct($persoArray, $secondaryGroup)
    {
        $this->persoArray = $persoArray;
        $this->secondaryGroup = $secondaryGroup;
    }
    public function load(ObjectManager $manager)
    {
        $membreArray = array();
        if(is_array($this->persoArray))
        {
            foreach ($this->persoArray as $perso)
            {
                $membre = new \LarpManager\Entities\Membre();
                $membre->setPersonnage($perso);
                $membre->setSecondaryGroup($this->secondaryGroup);
                $manager->persist($membre);
                $manager->flush();
                $membreArray[] = $membre;
            }
        }
        
        
        return $membreArray;
    }
}
?>