<?php

namespace LarpManager\Services;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;

use LarpManager\Twig\LarpManagerExtension;
use LarpManager\Services\ForumRightManager;
use LarpManager\Services\LarpManagerVoter;
use LarpManager\Services\fpdf\FPDF;

/**
 * LarpManager\LarpManagerServiceProvider
 * 
 * @author kevin
 *
 */
class LarpManagerServiceProvider implements ServiceProviderInterface
{
	/**
	 * 
	 * @param Application $app
	 */
	public function register(Application $app)
	{
		// Ajoute la gestion des droits de larpmanager
		$app['security.voters'] = $app->extend('security.voters', function($voters) use ($app) {
			foreach ($voters as $voter) {
				if ($voter instanceof RoleHierarchyVoter) {
					$roleHierarchyVoter = $voter;
					break;
				}
			}
			$voters[] = new LarpManagerVoter($roleHierarchyVoter);
			return $voters;
		});
		
		// manager
		$app['larp.manager'] = $app->share(function($app) {
			$larpManagerManager = new LarpManagerManager($app);
			return $larpManagerManager;
		});
		
		// Forum right manager
		$app['forum.manager'] = $app->share(function($app) {
			$forumRightManager = new ForumRightManager($app);
			return $forumRightManager;
		});
		
		// fpdf
		$app['pdf.manager'] = $app->share(function($app) {
			$fpdf = new FPDF();
			$fpdf->FPDF();
			return $fpdf;
		});
		
		// personnage converter
		$app['converter.personnage'] = $app->share(function($app) {
			return new PersonnageConverter($app['orm.em']);
		});
		
		// personnage converter
		$app['converter.personnageReligion'] = $app->share(function($app) {
			return new PersonnageReligionConverter($app['orm.em']);
		});		
		
		// territoire converter
		$app['converter.territoire'] = $app->share(function($app) {
			return new TerritoireConverter($app['orm.em']);
		});
		
		// event converter
		$app['converter.event'] = $app->share(function($app) {
			return new EventConverter($app['orm.em']);
		});
		
		// personnage secondaire converter
		$app['converter.personnageSecondaire'] = $app->share(function($app) {
			return new PersonnageSecondaireConverter($app['orm.em']);
		});
		
		// user converter
		$app['converter.user'] = $app->share(function($app) {
			return new UserConverter($app['orm.em']);
		});
		
		// background converter
		$app['converter.background'] = $app->share(function($app) {
			return new BackgroundConverter($app['orm.em']);
		});
		
		// groupe converter
		$app['converter.groupe'] = $app->share(function($app) {
			return new GroupeConverter($app['orm.em']);
		});
		
		// secondaryGroup converter
		$app['converter.secondaryGroup'] = $app->share(function($app) {
			return new SecondaryGroupConverter($app['orm.em']);
		});
		
		// postulant converter
		$app['converter.postulant'] = $app->share(function($app) {
			return new PostulantConverter($app['orm.em']);
		});
		
		// membre converter
		$app['converter.membre'] = $app->share(function($app) {
			return new MembreConverter($app['orm.em']);
		});
		
		// alliance converter
		$app['converter.alliance'] = $app->share(function($app) {
			return new AllianceConverter($app['orm.em']);
		});
			
		// enemy converter
		$app['converter.enemy'] = $app->share(function($app) {
			return new EnemyConverter($app['orm.em']);
		});
		
		// competence converter
		$app['converter.competence'] = $app->share(function($app) {
			return new CompetenceConverter($app['orm.em']);
		});
		
		// construction converter
		$app['converter.construction'] = $app->share(function($app) {
			return new ConstructionConverter($app['orm.em']);
		});		
	}

	/**
	 * Ajoute les extensions Twig de LarpManager
	 * 
	 * @param Application $app
	 */
	public function boot(Application $app)
	{
		$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
			$twig->addExtension(new LarpManagerExtension($app));
			return $twig;
		}));
	}
}