<?php

namespace LarpManager\Groupe;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;

/**
 * LarpManager\Groupe\GroupeVoter
 * @author kevin
 *
 */
class GroupeVoter implements VoterInterface
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
				'GROUPE_MEMBER',
				'GROUPE_RESPONSABLE',
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
		}
		
		return VoterInterface::ACCESS_ABSTAIN;
	}
	
	/**
	 * Test si un utilisateur est responsable d'un groupe
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
	 * @param unknown $user
	 * @param unknown $groupeId
	 */
	protected function isMemberOf($user, $groupeId)
	{
		$groupe = $user->getGroupe();
		
		if ( $groupe instanceof \LarpManager\Entities\Groupe
			&& $groupe->getId() == $groupeId)
			return true;
			
		return false;
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