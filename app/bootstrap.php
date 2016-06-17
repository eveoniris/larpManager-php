<?php

use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\RememberMeServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Neutron\Silex\Provider\ImagineServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Saxulum\DoctrineOrmManagerRegistry\Silex\Provider\DoctrineOrmManagerRegistryProvider;
use Nicl\Silex\MarkdownServiceProvider;

use LarpManager\User\UserServiceProvider;
use LarpManager\Personnage\PersonnageServiceProvider;
use LarpManager\Services\LarpManagerServiceProvider;

$loader = require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

/**
 * Indique si le site est en mode maintenance ou pas.
 * Pour passser le site en mode maintenance, il faut ajouter manuellement
 * le fichier maintenance.tag dans le répertoire cache.
 * Cela permet d'acceder au fonctionnalités suivantes :
 * Installation, Mise à jour.
 */
$app['maintenance'] = file_exists(__DIR__.'/../cache/' . 'maintenance.tag');

/**
 * Lecture du fichier de configuration
 */
$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . '/../config/settings.yml'));

/**
 * Préparation des logs en fonction de l'environnement
 * (le niveau de logs est plus élevé en mode dev.)
 */
if ( $app['config']['env']['env'] == 'prod' )
{
	$app['debug'] = false;
	
	$app->register(new Silex\Provider\MonologServiceProvider(), array(
			'monolog.logfile' => __DIR__.'/../logs/production.log',
			'monolog.level' => \Monolog\Logger::CRITICAL
	));
}
else
{
	$app['debug'] = true;
	
	$app->register(new Silex\Provider\MonologServiceProvider(), array(
			'monolog.logfile' => __DIR__.'/../logs/development.log',
			'monolog.level' => \Monolog\Logger::DEBUG
	));
}

/**
 * Enregistrer les libs dans l'application 
 */
 

$app->register(new HttpCacheServiceProvider(), array(
		'http_cache.cache_dir' => __DIR__.'/../cache/',
		'http_cache.esi'       => null,
));

// Formulaires
$app->register(new FormServiceProvider());

// add entity type on forms
$app->register(new DoctrineOrmManagerRegistryProvider());

// validation
$app->register(new ValidatorServiceProvider());

// traduction
$app->register(new TranslationServiceProvider(), array(
		'translator.domains' => array(),
));

// imagine
$app->register(new ImagineServiceProvider());

// http fragment
$app->register(new HttpFragmentServiceProvider());

// Twig
$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../src/LarpManager/Views',
    'twig.options'    => array(
        'cache' => __DIR__ . '/../cache/',
    ),
));

// Email
$app->register(new SwiftmailerServiceProvider());

$app['swiftmailer.options'] = $app['config']['swiftmailer']; //voir les settings yaml 
$app['swiftmailer.transport'] =  new \Swift_Transport_SendmailTransport(
	$app['swiftmailer.transport.buffer'],
	$app['swiftmailer.transport.eventdispatcher']
);
            
// Doctrine DBAL
$app->register(new DoctrineServiceProvider(), array(
	    'db.options' => $app['config']['database'] //voir les settings yaml 
	));

// Doctrine ORM
$app->register(new DoctrineOrmServiceProvider(), array(
    "orm.proxies_dir" => __DIR__."/../cache/proxies",
    "orm.em.options" => array(
        "mappings" => array(
            array(
                "type" => "annotation",
                "namespace" => "LarpManager\Entities",
                "path" => __DIR__."/../src/LarpManager/Entities",
            ),
        ),
    ),
));


// Gestion des urls
$app->register(new UrlGeneratorServiceProvider());

// Sessions
$app->register(new SessionServiceProvider());

/**
 * Définition des routes
 */
 
