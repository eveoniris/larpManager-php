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

namespace LarpManager\Form\Rumeur;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use LarpManager\Form\Type\RumeurHasGroupeType;
use LarpManager\Form\Type\RumeurHasEvenementType;
use LarpManager\Form\Type\RumeurHasObjectifType;

/**
 * LarpManager\Form\Groupe\RumeurForm
 *
 * @author kevin
 *
 */
class RumeurForm extends AbstractType
{
	/**
	 * Contruction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('text','textarea', array(
							'label' => 'Le contenu de votre rumeur',
							'required' => true,
							'attr' => array(
									'class' => 'tinymce',
									'row' => 9,
									'help' => 'Votre rumeur. Ce texte sera disponibles aux joueurs membres du territoire dans lequel cours la rumeur.',
							),
					))
					->add('territoire', 'entity', array(
							'label' => "Territoire dans lequel cours la rumeur",
							'required' => false,
							'class' => 'LarpManager\Entities\Territoire',
							'query_builder' => function(EntityRepository $er) {
								$qb = $er->createQueryBuilder('t');
								$qb->orderBy('t.nom', 'ASC');
								return $qb;
							},
							'property' => 'nom',
							'attr' => array(
									'help' => 'Le territoire choisi donnera accès à la rumeur à tous les personnages membre de ce territoire. Remarque, si vous choisissez un territoire de type pays (ex : Aquilonnie), les territoires qui en dépendent (ex : bossonie du nord) auront aussi accès à la rumeur. Si vous ne choisissez pas de territoire, la rumeur sera accessible à tous.'	
							),
					))
					->add('gn', 'entity', array(
							'label' => "GN référant",
							'required' => true,
							'class' => 'LarpManager\Entities\Gn',
							'query_builder' => function(EntityRepository $er) {
								$qb = $er->createQueryBuilder('g');
								$qb->orderBy('g.id', 'DESC');
								return $qb;
							},
							'property' => 'label',
							'attr' => array(
									'help' => 'Choisissez le GN dans lequel sera utilisé votre rumeur'
							),
					))
					->add('visibility','choice', array(
						'required' => true,
						'label' =>  'Visibilité',
						'choices' => array(
								'non_disponible' => 'Brouillon',
								'disponible' => 'Disponible pour les joueurs'
						),
						'attr' => array(
								'La rumeur ne sera visible par les joueurs que lorsque sa visibilité sera "Disponible pour les joueurs".'
						)
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
				'data_class' => '\LarpManager\Entities\Rumeur',
		));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'rumeur';
	}
}
