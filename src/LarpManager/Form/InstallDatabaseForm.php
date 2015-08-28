<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LarpManager\Form\InstallDatabaseForm
 *
 * @author kevin
 *
 */
class InstallDatabaseForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('database_name','text', array('required' => true))
				->add('database_host','text', array('required' => true))
				->add('database_port','text', array('required' => false))
				->add('database_user','text', array('required' => true))
				->add('database_password','password', array('required' => false));
	}

	/**
	 * Nom du formulaire
	 */
	public function getName()
	{
		return 'installDatabaseForm';
	}
}
