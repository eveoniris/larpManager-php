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

namespace LarpManager\Form\Intrigue;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Form\Groupe\IntrigueRelectureForm
 *
 * @author kevin
 *
 */
class IntrigueRelectureForm extends AbstractType
{
	/**
	 * Contruction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('statut', 'choice', array(
						'label' => "Choisissez le statut",
						'required' => true,
						'choices' => array(
								'Validé' => 'Validé',
								'Non validé' => 'Non validé',
						),
				))
				->add('remarque', 'textarea', array(
						'label' => "Vos remarques éventuelles",
						'required' => false,
						'attr' => array(
								'class' => 'tinymce',
								'row' => '9',
								'help' => 'Vos remarques vis à vis de cette intrigue.',
						),
				));
	}

	/**
	 * Définition de l'entité conercné
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\Relecture',
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'relecture';
	}
}
