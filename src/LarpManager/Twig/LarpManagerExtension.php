<?php 

namespace LarpManager\Twig;

/**
 *  LarpManager\Twig\LarpManagerExtension
 */
class LarpManagerExtension extends \Twig_Extension
{
	/**
	 * Définition des nouvelles extensions de twig
	 */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('forumRight', array('LarpManager\ForumRightManager', 'right')),
        	new \Twig_SimpleFilter('time_diff', array($this, 'time_diff'))
        );
    }
    
    /**
     * Nom de l'extension
     * 
     * @return string
     */
    public function getName()
    {
        return 'larpmanager_extension';
    }
    
    /**
     * Calcul l'interval de temps entre maintenant et une date
     * 
     * @param \Datetime $date
     */
    public function time_diff(\Datetime $date)
    {
    	$interval = $date->diff(new \Datetime('NOW'));
    	if ( $interval->days == 0 )	return "aujourd'hui";
    	
    	return $interval->format('il y a %a jours');
    }
}