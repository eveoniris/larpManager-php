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

namespace LarpManager\Form\Debriefing;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use LarpManager\Entities\Groupe;
use LarpManager\Entities\Gn;

/**
 * LarpManager\Form\DebriefingForm
 *
 * @author kevin
 *
 */
class DebriefingForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('titre', TextType::class, array(
					'required' => true,
					'label' => 'Titre',
				))
				->add('text', TextareaType::class, array(
					'required' => true,
					'label' => 'Contenu',
					'attr' => array(
						'class' => 'tinymce',
						'rows' => 15),
				))
				->add('groupe', EntityType::class, array(
						'required' => true,
						'label' => 'Groupe',
						'class' => Groupe::class,
						'property' => 'nom',
				))
				->add('gn', EntityType::class, array(
						'required' => true,
						'label' => 'GN',
						'class' => Gn::class,
						'property' => 'label',
						'placeholder' => 'Choisissez le GN auquel est lié ce debriefing',
						'empty_data'  => null
				))
                ->add('document', FileType::class, array(
                        'label' => 'Téléversez un document PDF',
                        'required' => false,
                        'mapped' => false,
                        'attr' => ['accept' => '.pdf'],));
	}
	
	/**
	 * Définition de l'entité concerné
	 * 
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Debriefing',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'debriefing';
	}
}