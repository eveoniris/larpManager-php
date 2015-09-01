<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\GnInscriptionForm
 *
 * @author kevin
 *
 */
class GnInscriptionForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('idGn','hidden');
	}
	
	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'inscriptionGn';
	}
}