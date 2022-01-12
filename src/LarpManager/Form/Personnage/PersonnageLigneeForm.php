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

namespace LarpManager\Form\Personnage;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use LarpManager\Repository\LigneesRepository;
use LarpManager\Repository\PersonnageRepository;

/**
 * LarpManager\Form\PersonnageLigneeForm
 *
 * @author Kevin F.
 *
 */
class PersonnageLigneeForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('parent1','entity', array(
					'label' => "Choisissez le Parent 1 du personnage",
					'expanded' => false,
					'required' => true,
					'class' => 'LarpManager\Entities\Personnage',
					'query_builder' => function(PersonnageRepository $pr) {
						return $pr->createQueryBuilder('p')->orderBy('p.nom', 'ASC');
					},
				))
				->add('parent2','entity', array(
					'label' => "Choisissez le Parent 2 du personnage (optionnel)",
					'expanded' => false,
					'required' => false,
					'class' => 'LarpManager\Entities\Personnage',
					'query_builder' => function(PersonnageRepository $pr) {
						return $pr->createQueryBuilder('p')->orderBy('p.nom', 'ASC');
					},
				))
		;
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
	 * @return string
	 */
	public function getName()
	{
		return 'personnageLignee';
	}
}