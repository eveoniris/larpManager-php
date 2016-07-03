<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * LarpManager\Form\ReligionForm
 *
 * @author kevin
 *
 */
class ReligionForm extends AbstractType
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
				->add('spheres', 'entity', array(
					'label' => 'Sphères',
					'multiple' => true,
					'expanded' => true,
					'class' => 'LarpManager\Entities\Sphere',
					'choice_label' => 'label',
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
				'data_class' => 'LarpManager\Entities\Religion',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'religion';
	}
}