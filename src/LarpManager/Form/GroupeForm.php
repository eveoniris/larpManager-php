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

use LarpManager\Form\Type\ClasseType;

/**
 * LarpManager\Form\GroupeForm
 *
 * @author kevin
 *
 */
class GroupeForm extends AbstractType
{
	/**
	 * Contruction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom','text')
				->add('numero','integer', array(
						'required' => true,
				))
				->add('pj', 'choice', array(
						'label' => 'Type de groupe',
						'required' => true,
						'choices' => array(
								true => 'Groupe composé de PJs',
								false => 'Groupe composé PNJs',
						),
						'expanded' => true,
				))
				->add('description','textarea', array(
						'required' => false,
						'attr' => array(
							'class' => 'tinymce',
							'row' => 9,
						),
				))
				->add('materiel','textarea', array(
						'required' => false,
				))
				->add('territoires','entity', array(
						'label' => 'Territoire',
						'required' => false,
						'multiple' => true,
						'expanded' => false,
						'class' => 'LarpManager\Entities\Territoire',
						'query_builder' => function(EntityRepository $er) {
							$qb = $er->createQueryBuilder('u');
							$qb->orderBy('u.nom', 'ASC');
							return $qb;
						},
						'property' => 'nom',
				))
				->add('code','text', array(
						'required' => false,
				))
				->add('scenariste','entity', array(
						'label' => 'Scénariste',
						'required' => false, 
						'class' => 'LarpManager\Entities\User',
						'property' => 'name',
						'query_builder' => function(EntityRepository $er) {
							$qb = $er->createQueryBuilder('u');
							$qb->where($qb->expr()->orX(
									$qb->expr()->like('u.rights', $qb->expr()->literal('%ROLE_SCENARISTE%')),
									$qb->expr()->like('u.rights', $qb->expr()->literal('%ROLE_ADMIN%'))));
							return $qb;
						}
				))
				->add('jeuStrategique','checkbox', array(
						'label' => "Participe au jeu stratégique ?",
						'required' => false,
				))
				->add('jeuMaritime','checkbox', array(
						'label' => "Participe au jeu maritime ?",
						'required' => false,
				))
				->add('gns', 'entity', array(
						'label' => 'GNs auquel ce groupe participe',
						'multiple' => true,
						'required' => false,
						'class' => 'LarpManager\Entities\Gn',
						'property' => 'label',
				))
				->add('groupeClasses', 'collection', array(
						'label' => "Composition",
						'required' => false,
						'allow_add' => true,
						'allow_delete' => true,
						'by_reference' => false,
						'type' => new ClasseType()
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
				'data_class' => '\LarpManager\Entities\Groupe',
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'groupe';
	}
}
