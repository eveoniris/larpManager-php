<?php

namespace Tests\Functional\GroupeSecondaire;

use Tests\BaseTestCase;
use Tests\DataFixtures\UserFixture;
use Tests\DataFixtures\SecondaryGroupFixture;
use Tests\DataFixtures\PersonnageFixture;
use Tests\DataFixtures\GnFixture;
use Tests\DataFixtures\MembreFixture;
use LarpManager;
// //
class GroupeSecondaireVisibiliteTest extends BaseTestCase
{
 	protected $app;
	
 	var $respId=null;
 	var $groupeSecId=null;
 	var $gnArray=null;
 	var $personnageFixture = null;
 	var $userArray = null;
 	
 	public function setupData()
 	{
 	    $client = static::createClient();
 	    
        $userfixture = new UserFixture();
        $this->userArray = $userfixture->load($this->app["orm.em"]);
        
        $gnFixture = new GnFixture($this->userArray);
        $this->gnArray = $gnFixture->load($this->app["orm.em"]);
        
        $secGroupFixture = new SecondaryGroupFixture();
        $secondaryGroup = $secGroupFixture->load($this->app["orm.em"]);
        $this->groupeSecId = $secondaryGroup->getId();
        
        $this->personnageFixture = new PersonnageFixture($this->gnArray[0], $this->userArray);
        $personnageArray = $this->personnageFixture->load($this->app["orm.em"]);
        
        $persoGroupeSecondaireArray = array_slice($personnageArray,0,3);
        
        $membreFixture = new MembreFixture($persoGroupeSecondaireArray, $secondaryGroup);
        $membreFixture->load($this->app["orm.em"]);
        $secondaryGroup->setResponsable($personnageArray[0]);
        $this->app['orm.em']->persist($secondaryGroup);
        $this->app['orm.em']->flush();
        $this->app['orm.em']->clear(); //necessaire de recharger apres pour les tables liees...
 	}
 	
 	//Trois personnages rejoingnent un groupe secondaire sur le GN 1, il y a un responsable.
 	//Deux autres personnages existent mais ne sont pas dans le groupe secondaire.
 	
 	public function testVisibiliteOneGN()
 	{
 	    $client = static::createClient();
 	    $this->app['monolog']->info("Begin testVisibiliteOne test");
 	    $gn = $this->gnArray[0];
 	    $gnId = $gn->getId();
  		$resp = $this->LogIn($client, "testuser1"); //$user[0]
   		$respId = $resp->getId();
        
        
 		
 	    //tout le monde a son perso, tout va bien
 	    {
 	        $this->app['orm.em']->clear(); 
      	    $resp = $this->app['orm.em']->find('\LarpManager\Entities\User',$respId);
      		$gn = $this->app['orm.em']->find('\LarpManager\Entities\Gn',$gnId);
      		$crawler = $client->request('GET','/participant/'.$resp->getParticipant($gn)->getId()
      		                                                 ."/groupeSecondaire/".$this->groupeSecId
      		                                                 ."/detail");
      		$this->assertTrue($client->getResponse()->isOk());
      		$this->assertCount(3,$crawler->filter('span:contains("Inscrit")'));
 	    }
 	    $this->app['monolog']->info("End testVisibiliteOne test");
 	}
 	 
 	
 	//Trois personnages rejoingnent un groupe secondaire sur le GN 1, il y a un responsable.
 	//Au GN 2, deux personnes reprennent leur personnage, mais le troisière change de personnage.
 	//Il est considéré qu'il ne fait pas partie du groupe, mais est affiché comme "pas inscrit".
 	
 	//Je fais le test en deux fois aussi parce que je n'arrive pas a mettre a jour le user du login... il reste
 	//avec un unique participant meme apres le clear sur l'orm.
 	public function testVisibiliteTwoGNDifferentPerso()
 	{
 	    $client = static::createClient();
 	    $this->app['monolog']->info("Begin testVisibiliteTwo test");
 	    $gn = $this->gnArray[1];
 	    $gnId = $gn->getId();
 	    $this->personnageFixture->nouveauPersonnage($gnId, $this->userArray["user"][2]->getId(), $this->app["orm.em"]);
 	    $this->personnageFixture->reprendPersonnage($this->gnArray[0]->getId(), $gnId, array_slice($this->userArray["user"],0,2), $this->app["orm.em"]);
 	        
        $resp = $this->LogIn($client, "testuser1"); //$user[0] 
        $respId = $resp->getId();
 	        
 	    //user 3 a change de perso meme si c'est le meme joueur, il ne doit plus s'afficher dans le groupe secondaire
 	    {
 	        $this->app['orm.em']->clear();
 	        $resp = $this->app['orm.em']->find('\LarpManager\Entities\User',$respId);
 	        $gn = $this->app['orm.em']->find('\LarpManager\Entities\Gn',$gnId);
 	        
 	        $crawler = $client->request('GET','/participant/'.$resp->getParticipant($gn)->getId()
 	            ."/groupeSecondaire/".$this->groupeSecId
 	            ."/detail");
 	        
 	        $this->assertTrue($client->getResponse()->isOk());
 	        $this->assertCount(2,$crawler->filter('span:contains("Inscrit")'));
 	        $this->assertCount(1,$crawler->filter('span:contains("Personnage pas inscrit")'));
 	    }
 	    $this->app['monolog']->info("End testVisibiliteTwo test");
 	}
 	
 	//Trois personnages rejoingnent un groupe secondaire sur le GN 1, il y a un responsable.
 	//Au GN 2, deux personnes reprennent leur personnage, mais le troisière ne s'est pas inscrit.
 	//Il est considéré qu'il ne fait pas partie du groupe, mais est affiché comme "pas inscrit".
 	public function testVisibiliteTwoGNMissingPerso()
 	{
 	    $client = static::createClient();
 	    $this->app['monolog']->info("Begin testVisibiliteTwo test");
 	    $gn = $this->gnArray[1];
 	    $gnId = $gn->getId();
 	    $this->personnageFixture->reprendPersonnage($this->gnArray[0]->getId(), $gnId, array_slice($this->userArray["user"],0,2), $this->app["orm.em"]);
 	    //3 n'est pas inscrit
 	    
 	    $resp = $this->LogIn($client, "testuser1"); //$user[0]
 	    $respId = $resp->getId();
 	    
 	    //user 3 a change de perso meme si c'est le meme joueur, il ne doit plus s'afficher dans le groupe secondaire
 	    {
 	        $this->app['orm.em']->clear();
 	        $resp = $this->app['orm.em']->find('\LarpManager\Entities\User',$respId);
 	        $gn = $this->app['orm.em']->find('\LarpManager\Entities\Gn',$gnId);
 	        
 	        $crawler = $client->request('GET','/participant/'.$resp->getParticipant($gn)->getId()
 	            ."/groupeSecondaire/".$this->groupeSecId
 	            ."/detail");
 	        
 	        $this->assertTrue($client->getResponse()->isOk());
 	        $this->assertCount(2,$crawler->filter('span:contains("Inscrit")'));
 	        $this->assertCount(1,$crawler->filter('span:contains("Personnage pas inscrit")'));
 	    }
 	    $this->app['monolog']->info("End testVisibiliteTwo test");
 	}
 	

 }

