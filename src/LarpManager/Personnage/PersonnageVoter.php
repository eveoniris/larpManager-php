<?php

namespace LarpManager\Personnage;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;


class PersonnageVoter implements VoterInterface
{
	/** @var RoleHierarchyVoter */
	protected $roleHierarchyVoter;
	
	/**
	 * @param RoleHierarchyVoter $roleHierarchyVoter
	 */
	public function __construct(RoleHierarchyVoter $roleHierarchyVoter)
	{
		$this->roleHierarchyVoter = $roleHierarchyVoter;
	}
	
	/**
	 * Checks if the voter supports the given attribute.
	 *
	 * @param string $attribute An attribute
	 *
	 * @return Boolean true if this Voter supports the attribute, false otherwise
	 */
	public function supportsAttribute($attribute)
	{
		return in_array($attribute, array(
				'OWN_PERSONNAGE',
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
			
			if ($this->hasRole($token, 'ROLE_SCENARISTE')) {
				return VoterInterface::ACCESS_GRANTED;
			}
				
			if ($attribute == 'OWN_PERSONNAGE') {
				$personnageId = $object;
				return $this->isOwnerOf($user, $personnageId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
		}
	
		return VoterInterface::ACCESS_ABSTAIN;
	}
	
	/**
	 * Test si un utilisateur posséde bien le personnage
	 * 
	 * @param unknown $user
	 * @param unknown $personnageId
	 */
	protected function isOwnerOf($user, $personnageId)
	{
		$personnage = $user->getPersonnage();
		if ( $personnage )
		{
			return $personnage->getId() == $personnageId;
		}
		return false;
	}
	
	protected function hasRole($token, $role)
	{
		return VoterInterface::ACCESS_GRANTED == $this->roleHierarchyVoter->vote($token, null, array($role));
	}
}
	