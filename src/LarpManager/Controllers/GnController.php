<?php

/**
 * LarpManager - A Live Action Role Playing Manager
 * Copyright (C) 2016 Kevin Polez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

use LarpManager\Form\GnForm;
use LarpManager\Form\ParticipantFindForm;
use LarpManager\Entities\Gn;
use JasonGrimes\Paginator;

use Doctrine\Common\Collections\ArrayCollection;


/**
 * LarpManager\Controllers\GnController
 *
 * @author kevin
 *
 */
class GnController
{
	/**
	 * @description affiche la liste des gns
	 */
	public function listAction(Request $request, Application $app) 
	{
		$order_by = $request->get('order_by') ?: 'id';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;
		
		$criteria = array();
		
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Gn');
		$gns = $repo->findBy(
				$criteria,
				array( $order_by => $order_dir),
				$limit,
				$offset);
		
		$numResults = $repo->findCount($criteria);
		
		$paginator = new Paginator($numResults, $limit, $page,
				$app['url_generator']->generate('gn.list') . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
		
		return $app['twig']->render('admin/gn/list.twig', array(
				'gns' => $gns,
				'paginator' => $paginator,
		));
	}
	
	/**
	 * affiche le formulaire d'ajout d'un gn
	 * Lorsqu'un GN est créé, son forum associé doit lui aussi être créé
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{	
		$form = $app['form.factory']->createBuilder(new GnForm(), new Gn())
			->add('save','submit', array('label' => "Sauvegarder"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$gn = $form->getData();
			
			/**
			 * Création du topic associé à ce gn
			 * @var \LarpManager\Entities\Topic $topic
			 */
			$topic = new \LarpManager\Entities\Topic();
			$topic->setTitle($gn->getLabel());
			$topic->setDescription($gn->getDescription());
			$topic->setUser($app['user']);
			// défini les droits d'accés à ce forum
			// (les participants au GN ont le droits d'accéder à ce forum)
			$topic->setRight('GN_PARTICIPANT');
			
			$gn->setTopic($topic);
			$app['orm.em']->persist($gn);
			$app['orm.em']->flush();			

			$app['orm.em']->persist($topic);
			$topic->setObjectId($gn->getId());
			$app['orm.em']->persist($gn);
			$app['orm.em']->flush();
	
			$app['session']->getFlashBag()->add('success', 'Le gn a été ajouté.');
	
			return $app->redirect($app['url_generator']->generate('gn.list'),301);
		}
	
		return $app['twig']->render('admin/gn/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Detail d'un gn
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app, Gn $gn)
	{	
		if ( $app['security.authorization_checker']->isGranted('ROLE_ADMIN') )
		{
			return $app['twig']->render('admin/gn/detail.twig', array('gn' => $gn));
		}
		else
		{
			return $app['twig']->render('public/gn/detail.twig', array('gn' => $gn));
		}	
	}
		
	/**
	 * Liste des participants à un jeu
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Gn $gn
	 */
	public function participantsAction(Request $request, Application $app, Gn $gn)
	{
		$order_by = $request->get('order_by') ?: 'ec.nom';
		$order_dir = $request->get('order_dir') == 'DESC' ? 'DESC' : 'ASC';
		$limit = (int)($request->get('limit') ?: 50);
		$page = (int)($request->get('page') ?: 1);
		$offset = ($page - 1) * $limit;

		// nombre de participant au total
		$qbTotal = $app['orm.em']->createQueryBuilder();
		$qbTotal->select('COUNT(p.id)')
			->from('\LarpManager\Entities\Participant', 'p')
			->join('p.gn', 'gn')
			->join('p.user', 'u')
			->join('u.etatCivil', 'ec')
			->where('gn.id = :gnId')
			->setParameter('gnId', $gn->getId());
		
		// liste des participants à afficher
		$qb = $app['orm.em']->createQueryBuilder();
		$qb->select('p')
			->from('\LarpManager\Entities\Participant', 'p')
			->join('p.gn', 'gn')
			->join('p.user', 'u')
			->join('u.etatCivil', 'ec')
			->where('gn.id = :gnId')
			->setParameter('gnId', $gn->getId())
			->orderBy($order_by, $order_dir)
			->setMaxResults($limit)
			->setFirstResult($offset);
		
		$form = $app['form.factory']->createBuilder(new ParticipantFindForm())->getForm();
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$data = $form->getData();
			
			switch ( $data['type'] )
			{
				case 'nom' :
					$qb->andWhere("ec.nom LIKE :value");
					$qbTotal->andWhere("ec.nom LIKE :value");
					break;
				case 'email' :
					$qb->andWhere("u.email LIKE :value");
					$qbTotal->andWhere("u.email LIKE :value");
					break;
			}
			$qb->setParameter('value','%'.$data['value'].'%');
			$qbTotal->setParameter('value', '%'.$data['value'].'%');
		}
				
