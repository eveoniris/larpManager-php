<?php

namespace LarpManager\Services;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Silex\Application;

use LarpManager\Entities\User;

/**
 * LarpManager\LarpManagerVoter
 * @author kevin
 *
 */
class LarpManagerVoter implements VoterInterface
{
	/** @var RoleHierarchyVoter */
	protected $roleHierarchyVoter;
	
	protected $app;
	
	/**
	 * Constructeur
	 * 
	 * @param RoleHierarchyVoter $roleHierarchyVoter
	 */
	public function __construct(RoleHierarchyVoter $roleHierarchyVoter, Application $app)
	{
		$this->roleHierarchyVoter = $roleHierarchyVoter;
		$this->app = $app;
	}
	
	/**
	 * Vérifie si le voter supporte l'attribue demandé
	 *
	 * @param string $attribute An attribute
	 *
	 * @return Boolean true if this Voter supports the attribute, false otherwise
	 */
	public function supportsAttribute($attribute)
	{
		return in_array($attribute, array(
				'POST_OWNER',
				'MODERATOR',
				'TERRITOIRE_MEMBER',
				'GN_PARTICIPANT',
				'GROUPE_MEMBER',
				'GROUPE_RESPONSABLE',
				'GROUPE_BILLET',
				'GROUPE_SECONDAIRE_MEMBER',
				'GROUPE_SECONDAIRE_RESPONSABLE',
				'JOUEUR_OWNER',
				'JOUEUR_NOT_REGISTERED',
				'OWN_PERSONNAGE',
				'OWN_PARTICIPANT',
				'TERRITOIRE_MEMBER',
				'TOPIC_RIGHT',
		));
	}

	/**
	 * Checks if the voter supports the given user token class.
	 *
	 * @param string $class A class name
	 *
	 * @return true if this Voter can process the class
	 */
	public function supportsClass($class)
	{
		return true;
	}
	
