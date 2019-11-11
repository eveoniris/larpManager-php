<?php 
namespace Tests\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class SecondaryGroupFixture implements FixtureInterface
{
    var $user;
    var $label;
    
    public function __construct($user=null, $label="TestSecGr")
    {
        $this->user = $user;
        $this->label = $label;
    
    }
    
    public function load(ObjectManager $manager)
    {
        $secondaryGroup = new \LarpManager\Entities\SecondaryGroup();
        $secondaryGroup->setLabel($this->label);
        
        $secondaryGroupType = new \LarpManager\Entities\SecondaryGroupType();
        $secondaryGroupType->setLabel("SecType 1");
        $manager->persist($secondaryGroupType);
        $manager->flush();
        
        $secondaryGroup->setSecondaryGroupType($secondaryGroupType);
        
        $topic = new \LarpManager\Entities\Topic();
        $topic->setTitle($this->label);
        //$topic->setUser($user);
        $manager->persist($topic);
        $manager->flush();
        
        $secondaryGroup->setTopic($topic);
        
        $manager->persist($secondaryGroup);
        $manager->flush();
        
        return $secondaryGroup;
    }
}
?>