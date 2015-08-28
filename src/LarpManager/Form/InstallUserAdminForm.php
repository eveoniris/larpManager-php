<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\InstallUserAdminForm
 *
 * @author kevin
 *
 */
class InstallUserAdminForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name','text', array('required' => true))
		->add('email','email', array('required' => true))
		->add('password','password', array('required' => true));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'installUserAdminForm';
	}
}
