<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\PotionForm
 *
 * @author kevin
 *
 */
class PotionForm extends AbstractType
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
					'required' => true,
					'label' => 'Label',
				))
				->add('document','file', array(
						'label' => 'Téléversez un document',
						'required' => true,
						'mapped' => false
				))
				->add('description','textarea', array(
					'required' => false,
					'label' => 'Description',
					'attr' => array(
							'class' => 'tinymce',
							'rows' => 9),
				));
	}
	
	/**
	 * Définition de l'entité concerné
	 *
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Potion',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'potion';
	}
	
}