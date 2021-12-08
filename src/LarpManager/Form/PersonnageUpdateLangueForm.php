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
 * LarpManager\Form\PersonnageUpdateLangueForm
 *
 * @author kevin
 *
 */
class PersonnageUpdateLangueForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * Seul les éléments ne dépendant pas des points d'expérience sont modifiables
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('langues','entity', array(
				'required' => true,
				'multiple' => true,
				'expanded' => true,
				'class' => 'LarpManager\Entities\Langue',
				'choice_label' => 'label',
				'label' => 'Choisissez les langues du personnage',
				'mapped' => false,
				'query_builder' => function(EntityRepository $repository) { 
					return $repository->createQueryBuilder('l')->addOrderBy('l.secret', 'ASC')->addOrderBy('l.diffusion', 'DESC')->addOrderBy('l.label', 'ASC');
				}
		));

	}

	/**
	 * Définition de l'entité concerné
	 *
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'personnageUpdateLangue';
	}
}