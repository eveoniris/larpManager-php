<?php

namespace LarpManager\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use LarpManager\Form\Type\CompetenceType;

class PersonnageForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('nom','text', array(
					'label' => '' 
				))
				->add('intrigue','checkbox', array(
					'label' => 'Participer aux intrigues'
				))
				->add('classe','entity', array(
					'label' =>  'Classe',
					'property' => 'label',
					'class' => 'LarpManager\Entities\Classe',
					'query_builder' => function()
				));
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'LarpManager\Entities\Personnage',
		));
	}
	
	public function getName()
	{
		return 'personnage';
	}
}