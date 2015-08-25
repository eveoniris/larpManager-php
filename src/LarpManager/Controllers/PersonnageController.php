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
	public function classeAction(Request $request, Application $app )
	{
		$id = $request->get('classeId');
		
		$classe = $app['orm.em']->find('\LarpManager\Entities\Classe',$id);
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Niveau');
		$niveaux = $repo->findAll();
		
		$repo = $app['orm.em']->find('\LarpManager\Entities\Competence');
		$competences = $repo->findAll();
		
		$niv = array();
		foreach ($niveaux as $niveau)
		{
			$niv[$niveau->getNiveau()] = array(
				'label' => $niveau->getLabel(),
				'description' => $niveau->getLabel(),
				'coutFavori' => $niveau->getCoutFavori(),
				'coutNormal' => $niveau->getCout(),
				'coutMeconnu' => $niveau->getCoutMeconu(),
			);
		}
		
		$comps = array();
		foreach ( $classe->getCompetenceCreations() as $competence)
		{
			$comps[$competence->getId()] = array(
						'type' => 'creation',
						'id' => $competence->getId(),
						'niveaux' =>  array()
					);
			
			foreach ( $competence->getNiveaux() as $niveau)
			{
				$comps[$competence->getId()]['niveaux'][$niveau->getNiveau()] = array(
						'niveau' => $niveau->getNiveau(),
						'description' =>$niveau->getDescription(),
					);						
			}
		}
		
		foreach ( $classe->getCompetenceFavorites() as $competence)
		{
			if ( isset($comps[$competence->getId()])) continue;
			
			$comps[$competence->getId()] = array(
				'type' => 'favorite',
				'id' => $competence->getId(),
				'niveaux' =>  array()
			);
			
			foreach ( $competence->getNiveaux() as $niveau)
			{
				$comps[$competence->getId()]['niveaux'][$niveau->getNiveau()] = array(
						'niveau' => $niveau->getNiveau(),
						'description' =>$niveau->getDescription(),
					);						
			}
		}
		
		foreach ( $classe->getCompetenceNormales() as $competence)
		{
			if ( isset($comps[$competence->getId()])) continue;
			
			$comps[$competence->getId()] = array(
				'type' => 'normale',
				'id' => $competence->getId(),
				'niveaux' =>  array()
			);
			
			foreach ( $competence->getNiveaux() as $niveau)
			{
				$comps[$competence->getId()]['niveaux'][$niveau->getNiveau()] = array(
						'niveau' => $niveau->getNiveau(),
						'description' =>$niveau->getDescription(),
					);						
			}
		}
		
		return $app->json(array(
					'competences' => $comps,
					'niveaux' => $niv,
					'html_classe' => $app['twig']->render('classe/info.twig', array('classe' => $classe)),
					'html_competences' => $app['twig']->render('competence/selection.twig', array('competences' => $competences)),
				));
		
	}
	
	/**
	 * Fourni des informations sur une compétence
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function competenceAction(Request $request, Application $app)
	{
		
	}
	
	/**
	 * Fourni la liste des compétences en fonction de la classe
	 * 
	 * @param Application $app
	 * @param Request $request
	 */
	public function competenceListAction(Request $request, Application $app)
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