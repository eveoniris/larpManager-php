<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use LarpManager\Form\PersonnageForm;

class PersonnageController
{
	
	/**
	 * Fourni des informations sur une classe
	 *
	 * @param Application $app
	 * @param Request $request
	 */
	public function classeAction(Application $app, Request $request)
	{
		$id = $request->get('classeId');
		
		$classe = $app['orm.em']->find('\LarpManager\Entities\Classe',$id);
		
		return $app['twig']->render('classe/info.twig', array('classe' => $classe));
		
	}
	
	/**
	 * Fourni la liste des compétences en fonction de la classe
	 * 
	 * @param Application $app
	 * @param Request $request
	 */
	public function competenceListAction(Application $app, Request $request)
	{
		$id = $request->get('classeId');
		
		$classe = $app['orm.em']->find('\LarpManager\Entities\Classe',$id);
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Competence');
		$competences = $repo->findAll();
		
		$result = array(
				'favorites' => array(),
				'normales' => array(),
				'creations' => array(),
				'autres' => array(),
		);
		
		foreach ( $classe->getCompetenceFavorites() as $competence)
		{
			$result['favorites'][] = array(
				'id' => $competence->getId(),
				'label' => $competence->getNom(),
				'description' => $competence->getDescription()
			);
			$key = array_search($competence,$competences);
			unset($competences[$key]);
		}
		
		foreach ( $classe->getCompetenceNormales() as $competence)
		{
			$result['normales'][] = array(
					'id' => $competence->getId(),
					'label' => $competence->getNom(),
					'description' => $competence->getDescription()
			);
			
			$key = array_search($competence,$competences);
			unset($competences[$key]);
		}
		
		foreach ( $classe->getCompetenceCreations() as $competence)
		{
			$result['creations'][] = array(
					'id' => $competence->getId(),
					'label' => $competence->getNom(),
					'description' => $competence->getDescription()
			);
			
			$key = array_search($competence,$competences);
			unset($competences[$key]);
		}
		
		foreach ( $competences as $competence) {
			$result['autres'][] = array(
					'id' => $competence->getId(),
					'label' => $competence->getNom(),
					'description' => $competence->getDescription()
			);
		}
			
		return $app->json($result);
	}
}