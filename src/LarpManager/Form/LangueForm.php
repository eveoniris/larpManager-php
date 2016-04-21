<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
		$builder->add('label','text', array(
					'label' => 'Label',
					'required' => true,
				))
				->add('description','textarea', array(
					'label' => 'Description',
					'required' => false,
					'attr' => array('rows' => 10),
				))
				->add('diffusion','integer', array(
						'label' => 'Degrés de diffusion (de 0 à 2 : ancien, courant, très répandu)',
						'required' => false,
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
				'data_class' => 'LarpManager\Entities\Langue',
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