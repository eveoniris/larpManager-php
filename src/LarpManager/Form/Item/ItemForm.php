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

namespace LarpManager\Form\Item;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\Monnaie\ItemForm
 *
 * @author kevin
 *
 */
class ItemForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label','text', array(
					'label' => 'Label',
					'required' => true,
				))
				->add('description','textarea', array(
					'required' => true,
					'label' => 'Description',
					'attr' => array(
						'rows' => 9,
						'class' => 'tinymce',
						'help' => 'Quelques mots pour décrire votre objet, c\'est le texte décrivant ce que la personne voit, situé au centre de l\'étiquette',
					),
					
				))
				->add('numero','integer', array(
					'required' => false,
					'label' => 'Numéro',
					'attr' => array(
						'help' => 'Situé en haut à gauche, il permet de retrouver rapidement l\'objet. NE REMPLISSEZ CETTE CASE QUE SI VOTRE OBJET DE JEU DISPOSE DEJA D\'UN NUMERO (il a été créé pendant le LH2 ou le LH1). Si vous laissez vide, un numero lui sera automatiquement attribué.',
					),
				))
				->add('quality','entity', array(
					'required' => true,
					'label' => 'Qualité',
					'class' => 'LarpManager\Entities\Quality',
					'property' => 'label',
					'attr' => array(
						'help' => 'Qualité de l\'objet',
					),					
				))
				->add('identification', 'choice', array(
					'required' => true,
					'label' => 'Identification',
					'choices' => array(
						81 => 'Rien de spécial',
						01 => 'Objet spécial mais non magique',
						11 => 'Objet enchanté par magie',
						21 => 'Objet enchanté par prêtrise Adonis',
						22 => 'Objet enchanté par prêtrise Anu',
						23 => 'Objet enchanté par prêtrise Asura',
						24 => 'Objet enchanté par prêtrise Bel',
						25 => 'Objet enchanté par prêtrise Bori',
						26 => 'Objet enchanté par prêtrise Crom',
						27 => 'Objet enchanté par prêtrise Derketo',
						28 => 'Objet enchanté par prêtrise Erlik',
						29 => 'Objet enchanté par prêtrise Ishtar',
						30 => 'Objet enchanté par prêtrise Ibis',
						31 => 'Objet enchanté par prêtrise Jhebbal Shag',
						32 => 'Objet enchanté par prêtrise Jhil',
						33 => 'Objet enchanté par prêtrise Mitra',
						34 => 'Objet enchanté par prêtrise Pteor',
						35 => 'Objet enchanté par prêtrise Set',
						36 => 'Objet enchanté par prêtrise Wiccana',
						37 => 'Objet enchanté par prêtrise Ymir',
						38 => 'Objet enchanté par prêtrise Yun',
						39 => 'Objet enchanté par prêtrise Zath',
						40 => 'Objet enchanté par prêtrise Ancêtre',
						41 => 'Objet enchanté par prêtrise Ashtur',
						42 => 'Objet enchanté par prêtrise Tolometh',
						43 => 'Objet enchanté par prêtrise Nature',
						44 => 'Objet enchanté par prêtrise Jullah-Hanuman',
						45 => 'Objet enchanté par prêtrise Dagoth-Dagon',
						46 => 'Objet enchanté par prêtrise Louhi',
						47 => 'Objet enchanté par prêtrise Shub-niggurath',							
					),
					'attr' => array(
						'help' => 'Information sur l\'objet'
					),
				))
				->add('special','textarea', array(
					'required' => true,
					'label' => 'Description spéciale',
					'attr' => array(
						'rows' => 9,
						'class' => 'tinymce',
						'help' => 'Quelques mots pour un effet spécial. Ce texte est révélé au joueur si celui-ci réussi à identifier l\'objet',
					),					
				))
				->add('couleur','choice', array(
					'required' => true,
					'label' => 'Couleur de l\'étiquette',
					'choices' => array(
						'orange' => 'Orange : Ne prendre que l\'etiquette', 
						'bleu' => 'Bleau: L\'objet peux être pris'),
					'attr' => array(
						'help' => 'La couleur de l\'étiquette indique si l\'on peux prendre l\'objet en lui-même ou seulement l\'étiquette',
					),					
				))
				->add('submit', 'submit', array(
						'label' => 'Valider',
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
				'class' => 'LarpManager\Entities\Item',
		));
	}
	
	/**
	 * 
	 * 
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'item';
	}
}