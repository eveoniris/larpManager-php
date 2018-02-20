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

namespace LarpManager\Form\Culture;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use LarpManager\Form\Type\ClasseType;

/**
 * LarpManager\Form\Groupe\CultureForm
 *
 * @author kevin
 *
 */
class CultureForm extends AbstractType
{
	/**
	 * Contruction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label','text')
				->add('description','textarea', array(
						'required' => false,
						'label' => 'Description succinte',
						'attr' => array(
							'class' => 'tinymce',
							'row' => 9,
						),
				))
				->add('descriptionComplete','textarea', array(
						'required' => false,
						'label' => 'Description complète de la culture (accessible aux joueurs membres des territoires correspondant à cette culture)',
						'attr' => array(
								'class' => 'tinymce',
								'row' => 9,
						),
				))
				->add('submit', 'submit', array('label' => 'Valider'));
	}

	/**
	 * Définition de l'entité conercné
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\Culture',
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'culture';
	}
}
