<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use LarpManager\Form\Type\CompetenceType;

class UserForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('email','email', array(
					'label' => 'Adresse email',
					'required' => true
				))
				->add('name','text', array(
					'label' => 'Nom ou pseudo',
					'required' => true
				));
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
	}
	
	public function getName()
	{
		return 'user';
	}
}