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

namespace LarpManager\Form\GroupeGn;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use LarpManager\Repository\PersonnageRepository;
use LarpManager\Repository\ParticipantRepository;

/**
 * LarpManager\Form\GroupeGn\GroupeGnOrdreForm
 *
 * @author Kevin F.
 *
 */
class GroupeGnOrdreForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('initiative', 'integer', array(
				'required' => false
			))
			->add('bateaux', 'integer', array(
				'required' => false
			))
			->add('agents', 'integer', array(
				'required' => false
			))
			->add('sieges', 'integer', array(
				'required' => false,
				'label' => 'Armes de Siège'
			))
			->add('suzerain','entity', array(
				'required' => false,
				'class' => 'LarpManager\Entities\Participant',
				'property' => 'personnage.nom',
				'query_builder' => function(ParticipantRepository $er) use ($options) {
					$qb = $er->createQueryBuilder('p');
					$qb->join('p.personnage', 'u');
					$qb->join('p.groupeGn', 'gg');
					$qb->orderBy('u.nom', 'ASC');
					$qb->where('gg.id = :groupeGnId');
					$qb->setParameter('groupeGnId', $options['groupeGnId']);
					return $qb;
				},
				'attr' => array(
						'class'	=> 'selectpicker',
						'data-live-search' => 'true',
						'placeholder' => 'Responsable',
				),
			))
		;
	}
	
	/**
	 * Définition de l'entité concerné
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'class' => 'LarpManager\Entities\GroupeGn',
            'groupeGnId' => false,
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'groupeGnOrdre';
	}
}