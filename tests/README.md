# Tests

Voici le répertoire pour créer vos tests.
Ces tests utilisent [PHPUnit](https://phpunit.de/)
La version utilisées est visible dans Composer.

# Comment lancer les tests

PHPUnit est installé dans vendor/phpunit.
Nous allons supposer que vous utilisez Eclipse.

> Il faut que sqlite soit disponible pour votre installation PHP. Pensez à installer sqlite et à vérifier sqlite.ini ou php.ini afin que le module soit bien chargé.

Il y a deux façons de lancer les tests

### Via la console
Il vous faut lancer phpunit.
Le plus simple est de configurer Eclipse via une commande externe mais il est possible de lancer la commande directement dans la console.

Pour crééer une commande externe, aller dans Run -> External Tools -> External Tools Configurations...

Créer une nouvelle commande et rentrer les paramètres suivants :

* Location :
Mettre le chemin de PHP. Par exemple, le chemin de PHP de votre installation de serveur local
* Working Directory : 
Mettre le chemin de la racine de LarpManager. Par exemple : ${workspace_loc:/larpManager-php} mais cela peut varier selon votre configuration
* Arguemnts : vendor\phpunit\phpunit\phpunit -c app/

Lancer cette commande lancera tous les tests dans la console.

### Via une debug ou run configuration
L'avantage de cette méthode par rapport à la précédente est que vous pouvez lancer vos tests en mode debug. Plus un tableau de résumé des tests réussis / échoués un peu plus joli.

Je me baserai sur les configurations de debug mais cela configurera de toutes façons aussi une configuration de run en même temps.

Allez dans Debug -> Debug Configurations...
Vous devriez avoir une option "PHPUnit".
Créez une nouvelle configuration.

Dans l'onglet PHPUnit

Cochez le radio "Run all tests in the selected project, source folder or file:" puis la racine de votre projet (par exemple, /larpManager-php)

Sélectionnez dans Execution parameters "Use project's PHPUnit (Composer)"

Dans l'onglet PHP Script

Runtime PHP :
Sélectionnez Project default (mais vous pouvez si vous le souhaitez choisir une autre version de PHP)

Script Arguments : -c app/

Attention à bien choisir un debugger dans Debugger si vous voulez pouvoir debugger. Par exemple, Xdebug.

C'est tout.
Pour lancer les tests, il vous suffit en suite de choisir Run ou Debug de cette configuration.

> Ne pas oublier le paramètre -c app/ quoi qu'il en soit. C'est ce qui va permettre à PHPUnit de trouver les tests.

#Comment faire les tests

Vous pouvez regarder les exemples dans Functional et Unit.
Les tests unitaires sont censé testé des classes unitairement.
Les tests fonctionnels sont normalement utilisé afin d'executer des scénarios.

Chaque test hérite de BaseTestCase ou de WebTestCase

    class XXXControllerTest extends BaseTestCase 
    class XXXEntityTest extends BaseTestCase 
    class XXXControllerTest extends WebTestCase 
    class XXXEntityTest extends WebTestCase 

BaseTestCase propose quelques méthodes pouvant vous aider telles que

    public function LogIn($client, $username)
qui permet de logguer un utilisateur créé via une UserFixture.

ou 

    public Function createApplication()

qui permet de creer l'application de test.

Si vous utilisez directement WebTestCase (qui est le test case PHPUnit de base) vous devrez redéfinir au moins certaines méthodes telles que createApplication et tearDown

### Tests non connectés
Il vous suffit d'héritez de la classe BaseTestCase.
L'application sera automatiquement créée et vous serez appelé sur chaque méthode qui commence par testXXX.
Par exemple

    <?php
    namespace LarpManager\Tests\Controller;

    require_once(__DIR__ . "\..\..\BaseTestCase.php");
    
    use LarpManager\Tests\BaseTestCase;

    class XXXControllerTest extends BaseTestCase
    {
    	public function testIndex()
	{
            //Votre test
            $this->assertTrue($myExpression);
	}
    }

Pour plus d'informations sur les méthodes de test de phpunit, vous pouvez regarder la doc de PHPUnit

### Tests connectés

Vous disposez de quelques méthodes pour faire les tests connectés.

Premièrement, une base est automatiquement créée dans un sqlite "in memory", c'est donc relativement rapide.
La base est créée et détruite pour chaque classe de test.
Vous pouvez créer un client puis faire des requêtes

    $client = static::createClient();
    $crawler = $client->request('GET','/');
    $this->assertTrue($client->getResponse()->isOk());

Vous pouvez aussi créer des utilisateurs et les logguer

    $fixture = new UserFixture();
    $fixture->load($this->app["orm.em"]);
    $client = static::createClient();
    $client = $this->LogIn($client, "testuser");

Vous pouvez créer vos propres "fixtures" dans le répertoire DataFixtures.
Les fixtures sont des entitées Silex que vous pouvez persister en base.

L'utilisateur loggué par LogIn doit avoir été créé par une fixture.

# Fonctionnement interne

Les tests se basent sur la configuration recommandée par Silex
https://silex.symfony.com/doc/1.3/testing.html

Entre autre, il existe un fichier phpunit.xml.dist qui est dans /app et qui contient les paramètres de lancement des tests.

* L'environnement de test est sélectionné dans bootstrap.php via 
```
    if(isset($_ENV['env']) && $_ENV['env'] == 'test')
    {
        $app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . '/../config/test_settings_sqlite.yml'));
    }
```
On peut ainsi voir dans test\_settings\_sqlite.yml la configuration de test de l'application

* Le login utilise les deux firewalls définis par l'application : secured\_area et public\_area.
    
# Un dernier mot
* Chaque commit devrait être fait si et seulement si aucun test n'est cassé... Il faut donc rejouer tous les tests avant un merge branch...
* Il est possible d'utiliser Selenium ou d'autres émulateurs de browser pour faire les tests fonctionnels.
Mais l'approche ici est de tester le contenu de la page cible en cherchant différentes chaines de texte ou balise, et non de tester le rendu.
* L'infrastructure de test est assez embryonnaire, si vous avez des super idées pour étoffer la chose allez-y.