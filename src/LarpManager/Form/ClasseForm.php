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

/**
 * LarpManager\Form\ClasseForm
 *
 * @author kevin
 *
 */
class ClasseForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label_masculin','text', array(
					'required' => true,	
				))
				->add('image_m','text', array(
					'label' => 'Adresse de l\'image utilisé pour représenté cette classe',
					'required' => true,
				))
				->add('label_feminin','text', array(
					'required' => true,	
				))
				->add('image_f','text', array(
					'label' => 'Adresse de l\'image utilisé pour représenté cette classe',
					'required' => true,
				))
				->add('description','textarea', array(
					'required' => false,)
				)
				->add('competenceFamilyFavorites','entity', array(
					'label' => "Famille de compétences favorites (n'oubliez pas de cochez aussi la/les compétences acquises à la création)",
					'required' => false,
					'property' => 'label',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'class' => 'LarpManager\Entities\CompetenceFamily',	)
				)
				->add('competenceFamilyNormales','entity', array(
					'label' => "Famille de compétences normales",
					'required' => false,
					'property' => 'label',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'class' => 'LarpManager\Entities\CompetenceFamily',	)
				)
				->add('competenceFamilyCreations','entity', array(
					'label' => "Famille de compétences acquises à la création",
					'required' => false,
					'property' => 'label',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'class' => 'LarpManager\Entities\CompetenceFamily',	)
				);
	}
	
	/**
	 * Définition de l'entité concernée
	 * 
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Classe',
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'classe';
	}
}