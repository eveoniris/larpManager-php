<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstallDatabaseForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('database_name','text', array('required' => true))
				->add('database_host','text', array('required' => true))
				->add('database_port','text', array('required' => false))
				->add('database_user','text', array('required' => true))
				->add('database_password','password', array('required' => false));
	}

	public function getName()
	{
		return 'installDatabaseForm';
	}
}
