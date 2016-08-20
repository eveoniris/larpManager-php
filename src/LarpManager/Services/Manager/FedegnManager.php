<?php

namespace LarpManager\Services\Manager;

use Silex\Application;
use LarpManager\Entities\EtatCivil;

/**
 * LarpManager\Services\Manager\FedegnManager
 * 
 * @author kevin
 */
class FedegnManager
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
	 * Suppression des accents (pour construire le cleanname)
	 * 
	 * @param unknown $str
	 * @param string $charset
	 */
	private function remove_accents($str, $charset='utf-8')
	{
		$str = htmlentities($str, ENT_NOQUOTES, $charset);
	
		$str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
		$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
	
		return $str;
	}
	
	/**
	 * Supression du BOM en début de chaine
	 * 
	 * @param unknown $text
	 */
	private function remove_utf8_bom($text)
	{
		$bom = pack('H*','EFBBBF');
		$text = preg_replace("/^$bom/", '', $text);
		return $text;
	}
	
	
	/**
	 * Fourni le cleanname utilisé par la fédégn
	 * 
	 * @param string $prenom
	 * @param string $nom
	 */
	public function cleanname($prenom, $nom)
	{
		return strtolower($this->remove_accents($prenom.$nom));
	}
	
	/**
	 * Test si l'utilisateur dispose d'un pass GN
	 * 
	 * @param EtatCivil $etatCivil
	 */
	public function test(EtatCivil $etatCivil)
	{
		$url = $this->app['config']['fedegn']['url'];
		$password= $this->app['config']['fedegn']['password'];
		$cleanname = $this->cleanname($etatCivil->getPrenom(), $etatCivil->getNom());
		$birthdate = $etatCivil->getDateNaissance()->format('Y-m-d');
		$year = new \Datetime('NOW');
		$year = $year->format('Y'); 
	
		
		$result = $this->remove_utf8_bom(file_get_contents($url.'?password='.$password.'&cleanname='.$cleanname.'&birthdate='.$birthdate.'&year='.$year));

		if ( strcmp($result,"false") === 0 )
		{
			return false;
		}
		else
		{
			return $result;
		}
	}
}
