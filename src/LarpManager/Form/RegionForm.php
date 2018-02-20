<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use LarpManager\Form\Type\RessourceType;

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
				->add('dirigeant','text', array(
						'required' => false,	)
				)
				->add('capitale','text', array(
						'required' => false,	)
				)
				->add('protection','integer', array(
					'label' => "Valeur de protection",
					'required' => false,	)
				)
				->add('puissance','integer', array(
					'label' => "Valeur de puissance actuelle",
					'required' => false,	)
				)
				->add('puissanceMax','integer', array(
					'label' => "Valeur de puissance maximum",
					'required' => false,
				))
				->add('ressources','entity', array(
						'required' => false,
						'multiple' => true,
						'class' => 'LarpManager\Entities\Ressource',
						'property' => 'label', 
				));
	}
	
	public function getName()
	{
		return 'regionForm';
	}
}