	public function vote(TokenInterface $token, $object, array $attributes)
	{
		$user = $token->getUser();
		
		foreach ($attributes as $attribute) {
			if (!$this->supportsAttribute($attribute)) {
				continue;
			}
			
			if ($this->hasRole($token, 'ROLE_ADMIN')) {
				return VoterInterface::ACCESS_GRANTED;
			}
			
			if ($attribute == 'GROUPE_RESPONSABLE') {
				$groupeGnId = $object;
				return $this->isResponsableOf($user, $groupeGnId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'GROUPE_BILLET') {
				$groupeGnId = $object;
				return $this->hasBillet($user, $groupeGnId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'GROUPE_MEMBER') {
				$groupeId = $object;
				return $this->isMemberOf($user, $groupeId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'TERRITOIRE_MEMBER') {
				$territoireId = $object;
				return $this->isInsideOf($user, $territoireId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'GROUPE_SECONDAIRE_RESPONSABLE') {
				$groupeSecondaireId = $object;
				return $this->isGroupeSecondaireResponsableOf($user, $groupeSecondaireId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'GROUPE_SECONDAIRE_MEMBER') {
				$groupeSecondaireId = $object;
				return $this->isGroupeSecondaireMemberOf($user, $groupeSecondaireId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ( $attribute == 'POST_OWNER') {
				$postId = $object;
				return $this->isHisPost($user, $postId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'JOUEUR_OWNER') {
				$joueurId = $object;
				return $this->isOwnerOfJoueur($user, $joueurId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'JOUEUR_NOT_REGISTERED') {
				return $this->isNotRegistered($user) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'OWN_PERSONNAGE') {
				$personnageId = $object;
				return $this->isOwnerOfPersonnage($user, $personnageId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'TOPIC_RIGHT') {
				$topic = $object;
				return $this->hasTopicRight($user, $topic, $token) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'GN_PARTICIPANT') {
				$gnId = $object;
				return $this->participeTo($user, $gnId, $token) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'OWN_PARTICIPANT') {
				$participantId = $object;
				return $this->isOwnerOfParticipant($user, $participantId, $token) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
		}
		
		return VoterInterface::ACCESS_ABSTAIN;
	}
	
	/**
	 * Determine si l'utilisateur a les droits d'acceder à ce topic
	 * 
	 * @param unknown $user
	 * @param unknown $topic
	 */
	protected function hasTopicRight($user, $topic, $token)
	{
		switch ( $topic->getRight() )
		{
			case 'GN_PARTICIPANT' :
				return $this->userGnRight($topic->getObjectId(), $user, $token);
				break;
			case 'GROUPE_MEMBER' :
				return $this->userGroupeRight($topic->getObjectId(), $user, $token);
				break;
			case 'TERRITOIRE_MEMBER' :
				return $this->userTerritoireRight($topic->getObjectId(), $user, $token);
				break;
			case 'GROUPE_SECONDAIRE_MEMBER' :
				return $this->userGroupeSecondaireRight($topic->getObjectId(), $user, $token);
				break;
			case 'CULTE' :
				return $this->userCulteRight($topic->getObjectId(), $user, $token);
			case 'ORGA' :
				return $this->hasRole($token, 'ROLE_ORGA') ? true: false;
			case 'SCENARISTE' :
				return $this->hasRole($token, 'ROLE_SCENARISTE') ? true: false;
			default :
				return true;
		}
	}
	
	/**
	 * Dtermine si le participant appartient à l'utilisateur
	 * 
	 * @param unknown $user
	 * @param unknown $participantId
	 * @param unknown $token
	 */
	protected function isOwnerOfParticipant($user, $participantId, $token)
	{
		foreach (  $user->getParticipants() as $participant )
		{
			if ( $participant->getId() == $participantId ) return true;
		}
		return false;
	}
	
	/**
	 * Détermine si un utilisateur participe ou pas à un jeu
	 * 
	 * @param unknown $user
	 * @param unknown $gn
	 * @param unknown $token
	 */
	protected function participeTo($user, $gnId, $token)
	{		
		foreach (  $user->getParticipants() as $participant )
		{
			if ( $participant->getGn()->getId() == $gnId ) return true;
		}
		return false;
	}
	
	/**
	 * Determine si l'utilisateur a le droit d'accéder aux forums de ce culte
	 * (le personnage de l'utilisateur doit être pratiquant de ce culte)
	 * 
	 * @param unknown $culteId
	 * @param unknown $user
	 */
	protected function userCulteRight($culteId, $user, $token)
	{
		if ($this->hasRole($token, 'ROLE_SCENARISTE')) return true;
		
		$participants =  $user->getParticipants();
		
		foreach ( $participants as $participant )
		{
			if ( $participant->getPersonnage() )
			{
				foreach ( $participant->getPersonnage()->getPersonnagesReligions() as $personnageReligion )
				{
					if ( $personnageReligion->getReligion()->getId() == $culteId )
					{
						return true;
					}
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Determine si l'utilisateur à le droit d'accéder aux forums de ce GN.
	 * (l'utilisateur doit être participant du GN)
	 *
	 * @param unknown $gnId
	 * @param unknown $user
	 */
	protected function userGnRight($gnId, $user, $token)
	{
		if ($this->hasRole($token, 'ROLE_SCENARISTE')) return true;
		
		foreach ( $user->getGns() as $gn )
		{
			if ( $gn->getId() == $gnId) return true;
		}

		return false;
	}
	
	/**
	 * Determine si l'utilisateur à le droit d'accéder aux forums de ce groupe
	 * (l'utilisateur doit être membre du groupe)
	 *
	 * @param unknown $groupeId
	 * @param unknown $user
	 */
	protected function userGroupeRight($groupeId, $user, $token)
	{
		if ($this->hasRole($token, 'ROLE_SCENARISTE')) return true;
		
		foreach ( $user->getParticipants() as $participant)
		{
			if ( $participant->getGroupe() )
			{
				if ( $participant->getGroupe()->getId() == $groupeId ) return true;
			}
		}
		return false;
	}
	
	/**
	 * Determine si l'utilisateur à le droit d'accéder au forum de ce territoire
	 * (l'utilisateur doit être membre d'un groupe lié à ce territoire)
	 * @param unknown $territoireId
	 * @param unknown $user
	 */
	protected function userTerritoireRight($territoireId, $user, $token)
	{
		if ($this->hasRole($token, 'ROLE_SCENARISTE')) return true;
		
		foreach ( $user->getParticipants() as $participant)
		{
			if ( $participant->getGroupe() )
			{
				$groupe = $participant->getGroupe();
				foreach ($groupe->getTerritoires() as $territoire)
				{
					if ( $territoire->getId() == $territoireId)
					{
						return true;
					}
					else if ( $territoire->getTerritoire() )
					{
						foreach ( $territoire->getAncestors() as $ancestor )
						{
							if ( $ancestor->getId() == $territoireId )
							{
								return true;
							}
						}
					}
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Determine si l'utilisateur à le droit d'accéder aux forums de ce groupe secondaire
	 * (l'utilisateur doit être membre du groupe)
	 * 
	 * @param unknown $groupeSecondaireId
	 * @param unknown $user
	 */
	protected function userGroupeSecondaireRight($groupeSecondaireId, $user, $token)
	{
		if ($this->hasRole($token, 'ROLE_SCENARISTE')) return true;
		
		foreach ( $user->getPersonnages() as $personnage )
		{
			foreach ( $personnage->getMembres() as $groupeMember )
			{
				$groupe = $groupeMember->getSecondaryGroup();
				if ( $groupe && $groupe->getId() == $groupeSecondaireId ) return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Test si un utilisateur dispose des droits pour acceder au forum du territoire
	 * @param unknown $user
	 * @param unknown $territoireId
	 */
	protected function isInsideOf($user, $territoireId)
	{
		foreach ( $user->getParticipants() as $participant)
		{
			$groupe = $participant->getGroupe();
			
			if ( $groupe->getTerritoires() )
			{
				foreach ($groupe->getTerritoires() as $territoire)
				{
						
					if ( $territoire->getId() == $territoireId)
					{
						return true;
					}
					else if ( $territoire->getTerritoire() )
					{
						foreach ( $territoire->getAncestors() as $ancestor )
						{
							if ( $ancestor->getId() == $territoireId )
							{
								return true;
							}
						}
					}
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Test si un utilisateur est bien l'auteur d'un post
	 * 
	 * @param unknown $user
	 * @param unknown $postId
	 */
	protected function isHisPost($user, $postId)
	{
		foreach( $user->getPostRelatedByUserIds() as $post)
		{
			if ( $post instanceOf \LarpManager\Entities\Post
				&& $post->getId() == $postId)
				return true;
		}
		return false;
	}
	
	/**
	 * Vérifie si un utilisateur a un billet pour une session de jeu
	 * 
	 * @param unknown $user
	 * @param unknown $groupeGnId
	 */
	protected function hasBillet($user, $groupeGnId)
	{
		$groupeGn = $this->app['converter.groupeGn']->convert($groupeGnId);
		
		foreach ( $user->getParticipants() as $participant )
		{
			
			if ($participant->getGn() == $groupeGn->getGn() )
			{
				if ( $participant->getBillet() ) return true;
			}
		}
		return false;
	}
	
	/**
	 * Test si un utilisateur est responsable d'un groupe
	 * 
	 * @param unknown $user
	 * @param unknown $groupeId
	 */
	protected function isResponsableOf($user, $groupeGnId)
	{
		foreach ( $user->getParticipants() as $participant )
		{
			foreach ( $participant->getGroupeGns() as $session )
			{
				if ( $session->getId() == $groupeGnId ) return true;				
			}
		}
		return false;
	}
	
	/**
	 * Test si un utilisateur est membre d'un groupe
	 * 
	 * @param unknown $user
	 * @param unknown $groupeId
	 */
	protected function isMemberOf($user, $groupeId)
	{
		foreach ( $user->getParticipants() as $participant )
		{
			if ( $participant->getGroupe() && $participant->getGroupe()->getId() == $groupeId)	return true;
		}				
		return false;
	}
	

	/**
	 * Test si un utilisateur est responsable d'un groupe secondaire
	 *
	 * @param unknown $user
	 * @param unknown $groupeId
	 */
	protected function isGroupeSecondaireResponsableOf($user, $groupeSecondaireId)
	{
		$personnage = $user->getPersonnage();
		if ( $personnage)
		{
			foreach( $personnage->getSecondaryGroupsAsChief() as $groupe)
			{
				if ( $groupe instanceof \LarpManager\Entities\SecondaryGroup
						&& $groupe->getId() == $groupeSecondaireId)
					return true;
			}
		}
		return false;
	}
	
	/**
	 * Test si un utilisateur est membre d'un groupe secondaire
	 *
	 * @param unknown $user
	 * @param unknown $groupeId
	 */
	protected function isGroupeSecondaireMemberOf($user, $groupeSecondaireId)
	{
		$personnage = $user->getPersonnage();
		if ( $personnage )
		{
			foreach ( $personnage->getMembres() as $membre )
			{
				$groupe = $membre->getSecondaryGroup();
				
				if ( $groupe instanceof \LarpManager\Entities\SecondaryGroup
						&& $groupe->getId() == $groupeSecondaireId)
					return true;
			}
		}		
		return false;
	}
	
	/**
	 * Test si un utilisateur posséde bien le personnage
	 *
	 * @param User $user
	 * @param $personnageId
	 */
	protected function isOwnerOfPersonnage(User $user, $personnageId)
	{
		foreach ( $user->getPersonnages() as $personnage )
		{
			if ( $personnage->getId() == $personnageId ) return true;
		}
		return false;
	}
	
	/**
	 * Test si un utilisateur est membre d'un territoire
	 *
	 * @param User $user
	 * @param $personnageId
	 */
	protected function isMemberOfTerritoire(User $user, $territoireId)
	{
		if ( $user->getPersonnage() )
		{
			$groupe = $user->getPersonnage()->getGroupe();
			if ( $user->getPersonnage()->getGroupe() )
			{
				foreach ( $groupe->getTerritoire() as $territoire )
				{
					if ( $territoire->getId() == $territoireId ) return true;
				}
			}
			
		}
		return false;
	}
	
	/**
	 * Test si un utilisateur est bien relié au joueur
	 * 
	 * @param unknown $user
	 * @param unknown $joueurId
	 */
	protected function isOwnerOfJoueur($user, $joueurId)
	{
		$joueur =  $user->getJoueur();
		return $joueur &&  $joueur->getId() == $joueurId;
	}
	
	/**
	 * Test si l'utilisateur a des informations joueurs enregistré
	 *
	 * @param LarpManager\Entities\User $user
	 */
	protected function isNotRegistered($user)
	{
		return empty($user->getJoueur);
	}
		
	/**
	 * Vérifie si l'utilisateur dispose du rôle demandé
	 * 
	 * @param Token $token
	 * @param string $role
	 */
	protected function hasRole($token, $role)
	{
		return VoterInterface::ACCESS_GRANTED == $this->roleHierarchyVoter->vote($token, null, array($role));
	}
	
}