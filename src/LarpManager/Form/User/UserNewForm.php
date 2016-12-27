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
 * LarpManager\Form\User\UserNewForm
 *
 * @author kevin
 *
 */
class UserNewForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('email','text', array(
					'label' => 'Adresse mail',
					'required' => true
				))
				->add('username','text', array(
					'label' => 'Nom d\'utilisateur',
					'required' => true
				))
				->add('gn','entity', array(
					'label' => 'Jeu auquel le nouvel utilisateur participe',
					'required' => false,
					'multiple' => false,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Gn',
					'property' => 'label',
				))
				->add('billet','entity', array(
					'label' => 'Choisissez le billet a donner à cet utilisateur',
					'multiple' => false,
					'expanded' => true,
					'required' => false,
					'class' => 'LarpManager\Entities\Billet',
					'property' => 'fullLabel',
					'query_builder' => function( $er) {
						$qb = $er->createQueryBuilder('b');
						$qb->orderBy('b.gn', 'ASC');
						return $qb;
					},
				));
	}
		
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'userNew';
	}
}