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
 * LarpManager\Form\JoueurForm
 *
 * @author kevin
 *
 */
class EtatCivilForm extends AbstractType
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
					'label' => 'Nom civil',
					'required' => true,
				))
				->add('prenom','text', array(
					'label' => 'Prénom civil',
					'required' => true,
				))
				->add('prenom_usage','text', array(
					'label' => 'Nom d\'usage',
					'required' => false,
				))
				->add('date_naissance','date', array(
						'label' => 'Date de naissance',
						'required' => false,
						'years' => range(1900,2020),
				))
				->add('telephone','text', array(
					'label' => 'Numéro de téléphone',
					'required' => true,
				))
				->add('probleme_medicaux','textarea', array(
					'label' => 'Eventuel problèmes médicaux',
					'required' => true,
				))
				->add('personne_a_prevenir','text', array(
					'label' => 'Personne à prévenir en cas de problème',
					'required' => true,
				))
				->add('tel_pap','text', array(
					'label' => 'Numéro de téléphone de la personne à prévenir',
					'required' => true,
				))
				->add('fedegn','text', array(
					'label' => 'Numéro d’adhérent FédéGN',
					'required' => false,
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
				'class' => 'LarpManager\Entities\EtatCivil',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'etatCivil';
	}
}