<?php

namespace LarpManager\Services\Manager;

use Silex\Application;
use Doctrine\Common\Collections\ArrayCollection;

class LarpManagerManager
{
	
	/** @var \Silex\Application */
	protected $app;

	/**
	 * Constructeur
	 * 
	 * @param \Silex\Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Génére une quête commerciale pour un groupe donné
	 * 
	 * @param Groupe $groupe
	 */
	public function generateQuete(\LarpManager\Entities\Groupe $groupe, $ressourceCommunes, $ressourceRares)
	{
		$tab_recompenses = array(
				"1 pièce d'or (10 pièces d'argent)",
				"1 point de renommée",
				"1 point d'héroïsme",
				"5 ingrédients au hasard",
				"2 ingrédients au choix",
				"4 ressources communes choisies",
				"2 ressources rares choisies",
				"6 ressources communes au hasard",
				"3 ressouces rare au hasard",
				"2 points de rituel",
				"2 points de mana",
				"1 formule alchimique au hasard",
				"1 sortilège au hasard",
				"2 potions alchimiques au hasard",
				"1 potion alchimique au choisies",
				"1 réponse à une question simple",
				"1 carte aventure (jeu maritime)",
		);
		
		$needs = new ArrayCollection();
		$recompenses = new ArrayCollection();
		$px = 0;
		$importation_needed = 0;
		$common_ressources_needed = 0;
		$uncommon_ressources_needed = 0;
		$valeur = 0;		
		
		// importations du territoire
		$importations = $groupe->getImportations();
		$exportations = $groupe->getExportations();
			
		// ressources rares
		// en retirer les exportations du pays
		$ressourceRares = new ArrayCollection(array_diff($ressourceRares->toArray(), $exportations->toArray()));
			
		// ressources communes
		// en retirer les exportations du pays
		$ressourceCommunes = new ArrayCollection(array_diff($ressourceCommunes->toArray(), $exportations->toArray()));

		// calcul du nombre d'importation necessaire
		/*if ( $importations->count() > 3 ) $importations_needed = 3; 
		else if ( $importations->count() > 0 ) $importation_needed = rand(1,$importations->count());*/
			
		// calcul du nombre de ressources communes
		$common_ressources_needed = 3;
		if ($common_ressources_needed < 0) $common_ressources_needed = 0;
			
		// calcul du nombre de ressources rares
		$uncommon_ressources_needed = 4;
		if ($uncommon_ressources_needed < 0) $uncommon_ressources_needed = 0;
		
		// allocation des importations
		if ( $importation_needed > 0 )
		{
			$resArray = $importations->toArray();
			shuffle( $resArray );
			$needs = new ArrayCollection(array_merge($needs->toArray(),array_slice($resArray,0,$importation_needed)));
		}
				
		// allocation des ressources simples
		$ressourceCommunes = new ArrayCollection(array_diff( $ressourceCommunes->toArray(), $needs->toArray()));
		if ( $ressourceCommunes->count() > 0 )
		{
			$resArray = $ressourceCommunes->toArray();
			shuffle( $resArray );
			$needs = new ArrayCollection(array_merge($needs->toArray(),array_slice($resArray,0,$common_ressources_needed)));
		}
		
		// allocation des ressources rares
		$ressourceRares = new ArrayCollection(array_diff( $ressourceRares->toArray(), $needs->toArray()));
		if ( $ressourceRares->count() > 0 )
		{
			$resArray = $ressourceRares->toArray();
			shuffle( $resArray );
			$needs = new ArrayCollection(array_merge($needs->toArray(),array_slice($resArray,0,$uncommon_ressources_needed)));
		}
		
		// calcul de la valeur de ce qui est demandé
		$valeur = 0;
		foreach ($needs as $ressource){
			$rarete = $ressource->getRarete();
			switch ($rarete->getValue())
			{
				case 1 : $valeur += 3; break;
				case 2 : $valeur += 6;break;
			}
		}
			
		// choix de la récompense
		
			
		shuffle($tab_recompenses );
		$recompenses = array_slice($tab_recompenses,0,4);
		$recompenses[] = "1 point d'expérience";

		return array(
				'needs' => $needs,
				'px' => $px,
				'valeur' => $valeur,
				'recompenses' => $recompenses,
		);
	}
	
	/**
	 * Fourni le gn actif
	 */
	public function getGnActif()
	{
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\Gn');
		return $repo->findGnActif();
	}	
	
	/**
	 * Fourni la liste des ROLES utilisé dans LarpManager
	 * @return Array $availablesRoles
	 */
	public function getAvailableRoles()
	{
		$availableRoles = array(
				array('label' => 'ROLE_USER', 'descr' => 'Utilisateur de larpManager'),
				array('label' => 'ROLE_ORGA', 'descr' => 'Organisateur'),
				array('label' => 'ROLE_ADMIN', 'descr' => 'Droit de modification sur tout'),
				array('label' => 'ROLE_STOCK', 'descr' => 'Droit de modification sur le stock'),
				array('label' => 'ROLE_REGLE', 'descr' => 'Droit de modification sur les règles'),
				array('label' => 'ROLE_CARTOGRAPHE', 'descr' => 'Droit de modification sur l\'univers'),
				array('label' => 'ROLE_SCENARISTE', 'descr' => 'Droit de modification sur le scénario, les groupes et le background'),
				array('label' => 'ROLE_MODERATOR', 'descr' => 'Modération du forum'),
		);
		return $availableRoles;
	}
	
