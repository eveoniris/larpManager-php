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
 
namespace LarpManager\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;



/**
 * LarpManager\Form\Type\CultureHasClasseType
 *
 * @author kevin
 *
 */
class CultureHasClasseType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('classe','entity', array(
					'label' => 'Choisissez la classe',
					'required' => true,
					'class' => 'LarpManager\Entities\Classe',
					'property' => 'label',
					'query_builder' => function(\LarpManager\Repository\ClasseRepository $er) {
						$qb = $er->createQueryBuilder('c');
						$qb->where('c.creation is true');
						$qb->orderBy('c.label_masculin', 'ASC');
						return $qb;
					}
			));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\CultureHasClasse',
		));
	}

	public function getName()
	{
		return 'cultureHasClasse';
	}
}