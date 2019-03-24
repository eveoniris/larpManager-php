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

/**
 * LarpManager\Form\CompetenceForm
 *
 * @author kevin
 *
 */
class CompetenceForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('competenceFamily','entity', array(
					'label' => 'Famille',
					'required' => true,
					'class' => 'LarpManager\Entities\CompetenceFamily',
					'property' => 'label',
				))
				->add('level','entity', array(
					'label' => 'Niveau',
					'required' => true,
					'class' => 'LarpManager\Entities\Level',
					'property' => 'label',
				))								
				->add('description','textarea', array(
					'required' => false,
					'attr' => array(
							'class' => 'tinymce'
					),
				))
				->add('langueConnue','integer', array(
				    'label' => 'Langue connue',
				    'required' => false
				))
				->add('langueAncienneConnue','integer', array(
				    'label' => 'Langue ancienne connue',
				    'required' => false
				))
				->add('sortNiveau1','integer', array(
				    'label' => 'Sort niveau 1',
				    'required' => false
				))
				->add('sortNiveau2','integer', array(
				    'label' => 'Sort niveau 2',
				    'required' => false
				))
				->add('sortNiveau3','integer', array(
				    'label' => 'Sort niveau 3',
				    'required' => false
				))
				->add('sortNiveau4','integer', array(
				    'label' => 'Sort niveau 4',
				    'required' => false
				))
				->add('alchimieNiveau1','integer', array(
				    'label' => 'Alchimie niveau 1',
				    'required' => false
				))
				->add('alchimieNiveau2','integer', array(
				    'label' => 'Alchimie niveau 2',
				    'required' => false
				))
				->add('alchimieNiveau3','integer', array(
				    'label' => 'Alchimie niveau 3',
				    'required' => false
				))
				->add('alchimieNiveau4','integer', array(
				    'label' => 'Alchimie niveau 4',
				    'required' => false
				))				
				->add('document','file', array(
					'label' => 'Téléversez un document',
					'required' => true,
					'mapped' => false
				))
				->add('materiel','textarea', array(
					'label' => 'Matériel necessaire',
					'required' => false,
					'attr' => array(
							'class' => 'tinymce'
					),
				));
	}
	
	/**
	 * Définition de l'entité concerné
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\Competence',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'competence';
	}
}