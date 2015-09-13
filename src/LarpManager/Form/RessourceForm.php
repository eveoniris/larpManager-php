<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\RessourceForm
 *
 * @author kevin
 *
 */
class RessourceForm extends AbstractType
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
				->add('rarete','entity', array(
						'label' => "Rareté",
						'required' => true,
						'property' => 'label',
						'multiple' => false,
						'mapped' => true,
						'class' => 'LarpManager\Entities\Rarete',	
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
				'data' => 'LarpManager\Entities\Ressource',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'ressource';
	}
}