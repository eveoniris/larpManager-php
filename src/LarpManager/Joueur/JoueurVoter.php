<?php

namespace LarpManager\Joueur;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;

/**
 * LarpManager\Joueur\JoueurVoter
 * 
 * @author kevin
 *
 */
class JoueurVoter implements VoterInterface
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
				'JOUEUR_OWNER',
				'JOUEUR_NOT_REGISTERED'
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
	
	/**
	 * Test l'authorisation demandé et fourni soit ACCSESS_GRANTED, soit ACCESS_DENIED, soit ACCESS_ABSTAIN
	 * 
	 * @param TokenInterface $token
	 * @param unknown $object
	 * @param array $attributes
	 */
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
			
			if ($attribute == 'JOUEUR_OWNER') {
				$joueurId = $object;
				return $this->isOwnerOf($user, $joueurId) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
			if ($attribute == 'JOUEUR_NOT_REGISTERED') {
				return $this->isNotRegistered($user) ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
			}
		}
		
		return VoterInterface::ACCESS_ABSTAIN;
	}
	
	/**
	 * Test si un utilisateur est bien relié au joueur
	 * @param unknown $user
	 * @param unknown $joueurId
	 */
	protected function isOwnerOf($user, $joueurId)
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
	 * Test si un utilisateur dispose du role demandé
	 * 
	 * @param unknown $token
	 * @param unknown $role
	 */
	protected function hasRole($token, $role)
	{
		return VoterInterface::ACCESS_GRANTED == $this->roleHierarchyVoter->vote($token, null, array($role));
	}
	
}