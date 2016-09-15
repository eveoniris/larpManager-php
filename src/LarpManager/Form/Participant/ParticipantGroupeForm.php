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
namespace LarpManager\Form\Participant;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Form\Participant\ParticipantGroupeForm
 *
 * @author kevin
 *
 */
class ParticipantGroupeForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('groupeGn','entity', array(
				'label' => 'Choisissez le groupe a affecter à cet utilisateur',
				'multiple' => false,
				'expanded' => true,
				'required' => true,
				'class' => 'LarpManager\Entities\GroupeGn',
				'property' => 'groupe.nom',
				'query_builder' => function(EntityRepository $er) use ($options) {
					$qb = $er->createQueryBuilder('gg');
					$qb->join('gg.groupe', 'g');
					$qb->join('gg.gn', 'gn');
					$qb->orderBy('g.nom', 'ASC');
					$qb->where('gn.id = :gnId');
					$qb->setParameter('gnId', $options['gnId']);
					return $qb;
				}
		));
	}


	/**
	 * Définition de l'entité concerné
	 *
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
		 	'class' => 'LarpManager\Entities\Participant',
			'gnId' => null,
		 ));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'participantGroupe';
	}
} 