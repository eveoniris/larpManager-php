<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchObjetForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{		
		$builder->add('type','choice', array(
					'required' => true,
					'choices' => array(
						'nom' => 'Nom',
						'numero' => 'Numero')))
				->add('value','text', array('required' => true));
		
	}

	public function getName()
	{
		return 'searchObjetForm';
	}
}