if( $app['maintenance'] )
{
	$app->mount('/install', new LarpManager\InstallControllerProvider());
}
else
{	
	// Security
	$app->register(new SecurityServiceProvider());
	
	// User management
	$app->register(new ServiceControllerServiceProvider());
	$app->register(new RememberMeServiceProvider());
	$app->register(new UserServiceProvider());
	
	// markdown syntaxe pour les forums
	$app->register(new MarkdownServiceProvider());
	
	// Other management
	$app->register(new PersonnageServiceProvider());
	
	$app->register(new LarpManagerServiceProvider());
	
	// Define firewall
	$app['security.firewalls'] = array(
		'public_area' => array(
			'pattern' => '^.*$',
			'anonymous' => true,
			'remember_me' => array(),
			'form' => array(
				'login_path' => '/user/login',
				'check_path' => '/user/login_check',
				),
			'logout' => array(
				'logout_path' => '/user/logout',
				),
			'users' => $app->share(function($app) { 
				return $app['user.manager']; 
			   }),
		),
		'secured_area' => array(	// le reste necessite d'être connecté
			'pattern' => '^/[annonce|statistique|stock|droit|forum|groupe|gn|personnage|territoire|appelation|langue|ressource|religion|age|genre|level|competence|competenceFamily|joueur|etatCivil]/.*$',
			'anonymous' => false,
			'remember_me' => array(),
			'form' => array(
					'login_path' => '/user/login',
					'check_path' => '/user/login_check',
			),
			'logout' => array(
					'logout_path' => '/user/logout',
			),
			'users' => $app->share(function($app) { 
				return $app['user.manager'];  
			    }),
		),
	);
	
	$app->mount('/', new LarpManager\HomepageControllerProvider());
	$app->mount('/annonce', new LarpManager\AnnonceControllerProvider());
	$app->mount('/user',  new LarpManager\UserControllerProvider());
	$app->mount('/droit',  new LarpManager\RightControllerProvider());
	$app->mount('/stock', new LarpManager\StockControllerProvider());
	$app->mount('/stock/objet', new LarpManager\StockObjetControllerProvider());
	$app->mount('/stock/tag', new LarpManager\StockTagControllerProvider());
	$app->mount('/stock/etat', new LarpManager\StockEtatControllerProvider());
	$app->mount('/stock/proprietaire', new LarpManager\StockProprietaireControllerProvider());
	$app->mount('/stock/localisation', new LarpManager\StockLocalisationControllerProvider());
	$app->mount('/stock/rangement', new LarpManager\StockRangementControllerProvider());
	$app->mount('/groupe', new LarpManager\GroupeControllerProvider());
	$app->mount('/groupeSecondaire', new LarpManager\GroupeSecondaireControllerProvider());
	$app->mount('/groupeSecondaireType', new LarpManager\GroupeSecondaireTypeControllerProvider());
	$app->mount('/territoire', new LarpManager\TerritoireControllerProvider());
	$app->mount('/appelation', new LarpManager\AppelationControllerProvider());
	$app->mount('/langue', new LarpManager\LangueControllerProvider());
	$app->mount('/competence', new LarpManager\CompetenceControllerProvider());
	$app->mount('/competenceFamily', new LarpManager\CompetenceFamilyControllerProvider());
	$app->mount('/level', new LarpManager\LevelControllerProvider());
	$app->mount('/classe', new LarpManager\ClasseControllerProvider());
	$app->mount('/chronologie', new LarpManager\ChronologieControllerProvider());
	$app->mount('/ressource', new LarpManager\RessourceControllerProvider());
	$app->mount('/religion', new LarpManager\ReligionControllerProvider());
	$app->mount('/construction', new LarpManager\ConstructionControllerProvider());	
	$app->mount('/personnage', new LarpManager\PersonnageControllerProvider());
	$app->mount('/personnageSecondaire', new LarpManager\PersonnageSecondaireControllerProvider());
	$app->mount('/age', new LarpManager\AgeControllerProvider());
	$app->mount('/magie', new LarpManager\MagieControllerProvider());
	$app->mount('/genre', new LarpManager\GenreControllerProvider());
	$app->mount('/gn', new LarpManager\GnControllerProvider());
	$app->mount('/participant', new LarpManager\ParticipantControllerProvider());
	$app->mount('/forum', new LarpManager\ForumControllerProvider());
	$app->mount('/statistique', new LarpManager\StatistiqueControllerProvider());
	$app->mount('/background', new LarpManager\BackgroundControllerProvider());
	$app->mount('/pnj', new LarpManager\PnjControllerProvider());
	$app->mount('/admin', new LarpManager\AdminControllerProvider());
	$app->mount('/titre', new LarpManager\TitreControllerProvider());
	$app->mount('/ingredient', new LarpManager\IngredientControllerProvider());
	$app->mount('/trombinoscope', new LarpManager\TrombinoscopeControllerProvider());
		

	/**
	 * Gestion de la hierarchie des droits
	 */
	$app['security.role_hierarchy'] = array(
		'ROLE_USER' => array('ROLE_USER'),
		'ROLE_ORGA' => array('ROLE_ORGA'),
		'ROLE_STOCK' => array('ROLE_USER', 'ROLE_ORGA', 'ROLE_STOCK'),
		'ROLE_SCENARISTE' => array('ROLE_USER', 'ROLE_ORGA', 'ROLE_SCENARISTE'),
		'ROLE_REGLE' => array('ROLE_USER', 'ROLE_ORGA', 'ROLE_REGLE'),
		'ROLE_MODERATOR' => array('ROLE_USER', 'ROLE_ORGA', 'ROLE_MODERATOR'), 
		'ROLE_ADMIN' => array('ROLE_USER', 'ROLE_ORGA', 'ROLE_STOCK','ROLE_SCENARISTE','ROLE_REGLE','ROLE_MODERATOR'),
	);
	
	/**
	 * Gestion des droits d'accés
	 */
	$app['security.access_rules'] = array(
		array('^/admin/.*$', 'ROLE_ADMIN'),
		array('^/trombinoscope/.*$', 'ROLE_SCENARISTE'),
		array('^/pnj/.*$', 'ROLE_USER'),
		array('^/groupe/.*$', 'ROLE_USER'),
		array('^/groupeSecondaire/.*$', 'ROLE_USER'),
		array('^/competence/.*$', 'ROLE_USER'),
		array('^/classe/.*$', 'ROLE_USER'),
		array('^/chronologie/.*$', 'ROLE_USER'),
		array('^/gn/.*$', 'ROLE_USER'),
		array('^/personnage/.*$', 'ROLE_USER'),
		array('^/personnageSecondaire/.*$', 'ROLE_USER'),
		array('^/joueur/.*$', 'ROLE_USER'),
		array('^/territoire/.*$', 'ROLE_USER'),
		array('^/religion/.*$', 'ROLE_USER'),
		array('^/groupeSecondaireType/.*$', 'ROLE_SCENARISTE'),
		array('^/background/.*$', 'ROLE_SCENARISTE'),
		array('^/annonce/.*$', 'ROLE_ADMIN'),
		array('^/droit/.*$', 'ROLE_ADMIN'),
		array('^/api/.*$', 'ROLE_SCENARISTE'),
		array('^/age/.*$', 'ROLE_REGLE'),
		array('^/magie/.*$', 'ROLE_USER'),
		array('^/genre/.*$', 'ROLE_REGLE'),
		array('^/construction/.*$', 'ROLE_REGLE'),
		array('^/appelation/.*$', 'ROLE_SCENARISTE'),
		array('^/titre/.*$', 'ROLE_SCENARISTE'),
		array('^/ingredient/.*$', 'ROLE_SCENARISTE'),
		array('^/langue/.*$', 'ROLE_SCENARISTE'),
		array('^/ressource/.*$', 'ROLE_SCENARISTE'),
		array('^/statistique/.*$', 'ROLE_SCENARISTE'),
		array('^/competenceFamily/.*$', 'ROLE_REGLE'),
		array('^/level/.*$', 'ROLE_REGLE'),
		array('^/stock/.*$', 'ROLE_STOCK'),

	);
}




