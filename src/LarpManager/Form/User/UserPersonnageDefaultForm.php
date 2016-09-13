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

namespace LarpManager\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Form\User\UserPersonnageDefaultForm
 *
 * @author kevin
 *
 */
class UserPersonnageDefaultForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('personnageRelatedByPersonnageId','entity', array(
					'required' => false,
					'label' => 'Choisissez votre personnage par défaut. Ce personnage sera utilisé pour signer vos messages',
					'multiple' => false,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Personnage',
					'property' => 'identity',
					'placeholder' => 'Aucun',
					'empty_data'  => null,
					'query_builder' => function(EntityRepository $er) use ($options) {
						return $er->createQueryBuilder('p')
							->join('p.users', 'u')
							->where('u.id = :userId')
							->setParameter('userId', $options['user_id']);
					},
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
				'data_class' => 'LarpManager\Entities\User',
				'user_id' => null,
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'userPersonnageDefault';
	}
}