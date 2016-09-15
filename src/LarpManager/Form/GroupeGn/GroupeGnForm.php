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

use LarpManager\Repository\ParticipantRepository;

/**
 * LarpManager\Form\GroupeGn\GroupeGnForm
 *
 * @author kevin
 *
 */
class GroupeGnForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('gn','entity', array(
					'label' => 'Jeu',
					'required' => true,
					'class' => 'LarpManager\Entities\Gn',
					'property' => 'label',
				))
				->add('free','choice', array(
					'label' => 'Groupe disponible ou réservé ?',
					'required' => false,
					'choices' => array(
						true => 'Groupe disponible',
						false => 'Groupe réservé',
					),
				))
				->add('code','text', array(
						'required' => false,
				))
				->add('jeuStrategique','checkbox', array(
						'label' => "Participe au jeu stratégique",
						'required' => false,
				))
				->add('jeuMaritime','checkbox', array(
						'label' => "Participe au jeu maritime",
						'required' => false,
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
				'class' => 'LarpManager\Entities\GroupeGn',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'groupeGn';
	}
}