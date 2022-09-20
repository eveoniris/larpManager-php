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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Doctrine\ORM\EntityRepository;

/**
 * LarpManager\Form\LangueForm
 *
 * @author kevin
 *
 */
class LangueForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$documentLabel = 'Téléversez un document PDF (abécédaire)';
		if ($options['hasDocumentUrl'])
		{
			$documentLabel = 'Téléversez un nouveau document PDF (abécédaire) pour remplacer le document existant';
		}
		
		$builder->add('label','text', array(
					'label' => 'Label',
					'required' => true,
				))
				->add('description','textarea', array(
					'label' => 'Description',
					'required' => false,
					'attr' => array('rows' => 10),
				))
				->add('diffusion', 'choice', array(				
					'label' => 'Degré de diffusion (de 0 à 2 : rare, courante, commune)',
					'required' => false,
					'placeholder' => 'Inconnue',
					'choices' => array(
							0 => '0 - Rare',
							1 => '1 - Courante',
							2 => '2 - Commune'
					)
				))
				->add('groupeLangue','entity', array(
					'label' => "Choisissez le groupe de langue associé",
					'multiple' => false,
					'expanded' => true,
					'required' => true,
					'class' => 'LarpManager\Entities\GroupeLangue',
					'property' => 'label',
					'query_builder' => function(EntityRepository $er) {
						return $er->createQueryBuilder('i')->orderBy('i.label', 'ASC');
					},
				))
				->add('secret', 'choice', array(				
					'label' => 'Secret',
					'required' => true,
					'choices' => array(
							false => 'Langue visible',
							true => 'Langue secrète',
					)
				))
				->add('document','file', array(
					'label' => $documentLabel,
					'required' => false,
					'mapped' => false,
					'attr' => ['accept' => '.pdf'],
					/* 'constraints' => [
						new File([
							'mimeTypes' => [
								'application/pdf',
								'application/x-pdf',
							],
							'mimeTypesMessage' => 'Veuillez choisir un document PDF valide.',
						])
					],*/
				))
			;
	}
	
	/**
	 * Définition de l'entité concerné
	 * 
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Langue',
				'hasDocumentUrl' => false
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'langue';
	}
}