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

namespace LarpManager\Form\Trombinoscope;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


/**
 * LarpManager\Form\Groupe\TrombinoscopeForm
 *
 * @author kevin
 *
 */
class TrombinoscopeForm extends AbstractType
{
	/**
	 * Contruction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('renomme','integer', array(
						'label' => 'Renommé supérieur ou égale à',
						'required' => false,
					))
				->add('territoire', 'entity', array(
						'required' => false,
						'class' => 'LarpManager\Entities\Territoire',
						'property' => 'nomComplet',
						'label' => 'Par territoire',
						'expanded' => false,
						'query_builder' => function (\LarpManager\Repository\TerritoireRepository $er) {
							$qb = $er->createQueryBuilder('t');
							$qb->andWhere($qb->expr()->isNull('t.territoire'));
							$qb->orderBy('t.nom', 'ASC');
							return $qb;
						},
						'attr' => array(
								'class'	=> 'selectpicker',
								'data-live-search' => "true",
								'placeholder' => 'Territoire',
						),
				))
				->add('classe', 'entity', array(
						'required' => false,
						'class' => 'LarpManager\Entities\Classe',
						'property' => 'label',
						'label' => 'Par classe',
						'expanded' => false,
						'query_builder' => function (\LarpManager\Repository\ClasseRepository $er) {
							$qb = $er->createQueryBuilder('c');
							$qb->orderBy('c.label_masculin', 'ASC');
							return $qb;
						},
						'attr' => array(
								'class'	=> 'selectpicker',
								'data-live-search' => "true",
								'placeholder' => 'Classe',
						),
				))
				->add('competence', 'entity', array(
						'required' => false,
						'class' => 'LarpManager\Entities\Competence',
						'property' => 'label',
						'label' => 'Par competence',
						'expanded' => false,
						'query_builder' => function (\LarpManager\Repository\CompetenceRepository $er) {
							$qb = $er->createQueryBuilder('c');
							$qb->orderBy('c.id', 'ASC');
							return $qb;
						},
						'attr' => array(
								'class'	=> 'selectpicker',
								'data-live-search' => "true",
								'placeholder' => 'Competence',
						),
				))
				->add('religion', 'entity', array(
						'required' => false,
						'class' => 'LarpManager\Entities\Religion',
						'property' => 'label',
						'label' => 'Par religion',
						'expanded' => false,
						'query_builder' => function (\LarpManager\Repository\ReligionRepository $er) {
							$qb = $er->createQueryBuilder('r');
							$qb->orderBy('r.label', 'ASC');
							return $qb;
						},
						'attr' => array(
								'class'	=> 'selectpicker',
								'data-live-search' => "true",
								'placeholder' => 'Religion',
						),
				))
				->add('language', 'entity', array(
						'required' => false,
						'class' => 'LarpManager\Entities\Langue',
						'property' => 'label',
						'label' => 'Par langue',
						'expanded' => false,
						'query_builder' => function (\LarpManager\Repository\LangueRepository $er) {
							$qb = $er->createQueryBuilder('l');
							$qb->orderBy('l.label', 'ASC');
							return $qb;
						},
						'attr' => array(
								'class'	=> 'selectpicker',
								'data-live-search' => "true",
								'placeholder' => 'Langue',
						),
				))
				->add('groupe', 'entity', array(
						'required' => false,
						'class' => 'LarpManager\Entities\Groupe',
						'property' => 'nom',
						'label' => 'Par groupe',
						'expanded' => false,
						'query_builder' => function (\LarpManager\Repository\GroupeRepository $er) {
							$qb = $er->createQueryBuilder('g');
							$qb->orderBy('g.nom', 'ASC');
							return $qb;
						},
						'attr' => array(
								'class'	=> 'selectpicker',
								'data-live-search' => "true",
								'placeholder' => 'Groupe',
						),
				))
				->add('find','submit', array('label' => "Filtrer"));
	}

	/**
	 * Définition de l'entité concerné
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
		return 'trombinoscope';
	}
}
