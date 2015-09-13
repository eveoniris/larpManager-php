<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\AgeForm
 * 
 * @author kevin
 *
 */
class AgeForm extends AbstractType
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
				))
				->add('description','textarea', array(
					'required' => false,	
				))
				->add('enableCreation','choice', array(
						'required' => true,
						'choices' => array(true => 'Oui', false => 'Non'),
						'label' => 'Disponible lors de la création d\'un personnage',
				))
				->add('bonus','integer', array(
					'label' => 'XP en bonus',
					'required' => true,	
				));
	}
	
	/**
	 * Définition de la classe d'entité concernée
	 * 
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\Age',
		));
	}
	
	/**
	 * Nom du formlaire
	 */
	public function getName()
	{
		return 'age';
	}
}