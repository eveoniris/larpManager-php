<?php

namespace LarpManager\Services\Manager;

/**
 * LarpManager\Services\Manager\ForumRightManager
 * 
 * @author kevin
 */
class ForumRightManager
{
    /**
     * Gestion des droits d'accés aux forums
     * 
     * @param unknown $topic
     * @return boolean
     */
    public function right($topic, $user)
    {
    	switch ( $topic->getRight() )
    	{
    		case 'GN_PARTICIPANT' :
    			return ForumRightManager::userGnRight($topic->getObjectId(), $user);
    			break;
    		case 'GROUPE_MEMBER' :
    			return ForumRightManager::userGroupeRight($topic->getObjectId(), $user);
    			break;
    		case 'TERRITOIRE_MEMBER' :
    			return ForumRightManager::userTerritoireRight($topic->getObjectId(), $user);
    			break;
    		default :
    			return true;
    	}
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
    protected function userGroupeRight($groupeId, $user)
    {
    	foreach ( $user->getGroupes() as $groupe)
    	{
    		if ( $groupe->getId() == $groupeId ) return true;
    	}
    	return false;
    }
    
    /**
     * Determine si l'utilisateur à le droit d'accéder au forum de ce territoire
     * (l'utilisateur doit être membre d'un groupe lié à ce territoire)
     * @param unknown $territoireId
     * @param unknown $user
     */
    protected function userTerritoireRight($territoireId, $user)
    {
    	if ( $user->getGroupe() ) 
    	{
    		$territoire = $user->getGroupe()->getTerritoire();
    		if ( $territoire && $territoire->getId() == $territoireId) return true;
    	}
    	return false;
    }
}
