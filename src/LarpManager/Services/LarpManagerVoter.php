<?php

namespace LarpManager\Services;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;

/**
 * LarpManager\LarpManagerVoter
 * @author kevin
 *
 */
class LarpManagerVoter implements VoterInterface
{
	/** @var RoleHierarchyVoter */
	protected $roleHierarchyVoter;
	
	/**
	 * Constructeur
	 * 
	 * @param RoleHierarchyVoter $roleHierarchyVoter
	 */
	public function __construct(RoleHierarchyVoter $roleHierarchyVoter)
	{
		$this->roleHierarchyVoter = $roleHierarchyVoter;
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
				'GROUPE_MEMBER',
				'GROUPE_RESPONSABLE',
				'GROUPE_SECONDAIRE_MEMBER',
				'GROUPE_SECONDAIRE_RESPONSABLE',
				'JOUEUR_OWNER',
				'JOUEUR_NOT_REGISTERED',
				'OWN_PERSONNAGE',
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
				$groupeId = $object;
				return $this->isResponsableOf($user, $groupeId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'GROUPE_MEMBER') {
				$groupeId = $object;
				return $this->isMemberOf($user, $groupeId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
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
				return $this->hasTopicRight($user, $topic) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
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
	protected function hasTopicRight($user, $topic)
	{
		switch ( $topic->getRight() )
		{
			case 'GN_PARTICIPANT' :
				return $this->userGnRight($topic->getObjectId(), $user);
				break;
			case 'GROUPE_MEMBER' :
				return $this->userGroupeRight($topic->getObjectId(), $user);
				break;
			case 'GROUPE_SECONDAIRE_MEMBRE' :
				return $this->userGroupeSecondaireRight($topic->getObjectId(), $user);
				break;
			case 'CULTE' :
				return $this->userCulteRight($topic->getObjectId(), $user);
			default :
				return true;
		}
	}
	
	/**
	 * Determine si l'utilisateur a le droit d'accéder aux forums de ce culte
	 * (le personnage de l'utilisateur doit être pratiquant de ce culte)
	 * 
	 * @param unknown $culteId
	 * @param unknown $user
	 */
	protected function userCulteRight($culteId, $user)
	{
		$joueur =  $user->getJoueur();
		
		if ( $joueur && $joueur->getPersonnage() )
		{
			if ( $joueur->getPersonnage()->getPersonnageReligion() )
			{
				if ( $joueur->getPersonnage()->getPersonnageReligion()->getReligion()->getId() == $culteId )
				{
					return true;
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
	protected function userGnRight($gnId, $user)
	{
		$joueur =  $user->getJoueur();
	
		if ( $joueur)
		{
			foreach ( $joueur->getGns() as $gn )
			{
				if ( $gn->getId() == $gnId) return true;
			}
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
	protected function userGroupeRight($groupeId, $user)
	{
		if ( $user->getGroupes() )
		{
			foreach ( $user->getGroupes() as $groupe)
			{
				if ( $groupe->getId() == $groupeId ) return true;
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
		foreach( $user->getPosts() as $post)
		{
			if ( $post instanceOf \LarpManager\Entities\Post
				&& $post->getId() == $postId)
				return true;
		}
		return false;
	}
	
	/**
	 * Test si un utilisateur est responsable d'un groupe
	 * 
	 * @param unknown $user
	 * @param unknown $groupeId
	 */
	protected function isResponsableOf($user, $groupeId)
	{
		foreach( $user->getGroupeResponsable() as $groupe)
		{
			if ( $groupe instanceof \LarpManager\Entities\Groupe
				&& $groupe->getId() == $groupeId)
				return true;
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
		
		if ( $user->getGroupes() )
		{
			foreach ( $user->getGroupes() as $groupe )
			{
				if ( $groupe->getId() == $groupeId)	return true;
			}
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
		return true;
		if ( $user->getGroupes() )
		{
			foreach ( $user->getGroupes() as $groupe )
			{
				if ( $groupe->getId() == $groupeId)	return true;
			}
		}
			
		return false;
	}
	
	/**
	 * Test si un utilisateur posséde bien le personnage
	 *
	 * @param unknown $user
	 * @param unknown $personnageId
	 */
	protected function isOwnerOfPersonnage($user, $personnageId)
	{
		if ( $user->getPersonnage() )
		{
			if ( $user->getPersonnage()->getId() == $personnageId ) return true;
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