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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Form\BackgroundForm
 *
 * @author kevin
 *
 */
class BackgroundForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('titre','text', array(
					'required' => true,
					'label' => 'Titre',
				))
				->add('text','textarea', array(
					'required' => true,
					'label' => 'Contenu',
					'attr' => array(
						'class' => 'tinymce',
						'rows' => 15),
				))
				->add('groupe','entity', array(
						'required' => true,
						'label' => 'Groupe',
						'class' => 'LarpManager\Entities\Groupe',
						'property' => 'nom',
				))
				->add('gn', 'entity', array(
						'required' => true,
						'label' => 'GN',
						'class' => 'LarpManager\Entities\Gn',
						'property' => 'label',
						'placeholder' => 'Choisissez le GN auquel est lié ce background',
						'empty_data'  => null
				));
	}
	
	/**
	 * Définition de l'entité concerné
	 * 
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Background',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'background';
	}
}