		$participants = $qb->getQuery()->getResult();
		$count =  $qbTotal->getQuery()->getSingleScalarResult();
						
		$paginator = new Paginator($count, $limit, $page,
				$app['url_generator']->generate('gn.participants', array('gn' => $gn->getId())) . '?page=(:num)&limit=' . $limit . '&order_by=' . $order_by . '&order_dir=' . $order_dir
				);
		
		return $app['twig']->render('admin/gn/participants.twig', array(
				'gn' => $gn,
				'participants' => $participants,
				'paginator' => $paginator,
				'form' => $form->createView(),
		));
		
	}
	
	/**
	 * Génére le fichier à envoyer à la FédéGN
	 * 
	 * @param Request $request
	 * @param Application $app
	 * @param Gn $gn
	 */
	public function fedegnAction(Request $request, Application $app, Gn $gn)
	{
		$participants = $gn->getParticipantsFedeGn();

		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=eveoniris_fedegn_".date("Ymd").".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
			
		$output = fopen("php://output", "w");
		
		// header
		fputcsv($output,
			array(
			'Nom',
			'Prénom',
			'Email',
			'Date de naissance'), ';');
			
		foreach ( $participants as $participant)
		{
			$line = array();
			$line[] = utf8_decode($participant->getUser()->getEtatCivil()->getNom());
			$line[] = utf8_decode($participant->getUser()->getEtatCivil()->getPrenom());
			$line[] = utf8_decode($participant->getUser()->getEmail());
			$line[] = utf8_decode($participant->getUser()->getEtatCivil()->getDateNaissance()->format('Y-m-d'));
			fputcsv($output, $line, ';');
		}
		
		fclose($output);
		exit();
	}
	
	
	/**
	 * Met à jour un gn
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app, Gn $gn)
	{	
		$form = $app['form.factory']->createBuilder(new GnForm(), $gn)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$gn = $form->getData();
	
			if ($form->get('update')->isClicked())
			{	
				$app['orm.em']->persist($gn);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le gn a été mis à jour.');
			}
			else if ($form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($gn);
				$app['orm.em']->flush();
					
				$app['session']->getFlashBag()->add('success', 'Le gn a été supprimé.');
			}
	
			return $app->redirect($app['url_generator']->generate('gn.list'));
		}
	
		return $app['twig']->render('admin/gn/update.twig', array(
				'gn' => $gn,
				'form' => $form->createView(),
		));
	}

	
	/**
	 * Affiche la billetterie d'un GN
	 *
	 * @param Application $app
	 * @param Request $request
	 * @param Gn $gn
	 */
	public function billetterieAction(Application $app, Request $request, Gn $gn)
	{
		$groupeGns = $gn->getGroupeGnsPj();
		$iterator = $groupeGns->getIterator();
		$iterator->uasort(function ($a, $b) {
			return ($a->getGroupe()->getNumero() < $b->getGroupe()->getNumero()) ? -1 : 1;
		});
		$groupeGns = new ArrayCollection(iterator_to_array($iterator));
		
	
		return $app['twig']->render('public/gn/billetterie.twig', array(
				'gn' => $gn,
				'groupeGns' => $groupeGns,
		));
	}
	

	/**
	 * Liste des groupes prévu sur le jeu
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 */
	public function groupesAction(Request $request, Application $app, Gn $gn)
	{
		$groupeGns = $gn->getGroupeGnsPj();
		$iterator = $groupeGns->getIterator();
		$iterator->uasort(function ($a, $b) {
			return ($a->getGroupe()->getNumero() < $b->getGroupe()->getNumero()) ? -1 : 1;
		});
		$groupeGns = new ArrayCollection(iterator_to_array($iterator));
		
		$participant = $app['user']->getParticipant($gn);
		if ( ! $participant )
		{
			$app['session']->getFlashBag()->add('error', 'Vous devez participer à ce jeu pour voir la liste des groupes');
			return $app->redirect($app['url_generator']->generate('homepage'));
		}

		return $app['twig']->render('public/gn/groupes.twig', array(
				'groupeGns' => $groupeGns,
				'participant' => $participant,
		));
	}
	
	/**
	 * Liste des groupes réservés
	 *
	 * @param Request $request
	 * @param Application $app
	 * @param Participant $participant
	 */
	public function groupesReservesAction(Request $request, Application $app, Gn $gn)
	{
		$groupes = $gn->getGroupesReserves();
		$iterator = $groupes->getIterator();
		$iterator->uasort(function ($a, $b) {
			return ($a->getNumero() < $b->getNumero()) ? -1 : 1;
		});
		
		$groupes = new ArrayCollection(iterator_to_array($iterator));
	
		return $app['twig']->render('admin/gn/groupes.twig', array(
			'groupes' => $groupes,
			'gn' => $gn,
		));
	}
}
