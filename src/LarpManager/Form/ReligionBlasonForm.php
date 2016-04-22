<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\TrombineForm
 *
 * @author kevin
 *
 */
class ReligionBlasonForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('blason','file', array(
					'label' => 'Choisissez votre fichier',
					'required' => true,
					'mapped' => false,
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
				'data_class' => 'LarpManager\Entities\Religion',
		));
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'religionBlason';
	}
}