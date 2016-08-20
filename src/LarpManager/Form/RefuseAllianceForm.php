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
 * LarpManager\Form\AgeForm
 *
 * @author kevin
 *
 */
class RefuseAllianceForm extends AbstractType
{

	/**
	 * Construction du formulaire
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('messageAllie','textarea', array(
				'label' => 'Un petit mot pour expliquer votre démarche',
				'required' => true,
				'attr' => array(
						'class' => 'tinymce',
						'rows' => 9,
						'help' => 'Ce texte sera transmis au chef de groupe concerné.'),
		));
	}

	/**
	 * Définition de la classe d'entité concernée
	 *
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\GroupeAllie',
		));
	}

	/**
	 * Nom du formlaire
	 */
	public function getName()
	{
		return 'refuseAlliance';
	}
}