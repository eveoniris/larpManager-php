<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InstallUserAdminForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name','text', array('required' => true))
		->add('email','email', array('required' => true))
		->add('password','password', array('required' => true));
	}

	public function getName()
	{
		return 'installUserAdminForm';
	}
}
