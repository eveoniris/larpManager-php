<?php 
namespace Tests\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class PersonnageFixture implements FixtureInterface
{
    var $userArray;
    var $gn;
    var $classeId;
    var $ageId;
    var $genreId;
    //Utiliser le retour de UserFixture
    public function __construct($gn, $userArray)
    {
        $this->userArray = $userArray;
        $this->gn = $gn;
    }
    
    public function load(ObjectManager $manager)
    {
        $personnageArray = array();
        if(is_array($this->userArray))
        {
          $classe = new \LarpManager\Entities\Classe();
          $manager->persist($classe);
          $manager->flush();
          $this->classeId=$classe->getId();
          
          $age = new \LarpManager\Entities\Age();
          $age->setLabel("ok Starbuck");
          $age->setEnableCreation(true);
          $age->setMinimumValue(15);
          $manager->persist($age);
          $manager->flush();
          $this->ageId=$age->getId();

          $genre = new \LarpManager\Entities\Genre();
          $genre->setLabel("Genra");
          $manager->persist($genre);
          $manager->flush();
          $this->genreId=$genre->getId();
          
          foreach ($this->userArray["user"] as $user)
          {
              
            $participant = new \LarpManager\Entities\Participant();
            $participant->setUser($user);
            
            $participant->setGn($this->gn);
            
            $billet = $this->gn->getBillets()[0];
            $participant->setBillet($billet);
            
            $personnage = new \LarpManager\Entities\Personnage();
            $personnage->setUser($user);
            $personnage->setNom("perso " . $user->getName());
            $personnage->setClasse($classe);
            $personnage->setAge($age);
            $personnage->setGenre($genre);
            
            $manager->persist($personnage);
            $manager->flush();
            
            $participant->setPersonnage($personnage);
            $manager->persist($participant);
            $manager->flush();
            
            $personnageArray[] = $personnage;
            
          }
        }
        return $personnageArray;        
   }
   
   public function nouveauPersonnage($gnId,$userId,$manager)
   {
       $participant = new \LarpManager\Entities\Participant();
       $user = $manager->find('\LarpManager\Entities\User',$userId);
       $participant->setUser($user);
       $gn = $manager->find('\LarpManager\Entities\Gn',$gnId);
       $participant->setGn($gn);
       $billet = $gn->getBillets()[0];
       $participant->setBillet($billet);
       
       
       $personnage = new \LarpManager\Entities\Personnage();
       $personnage->setUser($user);
       $personnage->setNom("perso " . $user->getName() .$gn->getLabel());
       
       $personnage->setClasse($manager->find('\LarpManager\Entities\Classe',$this->classeId));
       $personnage->setAge($manager->find('\LarpManager\Entities\Age',$this->ageId));
       $personnage->setGenre($manager->find('\LarpManager\Entities\Genre',$this->genreId));
       
       $manager->persist($personnage);
       $manager->flush();
       
       $participant->setPersonnage($personnage);
       $manager->persist($participant);
       $manager->flush();
   }
   
   public function reprendPersonnage($oldGnId, $gnId,$userArray,$manager)
   {
       if(is_array($userArray))
       {
           foreach ($userArray as $user)
           {
               $participant = new \LarpManager\Entities\Participant();
               $user = $manager->find('\LarpManager\Entities\User',$user->getId());
               $participant->setUser($user);
               $oldGn = $manager->find('\LarpManager\Entities\Gn',$oldGnId);
               $gn = $manager->find('\LarpManager\Entities\Gn',$gnId);
               $participant->setGn($gn);
               $billet = $gn->getBillets()[0];
               $participant->setBillet($billet);
               
               $personnage = $manager->find('\LarpManager\Entities\Personnage', $user->getParticipant($oldGn)->getPersonnage()->getId());
               
               $participant->setPersonnage($personnage);
               $manager->persist($participant);
               $manager->flush();
           }
       }
   
   }
   
}
?>