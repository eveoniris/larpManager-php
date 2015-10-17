<?php 

namespace LarpManager\Twig;

use LarpManager\Services\ForumRightManager;
use Doctrine\ORM\PersistentCollection;
use Silex\Application;

/**
 *  LarpManager\Twig\LarpManagerExtension
 */
class LarpManagerExtension extends \Twig_Extension
{
	protected $forumRightManager;
	
	protected $app;
	
	/**
	 * Constructeur. Initialise le gestionnaire de droit du forum
	 * 
	 * @param Application $app
	 */
	public function __construct(Application $app)
	{
		$this->forumRightManager = new ForumRightManager();
		$this->app = $app;
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
       		new \Twig_SimpleFilter('explainVisibility', array($this, 'explainVisibility')),
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
     * Fourni la 'traduction' d'une visibilité
     * @param unknown $visibility
     */
    public function explainVisibility($visibility)
    {
    	if ( array_key_exists($visibility, $this->app['larp.manager']->getVisibility()) )
    	{
    		return $this->app['larp.manager']->getVisibility()[$visibility];
    	}
    		
    	return false;
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