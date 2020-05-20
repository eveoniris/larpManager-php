<?php
namespace Tests;

use Silex\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Doctrine\ORM\Tools\SchemaTool;

// Classe de base, a deriver
// reimplanter setupData pour creer ses donnes, au besoin
class BaseTestCase extends WebTestCase
{

    protected $app;

    // Fonctions utilisateur (vous pouvez creer vos fonctions ici)

    /**
     * Execute le login d'un utilisateur qui est enregistre
     * (via une UserFixture par exemple)
     *
     * @param $client le
     *            client connecte
     * @param $username un
     *            utilisateur qui existe reellement dans la base de test
     * @return $user User Utilisateur loggue
     */
    public function LogIn($client, $username)
    {
        $session = $this->app['session'];

        $repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\User');
        $user = $repo->findOneBy(array(
            'username' => $username
        ));

        {
            $firewallName = 'secured_area';
            $firewallContext = 'secured_area';
            $token = new UsernamePasswordToken($user, null, $firewallName, [
                'ROLE_USER'
            ]);
            $session->set('_security_' . $firewallContext, serialize($token));
        }
        {
            $firewallName = 'public_area';
            $firewallContext = 'public_area';
            $token = new UsernamePasswordToken($user, null, $firewallName, [
                'ROLE_USER'
            ]);
            $session->set('_security_' . $firewallContext, serialize($token));
        }

        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
        $this->assertNotNull($user,"User is not logged");
        return $user;
    }

    // Fin des fonctions utilisateur

    // Fonctions appelees par WebTestCase (ne pas toucher sauf probleme)

    /**
     * Creation de l'application de test
     */
    public function createApplication()
    {
        // app.php must return an Application instance
        $this->app = require __DIR__ . '/../app/bootstrap.php';
        unset($this->app['exception_handler']); // afficher les erreurs directement dans la console
        $this->app['session.test'] = true;

        $this->generateSchema($this->app);
        $this->setupData($this->app);

        return $this->app;
    }

    // A reimplanter au besoin par les tests derives
    public function setupData()
    {
        // Pour reimplanter via classe derivee
    }

    /**
     * Appele a la fin
     */
    public function tearDown()
    {
        $this->dropSchema($this->app);
        return parent::tearDown();
    }

    /* Detruit la base de donnees */
    private function dropSchema($app)
    {
        if(!$this->checkDatabaseIsTest())
            throw new Exception("Connexion a la mauvaise DB (drop)");
        $metadata = $this->getMetadata($app);
        if (! empty($metadata)) {
            // Create SchemaTool
            $tool = new SchemaTool($this->app['orm.em']);
            $tool->dropSchema($metadata);
        } else {
            throw new Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
    }

    /**
     * Les deux prochaines methodes permettent de creer
     * le modele de donnee en base
     */
    private function getMetadata($app)
    {
        return $this->app['orm.em']->getMetadataFactory()->getAllMetadata();
    }

    private function generateSchema($app)
    {
        if(!$this->checkDatabaseIsTest())
            throw new Exception("Connexion a la mauvaise DB (create)");
        $metadata = $this->getMetadata($app);
        if (! empty($metadata)) {
            // Create SchemaTool
            $tool = new SchemaTool($app['orm.em']);
            $tool->createSchema($metadata);
        } else {
            throw new Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
    }
    
    private function checkDatabaseIsTest()
    {
        return !isset($app['config']['database']['host']); //in memory donc null
    }
}

