<?php

/**
 * LarpManager - A Live Action Role Playing Manager
 * Copyright (C) 2016 Kevin Polez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
namespace LarpManager\Services;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;

use LarpManager\Twig\LarpManagerExtension;
use LarpManager\Services\LarpManagerVoter;

use LarpManager\Services\Manager\ForumRightManager;
use LarpManager\Services\Manager\LarpManagerManager;
use LarpManager\Services\Manager\PersonnageManager;
use LarpManager\Services\Manager\FedegnManager;

use LarpManager\Services\Converter\AgeConverter;
use LarpManager\Services\Converter\AllianceConverter;
use LarpManager\Services\Converter\AnnonceConverter;
use LarpManager\Services\Converter\AppelationConverter;
use LarpManager\Services\Converter\BackgroundConverter;
use LarpManager\Services\Converter\BilletConverter;
use LarpManager\Services\Converter\ClasseConverter;
use LarpManager\Services\Converter\CultureConverter;
use LarpManager\Services\Converter\CompetenceConverter;
use LarpManager\Services\Converter\ConstructionConverter;
use LarpManager\Services\Converter\DebriefingConverter;
use LarpManager\Services\Converter\DocumentConverter;
use LarpManager\Services\Converter\DomaineConverter;
use LarpManager\Services\Converter\EnemyConverter;
use LarpManager\Services\Converter\EtatCivilConverter;
use LarpManager\Services\Converter\EventConverter;
use LarpManager\Services\Converter\GnConverter;
use LarpManager\Services\Converter\GroupeConverter;
use LarpManager\Services\Converter\GroupeGnConverter;
use LarpManager\Services\Converter\IngredientConverter;
use LarpManager\Services\Converter\IntrigueConverter;
use LarpManager\Services\Converter\ItemConverter;
use LarpManager\Services\Converter\LangueConverter;
use LarpManager\Services\Converter\LieuConverter;
use LarpManager\Services\Converter\LoiConverter;
use LarpManager\Services\Converter\MembreConverter;
use LarpManager\Services\Converter\MessageConverter;
use LarpManager\Services\Converter\MonnaieConverter;
use LarpManager\Services\Converter\NotificationConverter;
use LarpManager\Services\Converter\ObjetConverter;
use LarpManager\Services\Converter\ParticipantConverter;
use LarpManager\Services\Converter\PersonnageBackgroundConverter;
use LarpManager\Services\Converter\PersonnageConverter;
use LarpManager\Services\Converter\PersonnageLangueConverter;
use LarpManager\Services\Converter\PersonnageReligionConverter;
use LarpManager\Services\Converter\PersonnageSecondaireConverter;
use LarpManager\Services\Converter\PersonnageTokenConverter;
use LarpManager\Services\Converter\PersonnageTriggerConverter;
use LarpManager\Services\Converter\PersonnageChronologieConverter;
use LarpManager\Services\Converter\PersonnageLigneeConverter;
use LarpManager\Services\Converter\PostulantConverter;
use LarpManager\Services\Converter\PotionConverter;
use LarpManager\Services\Converter\PriereConverter;
use LarpManager\Services\Converter\QualityConverter;
use LarpManager\Services\Converter\QuestionConverter;
use LarpManager\Services\Converter\ReligionConverter;
use LarpManager\Services\Converter\ReponseConverter;
use LarpManager\Services\Converter\RestaurationConverter;
use LarpManager\Services\Converter\RestrictionConverter;
use LarpManager\Services\Converter\RuleConverter;
use LarpManager\Services\Converter\RumeurConverter;
use LarpManager\Services\Converter\SecondaryGroupConverter;
use LarpManager\Services\Converter\SortConverter;
use LarpManager\Services\Converter\SphereConverter;
use LarpManager\Services\Converter\TechnologieConverter;
use LarpManager\Services\Converter\TerritoireConverter;
use LarpManager\Services\Converter\TitreConverter;
use LarpManager\Services\Converter\TokenConverter;
use LarpManager\Services\Converter\TriggerConverter;
use LarpManager\Services\Converter\UserConverter;

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
			$voters[] = new LarpManagerVoter($roleHierarchyVoter, $app);
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
		
		// Fedegn manager
		$app['fedegn.manager'] = $app->share(function($app) {
			$fedegnManager = new FedegnManager($app);
			return $fedegnManager;
		});
		
		// Item converter
		$app['converter.item'] = $app->share(function($app) {
			return new ItemConverter($app['orm.em']);
		});
		
		// Personnage manager
		$app['personnage.manager'] = $app->share(function($app) {
			$personnageManager = new PersonnageManager($app);
			return $personnageManager;
		});
				
		// age converter
		$app['converter.age'] = $app->share(function($app) {
			return new AgeConverter($app['orm.em']);
		});

		// alliance converter
		$app['converter.alliance'] = $app->share(function($app) {
			return new AllianceConverter($app['orm.em']);
		});
		
		// annonce converter
		$app['converter.annonce'] = $app->share(function($app) {
			return new AnnonceConverter($app['orm.em']);
		});
		
		// Appelation converter
		$app['converter.appelation'] = $app->share(function($app) {
			return new AppelationConverter($app['orm.em']);
		});
		
		// classe converter
		$app['converter.classe'] = $app->share(function($app) {
			return new ClasseConverter($app['orm.em']);
		});
		
		// culture converter
		$app['converter.culture'] = $app->share(function($app) {
			return new CultureConverter($app['orm.em']);
		});
		
		// event converter
		$app['converter.event'] = $app->share(function($app) {
			return new EventConverter($app['orm.em']);
		});

		// loi converter
		$app['converter.loi'] = $app->share(function($app) {
			return new LoiConverter($app['orm.em']);
		});
			
		// personnage converter
		$app['converter.personnage'] = $app->share(function($app) {
			return new PersonnageConverter($app['orm.em']);
		});
		
		// personnage religion converter
		$app['converter.personnageReligion'] = $app->share(function($app) {
			return new PersonnageReligionConverter($app['orm.em']);
		});
		
		// reponse converter
		$app['converter.reponse'] = $app->share(function($app) {
			return new ReponseConverter($app['orm.em']);
		});

		// technologie converter
		$app['converter.technologie'] = $app->share(function($app) {
			return new TechnologieConverter($app['orm.em']);
		});
			
		// territoire converter
		$app['converter.territoire'] = $app->share(function($app) {
			return new TerritoireConverter($app['orm.em']);
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
		
		// debriefing converter
		$app['converter.debriefing'] = $app->share(function($app) {
			return new DebriefingConverter($app['orm.em']);
		});
		
		// personnageBackground converter
		$app['converter.personnageBackground'] = $app->share(function($app) {
			return new PersonnageBackgroundConverter($app['orm.em']);
		});
		
		// groupe converter
		$app['converter.groupe'] = $app->share(function($app) {
			return new GroupeConverter($app['orm.em']);
		});
		
		// groupeGn converter
		$app['converter.groupeGn'] = $app->share(function($app) {
			return new GroupeGnConverter($app['orm.em']);
		});
		
		// notification converter
		$app['converter.notification'] = $app->share(function($app) {
			return new NotificationConverter($app['orm.em']);
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
		
		// message converter
		$app['converter.message'] = $app->share(function($app) {
			return new MessageConverter($app['orm.em']);
		});
		
		// message converter
		$app['converter.monnaie'] = $app->share(function($app) {
			return new MonnaieConverter($app['orm.em']);
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
		
		// religion converter
		$app['converter.religion'] = $app->share(function($app) {
			return new ReligionConverter($app['orm.em']);
		});
		
		// langue converter
		$app['converter.langue'] = $app->share(function($app) {
			return new LangueConverter($app['orm.em']);
		});

		// personnageLangue converter
		$app['converter.personnageLangue'] = $app->share(function($app) {
			return new PersonnageLangueConverter($app['orm.em']);
		});
		
		// domaine converter
		$app['converter.domaine'] = $app->share(function($app) {
			return new DomaineConverter($app['orm.em']);
		});
		
		// objet converter
		$app['converter.objet'] = $app->share(function($app) {
			return new ObjetConverter($app['orm.em']);
		});
		
		// sortilège converter
		$app['converter.sort'] = $app->share(function($app) {
			return new SortConverter($app['orm.em']);
		});
		
		// potion converter
		$app['converter.potion'] = $app->share(function($app) {
			return new PotionConverter($app['orm.em']);
		});
		
		// rule converter
		$app['converter.rule'] = $app->share(function($app) {
			return new RuleConverter($app['orm.em']);
		});
		
		// rule converter
		$app['converter.rumeur'] = $app->share(function($app) {
			return new RumeurConverter($app['orm.em']);
		});
		
		// sphere converter
		$app['converter.sphere'] = $app->share(function($app) {
			return new SphereConverter($app['orm.em']);
		});
			
		// priere converter
		$app['converter.priere'] = $app->share(function($app) {
			return new PriereConverter($app['orm.em']);
		});

		// quality converter
		$app['converter.quality'] = $app->share(function($app) {
			return new QualityConverter($app['orm.em']);
		});
		
		// question converter
		$app['converter.question'] = $app->share(function($app) {
			return new QuestionConverter($app['orm.em']);
		});
		
		// titre converter
		$app['converter.titre'] = $app->share(function($app) {
			return new TitreConverter($app['orm.em']);
		});
		
		// ingredient converter
		$app['converter.ingredient'] = $app->share(function($app) {
			return new IngredientConverter($app['orm.em']);
		});
		
		// personnage trigger converter
		$app['converter.personnageTrigger'] = $app->share(function($app) {
			return new PersonnageTriggerConverter($app['orm.em']);
		});
		
		// document converter
		$app['converter.document'] = $app->share(function($app) {
			return new DocumentConverter($app['orm.em']);
		});
			
		// lieu converter
		$app['converter.lieu'] = $app->share(function($app) {
			return new LieuConverter($app['orm.em']);
		});
		
		// token converter
		$app['converter.token'] = $app->share(function($app) {
			return new TokenConverter($app['orm.em']);
		});
		
		// personnage token converter
		$app['converter.personnageToken'] = $app->share(function($app) {
			return new PersonnageTokenConverter($app['orm.em']);
		});
			
		// Billet converter
		$app['converter.billet'] = $app->share(function($app) {
			return new BilletConverter($app['orm.em']);
		});
		
		// EtatCivil converter
		$app['converter.etatCivil'] = $app->share(function($app) {
			return new EtatCivilConverter($app['orm.em']);
		});
		
		// Gn converter
		$app['converter.gn'] = $app->share(function($app) {
			return new GnConverter($app['orm.em']);
		});
		
		// Restriction converter
		$app['converter.restriction'] = $app->share(function($app) {
			return new RestrictionConverter($app['orm.em']);
		});

		// Restauration converter
		$app['converter.restauration'] = $app->share(function($app) {
			return new RestaurationConverter($app['orm.em']);
		});

		// Participant converter
		$app['converter.participant'] = $app->share(function($app) {
			return new ParticipantConverter($app['orm.em']);
		});
		
		// Intrigue converter
		$app['converter.intrigue'] = $app->share(function($app) {
			return new IntrigueConverter($app['orm.em']);
		});
		
		// PersonnageChronologie converter
		$app['converter.personnageChronologie'] = $app->share(function($app) {
			return new PersonnageChronologieConverter($app['orm.em']);
		});
	
		// PersonnageLignee converter
		$app['converter.personnageLignee'] = $app->share(function($app) {
			return new PersonnageLigneeConverter($app['orm.em']);
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