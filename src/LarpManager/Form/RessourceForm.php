<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RessourceForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label','text', array(
					'required' => true,	
				))
				->add('rarete','entity', array(
						'label' => "Rareté",
						'required' => true,
						'property' => 'label',
						'multiple' => false,
						'mapped' => true,
						'class' => 'LarpManager\Entities\Rarete',	
				));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data' => 'LarpManager\Entities\Ressource',
		));
	}
	
	public function getName()
	{
		return 'ressource';
	}
}