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

namespace LarpManager\Form\Groupe;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use LarpManager\Form\Type\GroupeHasRessourceType;

/**
 * LarpManager\Form\GroupeRessourceForm
 *
 * @author kevin
 *
 */
class GroupeRessourceForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('randomCommun','integer', array(
					'mapped' => false,
					'label' => 'X ressources communes choisies au hasard',
					'required' => false,
					'attr' => array(
							'help' => 'Indiquez combien de ressources COMMUNES il faut ajouter à ce groupe.'
					),
			))
			->add('randomRare','integer', array(
					'mapped' => false,
					'label' => 'X ressources rares choisies au hasard',
					'required' => false,
					'attr' => array(
							'help' => 'Indiquez combien de ressources RARES il faut ajouter à ce groupe.'
					),
			))
			->add('groupeHasRessources', 'collection', array(
				'label' => "Ressources",
				'required' => false,
				'allow_add' => true,
				'allow_delete' => true,
				'by_reference' => false,
				'type' => new GroupeHasRessourceType()
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
				'data_class' => '\LarpManager\Entities\Groupe',
		));
	}
	
	/**
	 * Nom du formulaire 
	 * @return string
	 */
	public function getName()
	{
		return 'groupeRessource';
	}
}