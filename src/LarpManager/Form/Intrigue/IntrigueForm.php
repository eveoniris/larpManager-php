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

namespace LarpManager\Form\Intrigue;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use LarpManager\Form\Type\IntrigueHasGroupeType;
use LarpManager\Form\Type\IntrigueHasGroupeSecondaireType;
use LarpManager\Form\Type\IntrigueHasDocumentType;
use LarpManager\Form\Type\IntrigueHasLieuType;
use LarpManager\Form\Type\IntrigueHasEvenementType;
use LarpManager\Form\Type\IntrigueHasObjectifType;

/**
 * LarpManager\Form\Groupe\IntrigueForm
 *
 * @author kevin
 *
 */
class IntrigueForm extends AbstractType
{
	/**
	 * Contruction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('titre','text', array(
					'label' => 'Le titre de votre intrigue',
					'required' => true,
				))
				->add('intrigueHasGroupes', 'collection', array(
						'label' => "Groupes concernés",
						'required' => false,
						'allow_add' => true,
						'allow_delete' => true,
						'by_reference' => false,
						'type' => new IntrigueHasGroupeType()
				))
				->add('intrigueHasGroupeSecondaires', 'collection', array(
						'label' => "Groupes secondaires concernés",
						'required' => false,
						'allow_add' => true,
						'allow_delete' => true,
						'by_reference' => false,
						'type' => new IntrigueHasGroupeSecondaireType()
				))
				->add('intrigueHasDocuments', 'collection', array(
						'label' => "Documents concernées",
						'required' => false,
						'allow_add' => true,
						'allow_delete' => true,
						'by_reference' => false,
						'type' => new IntrigueHasDocumentType()
				))
				->add('intrigueHasLieus', 'collection', array(
						'label' => "Instances concernées",
						'required' => false,
						'allow_add' => true,
						'allow_delete' => true,
						'by_reference' => false,
						'type' => new IntrigueHasLieuType()
				))
				->add('intrigueHasEvenements', 'collection', array(
						'label' => "Evénements",
						'required' => false,
						'allow_add' => true,
						'allow_delete' => true,
						'by_reference' => false,
						'type' => new IntrigueHasEvenementType(),
				))
				->add('intrigueHasObjectifs', 'collection', array(
						'label' => "Objectifs",
						'required' => false,
						'allow_add' => true,
						'allow_delete' => true,
						'by_reference' => false,
						'type' => new IntrigueHasObjectifType(),
				))
				->add('description','textarea', array(
					'label' => 'Description de votre intrigue',
					'required' => true,
					'attr' => array(
						'class' => 'tinymce',
						'row' => 9,
						'help' => 'Une courte description de votre intrigue.',
					),
				))
				->add('text','textarea', array(
						'label' => 'Votre intrigue',
						'required' => true,
						'attr' => array(
								'class' => 'tinymce',
								'row' => 9,
								'help' => 'Développez votre intrigue içi. N\'oubliez pas d\'ajouter les groupes concernés et les événements si votre intrigue y fait référence',
						),
				))
				->add('resolution','textarea', array(
					'label' => 'Résolution de votre intrigue',
					'required' => false,
					'attr' => array(
						'class' => 'tinymce',
						'row' => 9,
						'help' => 'Indiquez de quelle manière les joueurs peuvent résoudre cette intrigue. Il s\'agit içi de la ou des différentes solutions que vous prévoyez à votre intrigue',
					),
				));
	}

	/**
	 * Définition de l'entité conercné
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => '\LarpManager\Entities\Intrigue',
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'intrigue';
	}
}
