<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\ClasseForm
 *
 * @author kevin
 *
 */
class ConstructionForm extends AbstractType
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
					'label' => 'Le nom de la construction',
					'required' => true,
				))
				->add('description', 'textarea', array(
					'label' => 'La description de la construction',
					'required' => true,
					'attr' => array(
							'class' => 'tinymce',
							'rows' => 9,
					)
				))
				->add('defense', 'integer', array(
					'label' => 'La valeur de défense de la construction',
					'required' => true,
				));
	}
	
	/**
	 * Définition de l'entité concernée
	 *
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{	
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Construction',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'construction';
	}
	
}