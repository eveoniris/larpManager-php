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

use LarpManager\Form\Type\ObjetCaracType;
use LarpManager\Form\Type\PhotoType;

/**
 * LarpManager\Form\Type\ObjetType
 *
 * @author kevin
 *
 */
class ObjetForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom','text',  array('required' => true))
				->add('numero','text', array('required' => true))
				->add('description','textarea', array('required' => false))
				
				->add('photo', new PhotoType(),  array('required' => false))
				
				->add('proprietaire','entity', array('required' => false, 'class' => 'LarpManager\Entities\Proprietaire', 'property' => 'nom'))	
				->add('responsable','entity', array('required' => false, 'class' => 'LarpManager\Entities\User', 'property' => 'name'))		
				->add('rangement','entity', array('required' => false, 'class' => 'LarpManager\Entities\Rangement', 'property' => 'adresse'))
				->add('etat','entity', array('required' => false, 'class' => 'LarpManager\Entities\Etat', 'property' => 'label'))				
				->add('tags','entity', array('required' => false, 'class' => 'LarpManager\Entities\Tag', 'property' => 'nom', 'multiple' => true))
				
				->add('objetCarac', new ObjetCaracType(), array('required' => false))
				
				->add('cout','integer', array('required' => false))
				->add('nombre','integer', array('required' => false))
				->add('budget','integer', array('required' => false))
				->add('investissement','choice', array('choices' => array('true' => 'ré-utilisable','false' =>'usage unique')));	
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
		return 'objet';
	}
}