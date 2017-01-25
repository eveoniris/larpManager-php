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

namespace LarpManager\Form\GroupeSecondaire;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Form\GroupeSecondare\GroupeSecondaireNewMembreForm
 *
 * @author kevin
 *
 */
class GroupeSecondaireNewMembreForm extends AbstractType
{
	/**
	 * Contruction du formulaire
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('personnage', 'entity', array(
				'required' => false,
				'label' => 'Choississez le personnage',
				'class' => 'LarpManager\Entities\Personnage',
				'query_builder' => function(EntityRepository $er) {
					$qb = $er->createQueryBuilder('p');
					$qb->orderBy('p.nom', 'ASC');
					return $qb;
				},
				'attr' => array(
						'class'	=> 'selectpicker',
						'data-live-search' => "true",
						'placeholder' => 'Personnage',
				),
				'property' => 'nom',
				'mapped' => false,
			))
			->add('submit','submit', array('label' => "Ajouter"));;
	}

	/**
	 * Définition de l'entité conercné
	 *
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'groupeSecondaireNewMembre';
	}
}
