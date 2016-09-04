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
namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\ParticipantBilletForm
 *
 * @author kevin
 *
 */
class ParticipantBilletForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('billet','entity', array(
				'label' => 'Choisissez le billet a donner à cet utilisateur',
				'multiple' => false,
				'expanded' => true,
				'required' => true,
				'class' => 'LarpManager\Entities\Billet',
				'property' => 'fullLabel',
				'query_builder' => function( $er) {
					$qb = $er->createQueryBuilder('b');
					$qb->orderBy('b.gn', 'ASC');
					return $qb;
				},
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
		 ));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'participantBillet';
	}
} 