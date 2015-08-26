<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompetenceForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('competenceFamily','entity', array(
					'label' => 'Famille',
					'required' => true,
					'class' => 'LarpManager\Entities\CompetenceFamily',
					'property' => 'label',
				))
				->add('level','entity', array(
					'label' => 'Niveau',
					'required' => true,
					'class' => 'LarpManager\Entities\Level',
					'property' => 'label',
				))
				->add('description','textarea', array(
					'required' => false,	
				));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class' => 'LarpManager\Entities\Competence',
		));
	}
	
	public function getName()
	{
		return 'competence';
	}
}