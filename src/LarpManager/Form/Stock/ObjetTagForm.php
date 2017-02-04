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

namespace LarpManager\Form\Stock;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use LarpManager\Form\Type\TagType;

/**
 * LarpManager\Form\Type\ObjetTagForm
 *
 * @author kevin
 *
 */
class ObjetTagForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('tags', 'entity',  array(
					'label' => 'Choisissez les tags appliqués à cet objet',
					'required' => false,
					'multiple' => true,
					'property' => 'nom',
					'expanded' => true,
					'class' => 'LarpManager\Entities\Tag'
				))
				->add('news', 'collection', array(
					'label' => 'Ou créez-en de nouveaux',
					'allow_add' => true,
					'allow_delete' => true,
					'by_reference' => false,
					'type' => new TagType(),
					'mapped' => false,
				))
				->add('valider','submit', array('label' => 'valider'));	
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\Objet',
				'cascade_validation' => true,
		));
	}
	
	public function getName()
	{
		return 'objetTag';
	}
}