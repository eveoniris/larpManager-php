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

namespace LarpManager\Form\Classe;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use LarpManager\Repository\CompetenceFamilyRepository;

/**
 * LarpManager\Form\Classe\ClasseForm
 *
 * @author kevin
 *
 */
class ClasseForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label_masculin','text', array(
					'required' => true,	
				))
				->add('image_m','text', array(
					'label' => 'Adresse de l\'image utilisé pour représenté cette classe',
					'required' => true,
				))
				->add('label_feminin','text', array(
					'required' => true,	
				))
				->add('image_f','text', array(
					'label' => 'Adresse de l\'image utilisé pour représenté cette classe',
					'required' => true,
				))
				->add('creation','choice', array(
						'required' => true,
						'expanded' => true,
						'choices' => array(
							true => 'Oui',
							false => 'Non'
						),
						'label' => 'Disponible lors de la création d\'un nouveau personnage',
						'attr' => array(
							'help' => 'Choisissez si cette classe sera disponible ou pas lors de la création d\'un nouveau personnage',
						)
				))
				->add('description','textarea', array(
					'required' => false,
					'attr' => array(
						'class' => 'tinymce',			
					),
				))
				->add('competenceFamilyFavorites','entity', array(
					'label' => "Famille de compétences favorites (n'oubliez pas de cocher aussi la/les compétences acquises à la création)",
					'required' => false,
					'property' => 'label',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'class' => 'LarpManager\Entities\CompetenceFamily',
					'query_builder' => function(CompetenceFamilyRepository $cfr) {
						return $cfr->createQueryBuilder('cfr')->orderBy('cfr.label', 'ASC');
					}
					)
				)
				->add('competenceFamilyNormales','entity', array(
					'label' => "Famille de compétences normales",
					'required' => false,
					'property' => 'label',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'class' => 'LarpManager\Entities\CompetenceFamily',
					'query_builder' => function(CompetenceFamilyRepository $cfr) {
						return $cfr->createQueryBuilder('cfr')->orderBy('cfr.label', 'ASC');
					}
					)
				)
				->add('competenceFamilyCreations','entity', array(
					'label' => "Famille de compétences acquises à la création",
					'required' => false,
					'property' => 'label',
					'multiple' => true,
					'expanded' => true,
					'mapped' => true,
					'class' => 'LarpManager\Entities\CompetenceFamily',
					'query_builder' => function(CompetenceFamilyRepository $cfr) {
						return $cfr->createQueryBuilder('cfr')->orderBy('cfr.label', 'ASC');
					}
					)
				);
	}
	
	/**
	 * Définition de l'entité concernée
	 * 
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Classe',
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'classe';
	}
}