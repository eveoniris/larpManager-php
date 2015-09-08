<?php 

namespace LarpManager\Twig;
use LarpManager\Services\ForumRightManager;
use Doctrine\ORM\PersistentCollection;

/**
 *  LarpManager\Twig\LarpManagerExtension
 */
class LarpManagerExtension extends \Twig_Extension
{
	protected $forumRightManager;
	
	public function __construct()
	{
		$this->forumRightManager = new ForumRightManager();
	}
	
	/**
	 * Définition des nouvelles extensions de twig
	 */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('forumRight', array($this->forumRightManager, 'right')),
        	new \Twig_SimpleFilter('time_diff', array($this, 'time_diff')),
        	new \Twig_SimpleFilter('reverse', array($this, 'reverse')),
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
    
    /**
     * Renverse un tableau
     * 
     * @param Array $array
     */
    public function reverse(PersistentCollection $object)
    {
    	$array = $object->toArray();
    	return array_reverse($array);
    }
}