	/**
	 * Fourni la liste des droits necessaires pour accéder à un topic
	 * @return string[]
	 */
	public function getAvailableTopicRight()
	{
		$availableTopicRight = array(
			'GN_PARTICIPANT' => 'L\'utilisateur doit participer au GN',
			'GROUPE_MEMBER' => 'L\'utilisateur doit être membre du groupe',
			'GROUPE_SECONDAIRE_MEMBER' => 'Le personnage de l\'utilisateur doit être membre du groupe secondaire',
			'CULTE' => 'Le personnage de l\'utilisateur doit suivre cette religion',
			'ORGA' => 'L\'utilisateur doit disposer du role ORGA',
			'CARTOGRAPHE' => 'L\'utilisateur doit disposer du role CARTOGRAPHE',
			'SCENARISTE' => 'L\'utilisateur doit disposer du role SCENARISTE',
		);
		return $availableTopicRight;
	}
	
	/**
	 * Fourni la liste des droits concernant la visibilité des backgrounds
	 * @return string[]
	 */
	public function getVisibility()
	{
		$visibility = array(
			'PRIVATE' => 'Seul les scénaristes peuvent voir ceci',
			'PUBLIC' => 'Tous les joueurs peuvent voir ceci',
			'GROUPE_MEMBER' => 'Seul les membres du groupe peuvent voir ceci',
			'GROUPE_OWNER' => 'Seul le chef de groupe peux voir ceci',
		);
		return $visibility;
	}
	
	/**
	 * Fourni la liste des droits concernant la visibilité des backgrounds
	 * @return string[]
	 */
	public function getPersonnageBackgroundVisibility()
	{
		$visibility = array(
				'PRIVATE' => 'Seul les scénaristes peuvent voir ceci',
				'OWNER' => 'Le proprietaire du personnage peut voir ceci',
		);
		return $visibility;
	}
	
	public function getChronologieVisibility()
	{
		$visibility = array(
			'PRIVATE' => 'Seul les scénaristes peuvent voir ceci',
			'PUBLIC' => 'Tous les joueurs peuvent voir ceci',
			'GROUPE_MEMBER' => 'Seul les membres d\'un groupe lié à ce territoire peuvent voir ceci',
		);
		
		return $visibility;
	}
	
	/**
	 * Fourni la liste des compétences de premier niveau
	 * @return Collection $competences
	 */
	public function getRootCompetences()
	{
		$rootCompetences = new ArrayCollection();
	
		$repo = $this->app['orm.em']->getRepository('\LarpManager\Entities\CompetenceFamily');
		$competenceFamilies = $repo->findAll();
	
		foreach ( $competenceFamilies as $competenceFamily)
		{
			$competence = $competenceFamily->getFirstCompetence();
			if ( $competence )
			{
				$rootCompetences->add($competence);
			}
		}
		
		// trie des competences disponibles
		$iterator = $rootCompetences->getIterator();
		$iterator->uasort(function ($a, $b) {
			return ($a->getLabel() < $b->getLabel()) ? -1 : 1;
		});
		
		return  new ArrayCollection(iterator_to_array($iterator));
	}
	
	/**
	 * Trouve un topic en fonction de sa clé
	 * 
	 * @param unknown $topicKey
	 */
	public function findTopic($topicKey)
	{
		$repoTopic = $this->app['orm.em']->getRepository('\LarpManager\Entities\Topic');
		return $repoTopic->findOneByKey($topicKey);
	}

	/**
	 * Il faut classer les territoires par groupe de territoire
	 *
	 * @param Array $territoires
	 * @return Array $territoires
	 */
	public function sortTerritoire( Array $territoires)
	{
		$root = array();
		$result = array();
	
		// recherche des racines ( territoires n'ayant pas de parent
		// dans la liste des territoires fournis)
		foreach ( $territoires as $territoire)
		{
			if ( ! in_array($territoire->getTerritoire(),$territoires) )
			{
				$root[] = $territoire;
			}
		}
	
		foreach ( $root as $territoire)
		{
			if ( count($territoire->getTerritoires()) > 0 )
			{
				$childs = array_merge(
						array($territoire),
						$this->sortTerritoire($territoire->getTerritoires()->toArray())
						);
	
				$result = array_merge($result, $childs);
			}
			else
			{
				$result[] = $territoire;
			}
		}
	
		return $result;
	}
	
	/**
	 * Classement des appelations par groupe
	 *
	 * @param Array $appelations
	 * @return Array $appelations
	 */
	public function sortAppelation( Array $appelations)
	{
		$root = array();
		$result = array();
	
		// recherche des racines ( appelations n'ayant pas de parent
		// dans la liste des appelations fournis)
		foreach ( $appelations as $appelation)
		{
			if ( ! in_array($appelation->getAppelation(),$appelations) )
			{
				$root[] = $appelation;
			}
		}
	
		foreach ( $root as $appelation)
		{
			if ( count($appelation->getAppelations()) > 0 )
			{
				$childs = array_merge(
						array($appelation),
						$this->sortAppelation($appelation->getAppelations()->toArray())
						);
	
				$result = array_merge($result, $childs);
			}
			else
			{
				$result[] = $appelation;
			}
		}
	
		return $result;
	}
}