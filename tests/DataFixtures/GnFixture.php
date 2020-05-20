<?php
namespace Tests\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class GnFixture implements FixtureInterface
{
    var $userArray;
    public function __construct($userArray)
    {
      $this->userArray = $userArray;
    }
    public function load(ObjectManager $manager)
    {
      $gnArray = array();
      for($i=1; $i<=4 ;$i++)
      {
          $gn = new \LarpManager\Entities\Gn();
          $gn->setLabel("TestGN ".$i);
          $gn->setActif(true);
          $topic = new \LarpManager\Entities\Topic();
          $topic->setTitle("TestGN Topic ".$i);
          $manager->persist($topic);
          $manager->flush();
          
          $gn->setTopic($topic);

          $gnArray[] = $gn;
          
          $billet = new \LarpManager\Entities\Billet();
          $billet->setGn($gn);
          $billet->setCreateur($this->userArray["admin"][0]);
          $billet->setLabel("Billet ".$gn->getLabel());

          $manager->persist($billet);
          $manager->persist($gn);
          $manager->flush();
          
          $manager->refresh($gn); //Sinon la dependance billet n'est pas a jour...
          
      }
      
      return $gnArray;
    }
}
?>