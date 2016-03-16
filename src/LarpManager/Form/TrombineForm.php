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
class TrombineForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('trombine','file', array(
				'label' => 'Choisissez une photo pour le trombinoscope',
				'required' => true,
		));
	}
	

	/**
	 * Définition de l'entité concerné
	 *
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		/*$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\Joueur',
		));*/
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'trombine';
	}
}