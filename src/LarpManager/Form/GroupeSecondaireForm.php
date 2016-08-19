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
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Form\GroupeSecondaireForm
 *
 * @author kevin
 *
 */
class GroupeSecondaireForm extends AbstractType
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
						'required' => true,
						'label' => 'Description',
						'attr' => array(
								'rows' => 9,
								'class' => 'tinymce')
				))
				->add('description_secrete','textarea', array(
						'required' => true,
						'label' => 'Description des secrets',
						'attr' => array(
								'rows' => 9,
								'class' => 'tinymce',
								'help' => 'les secrets ne sont accessibles qu\'aux membres selectionnés par le scénariste')
				))
				->add('responsable', 'entity', array(
						'required' => false,
						'label' => 'Chef du groupe',
						'class' => 'LarpManager\Entities\Personnage',
						'query_builder' => function(EntityRepository $er) {
							$qb = $er->createQueryBuilder('u');
							$qb->orderBy('u.nom', 'ASC');
							$qb->orderBy('u.surnom', 'ASC');
							return $qb;
						},
						'property' => 'identity',
				))
				->add('secondaryGroupType','entity', array(
						'label' => 'Type',
						'required' => true,
						'class' => 'LarpManager\Entities\SecondaryGroupType',
						'property' => 'label',
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
				'data_class' => '\LarpManager\Entities\SecondaryGroup',
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'secondaryGroup';
	}
}