/**
 * Gestion des exceptions
 */
$app->error(function (\Exception $e, $code) use ($app)
{	
	if( $app['maintenance'] ) 
	{
		return $app['twig']->render('error/maintenance.twig');
	}	
	else if ($e instanceof Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException)
	{
		return $app['twig']->render('error/denied.twig');
	}
	else if($e instanceof Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
	{
		return $app['twig']->render('error/notfound.twig');
	}		
	else if($e instanceof Symfony\Component\Routing\Exception\RouteNotFoundException)
	{
		return $app['twig']->render('error/notfound.twig');
	}
	else if($e instanceof LarpManager\Exception\RequestInvalidException)
	{
		return $app['twig']->render('error/requestInvalid.twig');
	}
	else if($e instanceof LarpManager\Exception\ObjectNotFoundException)
	{
		return $app['twig']->render('error/objectNotFound.twig');
	}
});

/**
 * Service de traduction
 */

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
		'locale'                    => 'fr',
		'locale_fallback'           => 'en',
		'translation.class_path'    => __DIR__.'/vendor/Symfony/Component',
));

use Symfony\Component\Translation\Loader\YamlFileLoader;

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
	$translator->addLoader('yaml', new YamlFileLoader());

	$translator->addResource('yaml', __DIR__.'/../src/LarpManager/Locales/en.yml', 'en');
	$translator->addResource('yaml', __DIR__.'/../src/LarpManager/Locales/de.yml', 'de');
	$translator->addResource('yaml', __DIR__.'/../src/LarpManager/Locales/fr.yml', 'fr');

	return $translator;
}));

return $app;
