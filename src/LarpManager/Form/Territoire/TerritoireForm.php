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

namespace LarpManager\Form\Territoire;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\TerritoireForm
 *
 * @author kevin
 *
 */
class TerritoireForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom','text', array(
					'label' => 'Nom',
					'required' => true,
				))
				->add('appelation','entity', array(
					'label' => 'Choisissez l\'appelation de ce territoire',
					'required' => true,
					'class' => 'LarpManager\Entities\Appelation',
					'multiple' => false,
					'mapped' => true,
					'property' => 'label', 
				))
				->add('description','textarea', array(
					'label' => 'Description',
					'required' => false,
					'attr' => array(
							'class' => 'tinymce',
							'rows' => 10),
				))
				->add('description_secrete','textarea', array(
					'label' => 'Description connue des habitants',
					'required' => false,
					'attr' => array(
							'class' => 'tinymce',
							'rows' => 10),
				))
				->add('statut','choice', array(
					'label' => 'Statut',
					'required' => false,
					'choices' => array('Normal' => 'Normal', 'Instable' => 'Instable')
				))
				->add('ordreSocial','integer', array(
					'label' => 'Ordre social',
					'required' => false,
					'attr' => array(
							'min' => 1,
							'max' => 5
					)
				))
				->add('geojson', 'textarea', array(
					'label' => 'GeoJSON',
					'required' => false,
					'attr' => array('rows' => 10),
				))
				->add('capitale','text', array(
					'label' => 'Capitale',
					'required' => false,
				))
				->add('politique','text', array(
					'label' => 'Système politique',
					'required' => false,
				))
				->add('dirigeant','text', array(
					'label' => 'Dirigeant',
					'required' => false,
				))
				->add('population','text', array(
					'label' => 'Population',
					'required' => false,
				))
				->add('symbole','text', array(
					'label' => 'Symbole',
					'required' => false,
				))
				->add('tech_level','text', array(
					'label' => 'Niveau technologique',
					'required' => false,
				))
				->add('type_racial','textarea', array(
						'label' => 'Type racial',
						'required' => false,
						'attr' => array('rows' => 5),
				))
				->add('inspiration','textarea', array(
						'label' => 'Inspiration',
						'required' => false,
						'attr' => array('rows' => 5),
				))
				->add('armes_predilection','textarea', array(
						'label' => 'Armes de prédilection',
						'required' => false,
						'attr' => array('rows' => 5),
				))
				->add('vetements','textarea', array(
						'label' => 'Vetements',
						'required' => false,
						'attr' => array('rows' => 5),
				))
				->add('noms_masculin','textarea', array(
						'label' => 'Noms masculins',
						'required' => false,
						'attr' => array('rows' => 5),
				))
				->add('noms_feminin','textarea', array(
						'label' => 'Noms féminins',
						'required' => false,
						'attr' => array('rows' => 5),
				))
				->add('frontieres','textarea', array(
						'label' => 'Frontières',
						'required' => false,
						'attr' => array('rows' => 5),
				))
				->add('importations','entity', array(
					'required' => false,
					'label' => 'Importations',
					'class' => 'LarpManager\Entities\Ressource',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'property' => 'label', 						
				))
				->add('exportations','entity', array(
					'required' => false,
					'label' => 'Exportations',
					'class' => 'LarpManager\Entities\Ressource',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'property' => 'label',
				))
				->add('languePrincipale','entity', array(
					'required' => false,
					'label' => 'Langue principale',
					'class' => 'LarpManager\Entities\Langue',
					'multiple' => false,
					'mapped' => true,
					'property' => 'label',
				))
				->add('langues','entity', array(
					'required' => false,
					'label' => 'Langues parlées (selectionnez aussi la langue principale)',
					'class' => 'LarpManager\Entities\Langue',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'property' => 'label',
				))
				->add('religionPrincipale','entity', array(
					'required' => false,
					'label' => 'Religion dominante',
					'class' => 'LarpManager\Entities\Religion',
					'multiple' => false,
					'mapped' => true,
					'property' => 'label',
				))
				->add('religions','entity', array(
					'required' => false,
					'label' => 'Religions secondaires',
					'class' => 'LarpManager\Entities\Religion',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'property' => 'label',
				))
				->add('territoire','entity', array(
					'required' => false,
					'label' => 'Ce territoire dépend de ',
					'class' => 'LarpManager\Entities\Territoire',
					'property' => 'nom',
					'empty_value' => 'Aucun, territoire indépendant',
					'empty_data'  => null
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
				'data_class' => 'LarpManager\Entities\Territoire',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'territoire';
	}
}