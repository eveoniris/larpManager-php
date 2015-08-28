<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * LarpManager\Form\UserForm
 *
 * @author kevin
 *
 */
class UserForm extends AbstractType
{
	/**
	 * Construction du formulaire
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
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
	
	/**
	 * Nom du formulaire
	 */	
	public function getName()
	{
		return 'user';
	}
}