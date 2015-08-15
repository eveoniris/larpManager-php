<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegionForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom','text', array(
					'required' => true,	)
				)
				->add('description','textarea', array(
					'required' => false,	)
				)
				->add('pays','entity', array(
					'required' => true,
					'class' => 'LarpManager\Entities\Pays',
					'property' => 'nom', )
				)				
				->add('protection','integer', array(
					'label' => "Valeur de protection",
					'required' => false,	)
				)
				->add('puissance','integer', array(
					'label' => "Valeur de puissance actuelle",
					'required' => false,	)
				)
				->add('puissance_max','integer', array(
					'label' => "Valeur de puissance maximum",
					'required' => false,	)
				);
	}
	
	public function getName()
	{
		return 'regionForm';
	}
}