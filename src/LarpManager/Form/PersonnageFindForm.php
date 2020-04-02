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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\PersonnageFindForm
 *
 * @author kevin
 *
 */
class PersonnageFindForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
	    //echo $options['test'];exit;
		$builder->add('value','text', array(
					'required' => false,	
					'label' => 'Recherche',
					'attr' => array(
						'placeholder' => 'Votre recherche',
						'aria-label' => "...",
					),
				))
				->add('type','choice', array(
					'required' => false,
					'choices' => array(
						'id' => 'ID',
						'nom' => 'Nom',
					),
					'label' => 'Type',
					'attr' => array(
						'aria-label' => "...",
					)
				))
				->add('religion', 'entity',  array(
					'required' => false,
					'label' => 'Par religion',
					'class' => 'LarpManager\Entities\Religion',
					'property' => 'label',
				))
				->add('competence', 'entity', array(
					'required' => false,
					'label' => 'Par compétence',
					'class' => 'LarpManager\Entities\Competence',
					'property' => 'label',
				))
				->add('classe','entity',array(
					'required' => false,
					'label' => 'Par classe',
					'class' => 'LarpManager\Entities\Classe',
					'property' => 'label',
				));
	}
	
	/**
	 * Définition de l'entité concerné
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(
            array(
                'religion'=>'',
                'classe'=>'',
                'competence'=>'',
            )
        );
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'personnageFind';
	}
}