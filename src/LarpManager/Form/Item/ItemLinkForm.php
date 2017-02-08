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

namespace LarpManager\Form\Item;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\Monnaie\ItemForm
 *
 * @author kevin
 *
 */
class ItemForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('personnage','entity', array(
						'required' => false,
						'mapped' => false,
						'label' => 'Personnage',
						'class' => 'LarpManager\Entities\Personnage',
						'property' => 'nom',
						'attr' => array(
							'help' => 'Personnage qui possède cet objet',
						),					
				))
				->add('groupe','entity', array(
						'required' => false,
						'mapped' => false,
						'label' => 'Groupe',
						'class' => 'LarpManager\Entities\Groupe',
						'property' => 'nom',
						'attr' => array(
							'help' => 'Groupe qui possède cet objet',
						),
				))
				->add('lieu','entity', array(
						'required' => false,
						'mapped' => false,
						'label' => 'Lieu',
						'class' => 'LarpManager\Entities\Lieu',
						'property' => 'label',
						'attr' => array(
							'help' => 'Lieu ou est entreposé cet objet',
						),
				))
				->add('submit', 'submit', array(
						'label' => 'Valider',
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
				'class' => 'LarpManager\Entities\Item',
		));
	}
	
	/**
	 * 
	 * 
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'item';
	}
}